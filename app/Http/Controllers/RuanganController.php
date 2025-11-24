<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getRuanganData($request);
        }

        $gedung = Gedung::all();
        $ruangan = Ruangan::all();
        return view('ruangan.index', compact('gedung', 'ruangan'));
    }

    private function getRuanganData(Request $request)
    {
        $ruangan = Ruangan::with('gedung');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $ruangan->where(function ($query) use ($search) {
                $query->where('nama_ruangan', 'like', '%' . $search . '%')
                    ->orWhere('kode_ruangan', 'like', '%' . $search . '%');
            });
        }

        // Filter by gedung
        if ($request->has('id_gedung') && !empty($request->id_gedung)) {
            $ruangan->where('id_gedung', $request->id_gedung);
        }

        // Order by newest first
        $ruangan->orderBy('created_at', 'desc');

        // Pagination - 12 items per page (4x3)
        return $ruangan->paginate(12);
    }

    public function create()
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $gedung = Gedung::all();

            return view('ruangan.create', ['gedung' => $gedung]);
        } else {
            return back();
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $customMessages = [
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan. Silakan gunakan kode yang lain.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'msgField' => $validator->errors()
            ], 422);
        }

        Ruangan::create([
            'id_gedung' => $request->id_gedung,
            'kode_ruangan' => $request->kode_ruangan,
            'nama_ruangan' => $request->nama_ruangan,
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data ruangan!'
        ]);
    }

    public function edit(string $id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $ruangan = Ruangan::find($id);
            $gedung = Gedung::all();

            return view('ruangan.edit', ['ruangan' => $ruangan, 'gedung' => $gedung]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $rules = [
            'id_gedung' => 'required|exists:gedung,id_gedung',
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan,' . $ruangan->id_ruangan . ',id_ruangan',
            'nama_ruangan' => 'required|string|max:50'
        ];

        $customMessages = [
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan. Silakan gunakan kode yang lain.'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ruangan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data ruangan berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $data = Ruangan::find($id);

            if ($data) {
                $data->delete($request->all());
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan!'
                ]);
            }

            redirect('/');
        } else {
            return back();
        }
    }

    public function import()
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            return view('ruangan.import');
        } else {
            return back();
        }
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_ruangan' => ['required', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi file gagal.',
                'errors' => $validator->errors(),
                'msgField' => ['file_ruangan' => $validator->errors()->get('file_ruangan')]
            ], 422);
        }

        try {
            $file = $request->file('file_ruangan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $errors = [];
            $rowsToInsert = [];

            foreach ($data as $index => $row) {
                if ($index === 0) continue; // skip header
                $barisExcel = $index + 1;

                $id_gedung = trim($row[0] ?? '');
                $kode_ruangan = trim($row[1] ?? '');
                $nama_ruangan = trim($row[2] ?? '');

                $rowErrors = [];

                if ($id_gedung === '' || $kode_ruangan === '' || $nama_ruangan === '') {
                    $rowErrors[] = "Semua kolom harus diisi.";
                }

                if (!is_numeric($id_gedung) || !Gedung::find($id_gedung)) {
                    $rowErrors[] = "ID Gedung '{$id_gedung}' tidak ditemukan.";
                }

                if (Ruangan::where('kode_ruangan', $kode_ruangan)->exists()) {
                    $rowErrors[] = "Kode Ruangan '{$kode_ruangan}' sudah ada di database.";
                }

                if ($rowErrors) {
                    $errors[] = "Baris ke-{$barisExcel}: " . implode(' ', $rowErrors);
                    continue;
                }

                $rowsToInsert[] = [
                    'id_gedung' => $id_gedung,
                    'kode_ruangan' => $kode_ruangan,
                    'nama_ruangan' => $nama_ruangan,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            if (!empty($errors)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat kesalahan pada data Excel.',
                    'errors' => $errors,
                    'msgField' => ['file_ruangan' => $errors]
                ], 422);
            }

            // ✅ Simpan semua data hanya jika tidak ada error
            DB::transaction(function () use ($rowsToInsert) {
                Ruangan::insert($rowsToInsert);
            });

            return response()->json([
                'status' => true,
                'message' => "Berhasil mengimpor " . count($rowsToInsert) . " data ruangan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat proses import.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function convertErrorsToFields($errors)
    {
        $result = [];
        foreach ($errors as $error) {
            if (str_contains($error, 'id_ruangan')) {
                $result['id_ruangan'] = [$error];
            } elseif (str_contains($error, 'kode_ruangan')) {
                $result['kode_ruangan'] = [$error];
            } elseif (str_contains($error, 'nama_ruangan')) {
                $result['nama_ruangan'] = [$error];
            } else {
                $result['file_ruangan'] = [$error];
            }
        }
        return $result;
    }
}