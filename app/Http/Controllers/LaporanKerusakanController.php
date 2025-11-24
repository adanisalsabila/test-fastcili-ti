<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\CreditScoreTeknisi;
use App\Models\PelaporLaporan;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Chart\{
    Chart,
    DataSeries,
    DataSeriesValues,
    PlotArea,
    Legend,
    Title
};
use App\Http\Controllers\HomeController;
use Carbon\Carbon;


class LaporanKerusakanController extends Controller
{
    public function index()
    {
        // Ambil semua laporan + pelapor
        $laporan = PelaporLaporan::with([
            'laporan.fasilitas.ruangan.gedung',
            'laporan.status',
            'user'
        ])->get();
        $laporanAuth = PelaporLaporan::where('id_user', Auth::id())->get();
        $gedung = Gedung::all();
        return view('laporan.index', compact('gedung', 'laporan', 'laporanAuth'));
    }

    public function getRuangan($idGedung)
    {
        return Ruangan::where('id_gedung', $idGedung)->get();
    }

    // Untuk Card sebelah kanan
    public function getFasilitasTerlapor($idRuangan)
    {
        return LaporanKerusakan::with('fasilitas')
            ->whereHas('fasilitas', fn($q) => $q->where('id_ruangan', $idRuangan))
            ->whereIn('id_status', [1, 2, 3])
            // ->where('id_user', '!=', Auth::user()->id)
            ->get()
            ->map(fn($lap) => [
                'id_laporan' => $lap->id_laporan,
                'nama_fasilitas' => $lap->fasilitas->nama_fasilitas,
                'deskripsi' => $lap->deskripsi,
                'foto_kerusakan' => $lap->foto_kerusakan,
                'tanggal_lapor' => $lap->tanggal_lapor
            ]);
    }

    // Untuk drop-down sebelah kiri
    public function getFasilitasBelumLapor($idRuangan)
    {
        $terlaporIds = LaporanKerusakan::whereIn('id_status', [1, 2, 3])
            ->pluck('id_fasilitas')
            ->toArray();

        return Fasilitas::where('id_ruangan', $idRuangan)
            ->whereNotIn('id_fasilitas', $terlaporIds)
            ->get();
    }


