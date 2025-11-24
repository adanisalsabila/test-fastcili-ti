<?php

namespace App\Http\Controllers;

use App\Models\CreditScoreTeknisi;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PelaporLaporan;
use App\Models\PenugasanTeknisi;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PerbaikanController extends Controller
{
    public function index()
    {
        // Status perbaikan yang ingin ditampilkan (tidak termasuk 'Selesai Dikerjakan')
        $status = ['Sedang Dikerjakan', 'Selesai'];
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            // Admin & Sarpras melihat semua penugasan yang belum selesai
            $laporan = PenugasanTeknisi::with(['user', 'laporan.fasilitas'])
                ->whereIn('status_perbaikan', $status)
                ->latest()
                ->get();
        } else {
            // Teknisi melihat penugasannya sendiri yang belum selesai
            $laporan = PenugasanTeknisi::with(['user', 'laporan.fasilitas'])
                ->where('id_user', auth()->user()->id_user)
                ->whereIn('status_perbaikan', $status)
                ->latest()
                ->get();
        }

        return view('perbaikan.index', ['laporan_kerusakan' => $laporan]);
    }

    public function riwayat_perbaikan(Request $request)
    {
        if ($request->ajax()) {
            $query = PenugasanTeknisi::with(['laporan.fasilitas.ruangan.gedung']);

            // Filter berdasarkan level user
            if (auth()->user()->id_level == 3) {
                // Teknisi hanya melihat riwayat miliknya
                $query->where('id_user', auth()->user()->id_user);
            }

            // Hanya tampilkan yang status_perbaikan 'Tidak Selesai' atau laporan sudah selesai diverifikasi (id_status = 4)
            $query->where(function ($q) {
                $q->where('status_perbaikan', 'Tidak Selesai')
                    ->orWhereHas('laporan', function ($subQuery) {
                        $subQuery->where('id_status', 4);
                    });
            });

            if ($request->has('bulan') && $request->bulan != '') {
                $query->whereMonth('tanggal_selesai', $request->bulan);
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status_perbaikan', $request->status);
            }


            $data = $query->latest()->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_fasilitas', function ($row) {
                    return $row->laporan->fasilitas->nama_fasilitas ?? '-';
                })
                ->addColumn('nama_ruangan', function ($row) {
                    return $row->laporan->fasilitas->ruangan->nama_ruangan ?? '-';
                })
                ->addColumn('nama_gedung', function ($row) {
                    return $row->laporan->fasilitas->ruangan->gedung->nama_gedung ?? '-';
                })
                ->addColumn('tanggal_selesai', function ($row) {
                    return $row->tanggal_selesai
                        ? \Carbon\Carbon::parse($row->tanggal_selesai)->translatedFormat('l, d F Y')
                        : '-';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status_perbaikan === 'Selesai Dikerjakan') {
                        return '<span class="badge badge-success">Selesai Diperbaiki</span>';
                    } elseif ($row->status_perbaikan === 'Tidak Selesai') {
                        return '<span class="badge badge-danger">Tidak Selesai</span>';
                    } else {
                        return '<span class="badge badge-secondary">-</span>';
                    }
                })
                ->addColumn('skor_kinerja', function ($row) {
                    return $row->skor_kinerja ?? '-';
                })
                ->addColumn('aksi', function ($row) {
                    $url = url('/perbaikan/detail/' . $row->id_penugasan);
                    return '<button onclick="modalAction(\'' . $url . '\')" class="btn btn-info btn-sm btn-round">
                            <i class="nc-icon nc-zoom-split"></i> Detail
                        </button>';
                })
                ->rawColumns(['status', 'aksi'])
                ->toJson();
        }

        return view('perbaikan.riwayat_perbaikan');
    }

    public function skor_teknisi()
    {
        $teknisi = CreditScoreTeknisi::with('user')
            ->orderBy('credit_score', 'asc')
            ->get();

        return view('pages/teknisi.modal_skor', compact('teknisi'));
    }


    public function edit($id)
    {
        if (auth()->user()->id_level == 3) {
            $perbaikan = PenugasanTeknisi::with('laporan.fasilitas')->findOrFail($id);

            return view('perbaikan.edit', ['laporan_kerusakan' => $perbaikan]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $penugasan = PenugasanTeknisi::where('id_penugasan', $id)->first();

        if (!$penugasan) {
            return response()->json([
                'success' => false,
                'messages' => 'Penugasan tidak ditemukan.',
            ]);
        }

        if (now()->greaterThan($penugasan->tenggat)) {
            return response()->json([
                'success' => false,
                'messages' => 'Tenggat waktu telah lewat. Anda tidak dapat mengunggah dokumentasi.',
            ]);
        }

        $perbaikan = PenugasanTeknisi::findOrFail($id);

        $rules = [
            'catatan_teknisi' => 'nullable|string|max:500',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $perbaikan->status_perbaikan = 'Selesai';
            $perbaikan->catatan_teknisi = $request->catatan_teknisi;
            $perbaikan->tanggal_selesai = now();

            if ($request->hasFile('dokumentasi')) {
                // Hapus file lama
                if ($perbaikan->dokumentasi && Storage::exists('public/uploads/dokumentasi/' . $perbaikan->dokumentasi)) {
                    Storage::delete('public/uploads/dokumentasi/' . $perbaikan->dokumentasi);
                }

                $file = $request->file('dokumentasi');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploads/dokumentasi', $filename);
                $perbaikan->dokumentasi = $filename;
            }

            $perbaikan->save();

            return response()->json([
                'success' => true,
                'messages' => 'Perbaikan berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'messages' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }

    public function detail($id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2 || auth()->user()->id_level == 3) {
            $perbaikan = PenugasanTeknisi::with(['laporan.fasilitas', 'user', 'laporan.pelaporLaporan'])
                ->findOrFail($id);

            $laporan = $perbaikan->laporan;

            // Ambil semua PelaporLaporan terkait laporan ini
            $pelaporLaporan = $laporan->pelaporLaporan;

            // Hitung jumlah pendukung (total PelaporLaporan)
            $jumlahPendukung = $pelaporLaporan->count();

            // Ambil rating yang tidak null
            $ratings = $pelaporLaporan->whereNotNull('rating_pengguna')->pluck('rating_pengguna');

            // Hitung jumlah yang sudah memberikan rating
            $jumlahRatingDiberikan = $ratings->count();

            // Hitung total nilai rating
            $totalRating = $ratings->sum();

            // Hitung rating akhir skala 0-5
            $totalPossibleScore = 5 * $jumlahPendukung;
            $ratingAkhir = $totalPossibleScore > 0 ? ($totalRating / $totalPossibleScore) * 5 : 0;

            // Ambil maksimal 5 feedback_pengguna pertama yang tidak null
            $ulasan = $pelaporLaporan
                ->whereNotNull('feedback_pengguna')
                ->pluck('feedback_pengguna')
                ->take(10);

            // Kirim ke view
            return view('perbaikan.detail', compact(
                'perbaikan',
                'jumlahPendukung',
                'jumlahRatingDiberikan',
                'ratingAkhir',
                'ulasan'
            ));
        } else {
            return back();
        }
    }
}