    public function destroy($id)
    {
        if (auth()->user()->id_level != 3) {
            $pelapor = pelaporLaporan::find($id);

            if (!$pelapor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $laporan_id = $pelapor->id_laporan;

            // Hitung jumlah pelapor untuk laporan ini
            $jumlahPelapor = pelaporLaporan::where('id_laporan', $laporan_id)->count();

            // Hapus pelapor ini
            $pelapor->delete();

            // Jika pelapor tinggal 1, hapus juga laporan utama
            if ($jumlahPelapor == 1) {
                $laporanUtama = laporanKerusakan::find($laporan_id);
                if ($laporanUtama) {
                    // Jika ada relasi lain yang perlu dihapus juga, tambahkan di sini
                    $laporanUtama->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return back();
        }
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        // Jika dukungan laporan sudah ada
        if ($request->filled('dukungan_laporan')) {
            $laporanId = $request->dukungan_laporan;
            // Jika Fasilitas yang dilaporkan sedang tahap perbaikan
            $sedangDiperbaiki = KriteriaPenilaian::where('id_laporan', $laporanId)->exists();
            if ($sedangDiperbaiki) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fasilitas ini sedang dalam proses perbaikan atau sudah dilaporkan.'
                ]);
            }

            // Cek apakah user sudah mendukung laporan ini
            $sudahMendukung = PelaporLaporan::where('id_laporan', $laporanId)
                ->where('id_user', $userId)
                ->exists();

            if ($sudahMendukung) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendukung laporan ini sebelumnya.'
                ]);
            }

            // Validasi untuk dukungan laporan
            $request->validate([
                'tambahan_deskripsi' => 'nullable|string|max:255',
            ]);

            // Simpan dukungan
            if ($request->tambahan_deskripsi) {
                PelaporLaporan::create([
                    'id_laporan' => $laporanId,
                    'id_user' => $userId,
                    'deskripsi_tambahan' => $request->tambahan_deskripsi,
                ]);
            } else {
                PelaporLaporan::create([
                    'id_laporan' => $laporanId,
                    'id_user' => $userId,
                    'deskripsi_tambahan' => LaporanKerusakan::where('id_laporan', $laporanId)->value('deskripsi'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dukungan terhadap laporan berhasil dikirim.'
            ]);
        } else {
            $fasilitas = Fasilitas::findOrFail($request->id_fasilitas);
            // Validasi laporan baru
            $request->validate([
                'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
                'deskripsi' => 'required|string|max:255',
                'jumlah_kerusakan' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:' . $fasilitas->jumlah
                ],
                'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Cek apakah ada laporan aktif untuk fasilitas yang sama
            $existing = LaporanKerusakan::where('id_fasilitas', $request->id_fasilitas)
                ->whereIn('id_status', [1, 2, 3, 4]) // status aktif
                ->first();


            // Cek apakah user sudah pernah melaporkan laporan aktif ini
            if ($existing) {
                $sudahMelaporkan = PelaporLaporan::where('id_laporan', $existing->id_laporan)
                    ->where('id_user', $userId)
                    ->whereHas('laporan', function ($query) {
                        $query->where('id_status', '!=', 4);
                    })
                    ->exists();

                if ($sudahMelaporkan) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah pernah melaporkan kerusakan ini sebelumnya.'
                    ]);
                }
            }

            // Upload foto
            $fotoFullPath = $request->file('foto_kerusakan')->store('uploads/laporan_kerusakan', 'public');
            $filename = basename($fotoFullPath);

            // Simpan laporan baru
            $laporan = LaporanKerusakan::create([
                'id_fasilitas' => $request->id_fasilitas,
                'deskripsi' => $request->deskripsi,
                'jumlah_kerusakan' => $request->jumlah_kerusakan,
                'foto_kerusakan' => $filename,
                'tanggal_lapor' => now(),
                'id_status' => 1,
            ]);

            // Simpan pelapor
            PelaporLaporan::create([
                'id_laporan' => $laporan->id_laporan,
                'id_user' => $userId,
                'deskripsi_tambahan' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan baru berhasil dikirim.'
            ]);
        }
    }

    public function trending(Request $request)
    {
        if (auth()->user()->id_level != 3) {
            $bobot = [
                'MHS' => 1,
                'TDK' => 2,
                'DSN' => 3,
                'ADM' => 3
            ];

            if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
                $pelapor = PelaporLaporan::with(['user.level', 'laporan'])
                    ->whereHas('laporan', function ($query) {
                        $query->where('id_status', 1);
                    })
                    ->get();
            } else {
                $pelapor = PelaporLaporan::with(['user.level', 'laporan'])
                    ->whereHas('laporan', function ($query) {
                        $query->where('id_status', '!=', 4);
                    })
                    ->get();
            }

            $skorPerLaporan = [];
            foreach ($pelapor as $item) {
                $idLaporan = $item->id_laporan;
                $kodeLevel = $item->user->level->kode_level ?? 'OTHER';
                $skor = $bobot[$kodeLevel] ?? 0;

                if (!isset($skorPerLaporan[$idLaporan])) {
                    $skorPerLaporan[$idLaporan] = [
                        'skor' => 0,
                        'total_pelapor' => 0,
                        'created_at' => $item->laporan->created_at ?? now() // Get the report creation date
                    ];
                }

                $skorPerLaporan[$idLaporan]['skor'] += $skor;
                $skorPerLaporan[$idLaporan]['total_pelapor']++;
            }

            $data = collect($skorPerLaporan)
                ->map(function ($item, $idLaporan) {
                    $laporan = LaporanKerusakan::with(['fasilitas', 'pelaporLaporan'])->find($idLaporan);
                    if (!$laporan) return null;

                    return [
                        'laporan' => $laporan,
                        'skor' => $item['skor'],
                        'total_pelapor' => $item['total_pelapor'],
                        'created_at' => $item['created_at'] // Include created_at in the final data
                    ];
                })
                ->filter()
                ->values();

            // Search
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = strtolower($request->search);
                $data = $data->filter(function ($item) use ($searchTerm) {
                    return str_contains(strtolower($item['laporan']->fasilitas->nama_fasilitas ?? ''), $searchTerm) ||
                        str_contains(strtolower($item['laporan']->deskripsi ?? ''), $searchTerm);
                })->values();
            }

            // Multi-level sorting
            $sortedData = $data->sortBy([
                // Primary sort: skor (descending)
                fn($a, $b) => $b['skor'] <=> $a['skor'],
                // Secondary sort: total_pelapor (descending) if skor is equal
                fn($a, $b) => $b['total_pelapor'] <=> $a['total_pelapor'],
                // Tertiary sort: created_at (ascending - older reports first) if both skor and total_pelapor are equal
                fn($a, $b) => $a['created_at'] <=> $b['created_at']
            ])->values();

            // Beri ranking dan ambil top 10
            $rankedData = $sortedData->take(10)->map(function ($item, $index) {
                $item['rank'] = $index + 1;
                return $item;
            });

            return view('laporan.trending', [
                'data' => $rankedData,
                'search' => $request->input('search', '')
            ]);
        } else {
            return back();
        }
    }

    public function showPenilaian(string $id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            // Langkah 1: Ambil bobot
            $bobot = [
                'MHS' => 1,
                'TDK' => 2,
                'DSN' => 3,
                'ADM' => 3
            ];

            // Langkah 2: Ambil semua pelapor
            $pelapor = PelaporLaporan::with(['user.level', 'laporan'])
                ->whereHas('laporan', function ($query) {
                    $query->where('id_status', 1);
                })
                ->get();

            // Langkah 3: Hitung skor per laporan
            $skorPerLaporan = [];
            foreach ($pelapor as $item) {
                $idLaporan = $item->id_laporan;
                $kodeLevel = $item->user->level->kode_level ?? 'OTHER';
                $skor = $bobot[$kodeLevel] ?? 0;

                if (!isset($skorPerLaporan[$idLaporan])) {
                    $skorPerLaporan[$idLaporan] = [
                        'skor' => 0,
                        'total_pelapor' => 0,
                        'created_at' => $item->laporan->created_at ?? now()
                    ];
                }

                $skorPerLaporan[$idLaporan]['skor'] += $skor;
                $skorPerLaporan[$idLaporan]['total_pelapor']++;
            }

            // Langkah 4: Konversi ke collection
            $data = collect($skorPerLaporan)
                ->map(function ($item, $idLaporan) {
                    $laporan = LaporanKerusakan::with(['fasilitas', 'pelaporLaporan'])->find($idLaporan);
                    if (!$laporan) return null;

                    return [
                        'laporan' => $laporan,
                        'skor' => $item['skor'],
                        'total_pelapor' => $item['total_pelapor'],
                        'created_at' => $item['created_at']
                    ];
                })
                ->filter()
                ->values();

            // Langkah 5: Sorting sama seperti trending()
            $sortedData = $data->sortBy([
                fn($a, $b) => $b['skor'] <=> $a['skor'],
                fn($a, $b) => $b['total_pelapor'] <=> $a['total_pelapor'],
                fn($a, $b) => $a['created_at'] <=> $b['created_at']
            ])->values();

            // Langkah 6: Beri ranking
            $trendingRanks = [];
            foreach ($sortedData as $index => $item) {
                $trendingRanks[$item['laporan']->id_laporan] = $index + 1;
            }

            // Langkah 7: Ambil laporan yang akan ditampilkan
            $laporan = LaporanKerusakan::with([
                'fasilitas',
                'pelaporLaporan'
            ])->findOrFail($id);

            // Langkah 8: Ambil ranking untuk laporan ini
            $trendingNo = $trendingRanks[$laporan->id_laporan] ?? '-';

            return view('laporan.showPenilaian', compact('laporan', 'trendingNo'));
        } else {
            return back();
        }
    }

    public function simpanPenilaian(Request $request, $id)
    {
        $findLaporan = LaporanKerusakan::find($id);
        $findLaporan->id_status = 2;
        $findLaporan->save();

        $request->validate([
            'tingkat_kerusakan' => 'required|numeric',
            'frekuensi_digunakan' => 'required|numeric',
            'dampak' => 'required|numeric',
            'estimasi_biaya' => 'required|numeric',
            'potensi_bahaya' => 'required|numeric',
        ]);

        $kriteria = KriteriaPenilaian::create([
            'id_laporan' => $id,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'frekuensi_digunakan' => $request->frekuensi_digunakan,
            'dampak' => $request->dampak,
            'estimasi_biaya' => $request->estimasi_biaya,
            'potensi_bahaya' => $request->potensi_bahaya
        ]);

        if ($kriteria) {
            return response()->json([
                'success' => true,
                'messages' => 'Laporan berhasil diberi nilai'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Gagal diberi nilai'
            ]);
        }

        redirect('/');
    }

    public function tugaskanTeknisi($id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $laporan = LaporanKerusakan::with('fasilitas')->findOrFail($id);
            $teknisi = User::where('id_level', '3')->get();

            return view('laporan_prioritas.tugaskan_teknisi', compact('laporan', 'teknisi'));
        } else {
            return back();
        }
    }

    public function formGantiTeknisi($id)
    {
        $laporan = LaporanKerusakan::with('fasilitas')->findOrFail($id);
        $penugasan = PenugasanTeknisi::where('id_laporan', $id)->first();
        $teknisi = User::where('id_level', '3')->get();


        return view('laporan_prioritas.ganti_teknisi', compact('laporan', 'penugasan', 'teknisi'));
    }
    public function gantiTeknisi(Request $request)
    {
        $validated = $request->validate([
            'id_penugasan' => 'required|exists:penugasan_teknisi,id_penugasan',
            'id_laporan'   => 'required|exists:laporan_kerusakan,id_laporan',
            'id_user'      => 'required|exists:users,id_user',
            'tenggat'      => 'required|date|after_or_equal:today',
        ]);

        // Ambil data penugasan lama
        $penugasanLama = PenugasanTeknisi::findOrFail($validated['id_penugasan']);
        $teknisiLama = $penugasanLama->id_user;
        $tenggatLama = Carbon::parse($penugasanLama->tenggat);

        // Default skor_kinerja untuk penugasan lama
        $skorKinerjaLama = null;

        // Cek apakah penggantian teknisi melewati tenggat
        if ($tenggatLama->isPast() && $teknisiLama != $validated['id_user']) {
            // Turunkan credit score teknisi lama
            $credit = CreditScoreTeknisi::firstOrCreate(
                ['id_user' => $teknisiLama],
                ['credit_score' => 100]
            );
            $credit->credit_score = max(0, $credit->credit_score - 10);
            $credit->save();

            $skorKinerjaLama = '-10'; // keterangan penalti
        }

        // Update penugasan lama → tidak selesai & simpan penalti jika ada
        $penugasanLama->update([
            'status_perbaikan' => 'Tidak Selesai',
            'skor_kinerja' => $skorKinerjaLama,
            'updated_at' => now(),
        ]);

        // Buat penugasan baru (tanpa skor_kinerja)
        PenugasanTeknisi::create([
            'id_laporan'   => $validated['id_laporan'],
            'id_user'      => $validated['id_user'],
            'tenggat'      => $validated['tenggat'],
            'status_perbaikan'       => 'Sedang Dikerjakan',
            'skor_kinerja' => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teknisi berhasil diganti.',
        ]);
    }


    public function verifikasiPerbaikan($id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $laporan = LaporanKerusakan::with('penugasan.user')->findOrFail($id);

            return view('laporan_prioritas.verifikasi', compact('laporan'));
        } else {
            return back();
        }
    }
    public function simpanPenugasan(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required',
            'id_user' => 'required',
            'tenggat' => 'required|date|after_or_equal:today',
        ]);

        PenugasanTeknisi::updateOrCreate(
            ['id_laporan' => $request->id_laporan],
            [
                'id_user' => $request->id_user,
                'tenggat' => $request->tenggat,
                'status_perbaikan' => 'Sedang Dikerjakan',
                'tanggal_selesai' => null,
            ]
        );

        LaporanKerusakan::where('id_laporan', $request->id_laporan)
            ->update(['id_status' => 3]); // Dalam Perbaikan

        return response()->json(['success' => true, 'messages' => 'Teknisi berhasil ditugaskan']);
    }


    public function simpanVerifikasi(Request $request)
    {
        $idLaporan = $request->id_laporan;
        $idPenugasan = $request->id_penugasan;

        $laporan = LaporanKerusakan::find($idLaporan);
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'messages' => 'Laporan tidak ditemukan.',
            ]);
        }

        $penugasan = PenugasanTeknisi::find($idPenugasan);
        if (!$penugasan) {
            return response()->json([
                'success' => false,
                'messages' => 'Data penugasan tidak ditemukan.',
            ]);
        }

        if ($request->verifikasi === 'setuju') {
            $laporan->update([
                'id_status' => 4, // Selesai
                'tanggal_selesai' => now(), // Selesai
            ]);


            $penugasan->update([
                'status_perbaikan' => 'Selesai Dikerjakan',
                'komentar_sarpras' => null,
            ]);

            // Ambil data credit score teknisi yang sudah pasti ada
            $credit = CreditScoreTeknisi::where('id_user', $penugasan->id_user)->first();

            if ($credit && $credit->credit_score < 100) {
                $credit->credit_score = min(100, $credit->credit_score + 5);
                $credit->save();


                $penugasan->update([
                    'status_perbaikan' => 'Selesai Dikerjakan',
                    'komentar_sarpras' => null,
                    'skor_kinerja' => '+5',
                ]);
            } else {
                // Sudah maksimal, hanya update status & komentar
                $penugasan->update([
                    'status_perbaikan' => 'Selesai Dikerjakan',
                    'komentar_sarpras' => null,
                    'skor_kinerja' => null,
                ]);
            }

            // Hapus nilai kriteria jika telah selesaidari proses penilaian Waspas
            KriteriaPenilaian::where('id_laporan', $idLaporan)->delete();
        } else {
            $penugasan->update([
                'status_perbaikan' => 'Ditolak',
                'komentar_sarpras' => $request->keterangan,
            ]);
        }

        return response()->json([
            'success' => true,
            'messages' => 'Verifikasi berhasil diproses.',
        ]);
    }

    public function createPelapor()
    {
        if (auth()->user()->id_level == 4 || auth()->user()->id_level == 5 || auth()->user()->id_level == 6) {
            $laporan = PelaporLaporan::with([
                'laporan.fasilitas.ruangan.gedung',
                'laporan.status',
                'user'
            ])->get();
            $gedung = Gedung::all();

            return view('pages.pelapor.create', compact('laporan', 'gedung'));
        } else {
            return back();
        }
    }

    public function storePelapor(Request $request)
    {
        $rules = [
            'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
            'deskripsi' => 'required|string',
        ];

        if ($request->hasFile('foto_kerusakan')) {
            $rules['foto_kerusakan'] = 'required|image|max:2048';
        } else if ($request->input('image')) {
            $rules['image'] = 'required|string';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Foto kerusakan harus diupload (file atau kamera).',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Upload foto jika ada
        $filename = null;
        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/laporan_kerusakan'), $filename);
        } else if ($request->input('image')) {
            $image = $request->input('image');
            list($type, $image_data) = explode(';', $image);
            list(, $image_data) = explode(',', $image_data);
            $imageData = base64_decode($image_data);

            $filename = time() . '_camera.jpg';
            $path = public_path('storage/uploads/laporan_kerusakan/' . $filename);
            file_put_contents($path, $imageData);
        }

        // Simpan data ke database
        LaporanKerusakan::create([
            'id_user' => auth()->user()->id_user,
            'id_fasilitas' => $request->id_fasilitas,
            'deskripsi' => $request->deskripsi,
            'foto_kerusakan' => $filename,
            'tanggal_lapor' => now(),
            'id_status' => 1 // Status default: "Menunggu"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan kerusakan berhasil ditambahkan'
        ]);
    }

    public function editPelapor(string $id)
    {
        if (auth()->user()->id_level == 4 || auth()->user()->id_level == 5 || auth()->user()->id_level == 6) {
            $laporan = LaporanKerusakan::find($id);

            return view('pages.pelapor.edit', ['laporan' => $laporan]);
        } else {
            return back();
        }
    }

    public function updatePelapor(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        $rules = [
            'deskripsi' => 'required|string|max:255',
        ];

        if ($request->hasFile('foto_kerusakan')) {
            $rules['foto_kerusakan'] = 'image|max:2048';
        } elseif ($request->input('image')) {
            $rules['image'] = 'string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $laporan->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto_kerusakan')) {
            if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
                Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
            }

            $file = $request->file('foto_kerusakan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads/laporan_kerusakan', $filename);
            $laporan->foto_kerusakan = $filename;
        } elseif ($request->input('image')) {
            if ($laporan->foto_kerusakan && Storage::exists('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan)) {
                Storage::delete('public/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan);
            }

            $image = $request->input('image');
            list($type, $image_data) = explode(';', $image);
            list(, $image_data) = explode(',', $image_data);
            $imageData = base64_decode($image_data);

            $filename = time() . '_camera.jpg';
            Storage::put('public/uploads/laporan_kerusakan/' . $filename, $imageData);
            $laporan->foto_kerusakan = $filename;
        }

        $laporan->save();

        return response()->json([
            'success' => true,
            'messages' => 'Data berhasil diperbarui'
        ]);
    }

    public function rate(string $id)
    {
        if (auth()->user()->id_level == 4 || auth()->user()->id_level == 5 || auth()->user()->id_level == 6) {
            $laporan = PelaporLaporan::with(['laporan.penugasan.user'])
                ->where('id_laporan', $id)
                ->first();

            return view('pages.pelapor.rating', ['laporan' => $laporan]);
        } else {
            return back();
        }
    }

    public function rating(Request $request, $id)
    {
        $request->validate([
            'rating_pengguna' => 'required|numeric|min:1|max:5',
            'feedback_pengguna' => 'required|string|max:500',
        ]);

        // Ambil id_user yang login
        $userId = auth()->user()->id_user;

        // Cari PelaporLaporan yang sesuai id_laporan dan id_user yang login
        $laporan = PelaporLaporan::where('id_laporan', $id)
            ->where('id_user', $userId)
            ->first();

        // Jika tidak ditemukan, gagal
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum pernah melaporkan atau mendukung laporan ini.',
            ]);
        }

        // Update rating dan feedback
        $laporan->update([
            'rating_pengguna' => $request->rating_pengguna,
            'feedback_pengguna' => $request->feedback_pengguna,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating dan ulasan berhasil disimpan.',
        ]);
    }

    public function detail(string $id)
    {
        if (auth()->user()->id_level == 4 || auth()->user()->id_level == 5 || auth()->user()->id_level == 6) {
            $pelaporLaporan = PelaporLaporan::find($id);

            return view('pages.pelapor.detail', ['pelaporLaporan' => $pelaporLaporan]);
        } else {
            return back();
        }
    }

    public function exportLaporan()
    {
        $now = Carbon::now();
        $tahun = $now->year;
        $bulan = $now->month;

        $laporan = LaporanKerusakan::select(
            'id_laporan',
            'id_fasilitas',
            'deskripsi',
            'foto_kerusakan',
            'tanggal_lapor',
            'tanggal_selesai',
            'id_status'
        )
            ->whereYear('tanggal_lapor', $tahun)
            ->whereMonth('tanggal_lapor', $bulan)
            ->orderBy('id_laporan')
            ->with('fasilitas', 'status')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $bulanTeks = $now->format('F_Y');

        // Sheet 1
        $sheet->setTitle('Data Laporan ' . $bulanTeks);
        $sheet->setCellValue('A1', 'ID Laporan');
        $sheet->setCellValue('B1', 'Fasilitas');
        $sheet->setCellValue('C1', 'Deskripsi');
        $sheet->setCellValue('D1', 'Foto Kerusakan');
        $sheet->setCellValue('E1', 'Tanggal Lapor');
        $sheet->setCellValue('F1', 'Tanggal Selesai');
        $sheet->setCellValue('G1', 'Status');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $baris = 2;
        foreach ($laporan as $value) {
            $sheet->setCellValue('A' . $baris, $value->id_laporan);
            $sheet->setCellValue('B' . $baris, $value->fasilitas->nama_fasilitas);
            $sheet->setCellValue('C' . $baris, $value->deskripsi);
            $sheet->setCellValue('E' . $baris, $value->tanggal_lapor);
            $sheet->setCellValue('F' . $baris, $value->tanggal_selesai ?? '-');
            $sheet->setCellValue('G' . $baris, $value->status->nama_status);

            // Tambahkan gambar ke kolom D
            $fotoPath = public_path('storage/uploads/laporan_kerusakan/' . $value->foto_kerusakan);
            if (file_exists($fotoPath)) {
                $drawing = new Drawing();
                $drawing->setPath($fotoPath);
                $drawing->setCoordinates('D' . $baris);
                $drawing->setHeight(30);
                $drawing->setWorksheet($sheet);
            } else {
                $sheet->setCellValue('D' . $baris, 'Foto tidak ditemukan');
            }

            $sheet->getRowDimension($baris)->setRowHeight(30);
            $baris++;
        }

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $laporanPerGedung = (new HomeController)->getLaporanPerGedung();
        $laporanPerBulan = (new HomeController)->getLaporanPerBulan($tahun);

        //hiddenSheet Data Grafik
        $sheetHidden = new Worksheet($spreadsheet, 'SheetDataHidden');
        $spreadsheet->addSheet($sheetHidden);
        $spreadsheet->setActiveSheetIndexByName('SheetDataHidden');

        //data per gedung
        $sheetHidden->setCellValue('A1', 'Gedung');
        $sheetHidden->setCellValue('B1', 'Jumlah Laporan');
        $rowGedung = 2;
        foreach ($laporanPerGedung as $gedung => $jumlah) {
            $sheetHidden->setCellValue("A$rowGedung", $gedung);
            $sheetHidden->setCellValue("B$rowGedung", $jumlah);
            $rowGedung++;
        }
        $endGedung = $rowGedung - 1;

        //data per bulan
        $sheetHidden->setCellValue('D1', 'Bulan');
        $sheetHidden->setCellValue('E1', 'Jumlah Laporan');
        $rowBulan = 2;
        foreach ($laporanPerBulan as $bulan => $jumlah) {
            $sheetHidden->setCellValue("D$rowBulan", $bulan);
            $sheetHidden->setCellValue("E$rowBulan", $jumlah);
            $rowBulan++;
        }
        $endBulan = $rowBulan - 1;

        // Sembunyikan sheet data
        $sheetHidden->setSheetState(Worksheet::SHEETSTATE_VERYHIDDEN);

        //Sheet 2: Statistik Laporan
        $sheet2 = new Worksheet($spreadsheet, 'Statistik Laporan');
        $spreadsheet->addSheet($sheet2, 1);

        // Grafik per Gedung
        $seriesGedung = new DataSeries(
            DataSeries::TYPE_BARCHART,
            null,
            [0],
            [new DataSeriesValues('String', "'SheetDataHidden'!\$B\$1", null, 1)],
            [new DataSeriesValues('String', "'SheetDataHidden'!\$A\$2:\$A\${$endGedung}", null, $endGedung - 1)],
            [new DataSeriesValues('Number', "'SheetDataHidden'!\$B\$2:\$B\${$endGedung}", null, $endGedung - 1)]
        );
        $plotGedung = new PlotArea(null, [$seriesGedung]);
        $chartGedung = new Chart(
            'chart_gedung',
            new Title('Jumlah Laporan per Gedung'),
            new Legend(Legend::POSITION_RIGHT, null, false),
            $plotGedung
        );
        $chartGedung->setTopLeftPosition('A1');
        $chartGedung->setBottomRightPosition('P20');
        $sheet2->addChart($chartGedung);

        // Grafik per Bulan
        $seriesBulan = new DataSeries(
            DataSeries::TYPE_LINECHART,
            null,
            [0],
            [new DataSeriesValues('String', "'SheetDataHidden'!\$E\$1", null, 1)],
            [new DataSeriesValues('String', "'SheetDataHidden'!\$D\$2:\$D\${$endBulan}", null, $endBulan - 1)],
            [new DataSeriesValues('Number', "'SheetDataHidden'!\$E\$2:\$E\${$endBulan}", null, $endBulan - 1)]
        );
        $plotBulan = new PlotArea(null, [$seriesBulan]);
        $chartBulan = new Chart(
            'chart_bulan',
            new Title('Jumlah Laporan per Bulan'),
            new Legend(Legend::POSITION_BOTTOM, null, false),
            $plotBulan
        );
        $chartBulan->setTopLeftPosition('A22');
        $chartBulan->setBottomRightPosition('P40');
        $sheet2->addChart($chartBulan);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $filename = 'Data_Laporan_Kerusakan_' . $bulanTeks . '.xlsx';

        // Header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
}
