<?php

namespace App\Http\Controllers;

use App\Models\CreditScoreTeknisi;
use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::when($request->id_level, function ($query, $id_level) {
                return $query->where('id_level', $id_level);
            })
                ->with('level')
                ->get();

            return Datatables::of($users)
                ->addColumn('profil', function ($user) {
                    return $user->foto_profil
                        ? '<img src="' . asset('storage/uploads/foto_profil/' . $user->foto_profil) . '" width="150px" style="border-radius:10px;">'
                        : '<img src="' . asset('default-avatar.jpg') . '" width="150px" style="border-radius:10px;">';
                })
                ->addColumn('akses', function ($user) {
                    return $user->akses
                        ? '<i class="fas fa-user-check text-success"></i>'
                        : '<i class="fas fa-user-times text-danger"></i>';
                })
                ->addColumn('aksi', function ($user) {
                    return '
                    <div class="d-flex">
                        <button onclick="modalAction(\'' . url('/users/edit/' . $user->id_user) . '\')" class="btn btn-sm btn-info mr-2">Edit</button>
                        <form class="form-delete" action="' . url('/users/delete/' . $user->id_user) . '" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        <button onclick="toggleAccess(\'' . url('/users/toggle-access/' . $user->id_user) . '\')" class="btn btn-sm ' . ($user->akses ? 'btn-secondary' : 'btn-success') . ' ml-2">
                            ' . ($user->akses ? 'Nonaktifkan' : 'Aktifkan') . '
                        </button>
                    </div>
                ';
                })
                ->rawColumns(['profil', 'akses', 'aksi'])
                ->toJson();
        }

        $level = Level::all();
        return view('users.index', ['level' => $level]);
    }

    public function create()
    {
        if (auth()->user()->id_level == 1) {
            $level = Level::all();
            return view('users.create', ['level' => $level]);
        } else {
            return back();
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'id_level' => 'required|exists:level,id_level',
            'nama' => 'required|string|max:20',
            'email' => 'required|string|max:50|unique:users,email',
        ];

        $customMessages = [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'msgField' => $validator->errors()
            ], 422);
        }

        User::create([
            'id_level' => $request->id_level,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'created_at' => now()
        ]);

        CreditScoreTeknisi::create([
            'id_user' => User::latest()->first()->id_user
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data!'
        ]);
    }


    public function edit(string $id)
    {
        if (auth()->user()->id_level == 1) {
            $user = User::find($id);
            $level = Level::all();
            return view('users.edit', ['users' => $user, 'level' => $level]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'id_level' => 'required|exists:level,id_level',
            'nama' => 'required|string|max:20',
            'email' => 'required|string|max:50|unique:users,email,' . $id . ',id_user',
        ];

        $messages = [
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'msgField' => $validator->errors()
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan!'
            ], 404);
        }

        try {
            $user->update([
                'id_level' => $request->id_level,
                'nama' => $request->nama,
                'email' => $request->email,
                // tambahkan field lain jika perlu
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(Request $request, $id)
    {
        if (auth()->user()->id_level == 1) {
            $data = User::find($id);
    
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan!'
                ], 404);
            }
    
            try {
                $data->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return back();
        }
    }

    public function toggleAccess($id)
    {
        if (auth()->user()->id_level == 1) {
            try {
                $user = User::findOrFail($id);
    
                // Jika ingin diaktifkan (akses = true)
                if (!$user->akses) {
                    // Cek apakah level_kode ada 
                    if (empty($user->id_level)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'level user belum diatur. Atur level terlebih dahulu!'
                        ], 400);
                    }
                }
    
                // Toggle akses
                $user->akses = !$user->akses;
                $user->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Status akses berhasil diubah.',
                    'new_status' => $user->akses
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return back();
        }
    }


    public function import()
    {
        if (auth()->user()->id_level == 1) {
            return view('users.import');
        } else {
            return back();
        }
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi file gagal',
                'errors' => $validator->errors(),
                'msgField' => ['file_user' => $validator->errors()->get('file_user')]
            ], 422);
        }

        try {
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $errors = [];
            $insert = [];
            $emailInFile = [];

            // Step 1: Validasi semua data dulu
            foreach ($data as $index => $row) {
                if ($index === 0) continue; // Skip header
                $barisExcel = $index + 1;

                $id_level = trim($row[0] ?? '');
                $email = trim($row[1] ?? '');
                $nama = trim($row[2] ?? '');
                $password_plain = trim($row[3] ?? '');

                $rowErrors = [];

                // Validasi kosong
                if ($id_level === '' || $email === '' || $nama === '' || $password_plain === '') {
                    $rowErrors[] = "Kolom tidak boleh kosong.";
                }

                // Validasi Level
                if (!is_numeric($id_level) || !Level::find($id_level)) {
                    $rowErrors[] = "Level ID {$id_level} tidak ditemukan.";
                }

                // Validasi Email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = "Format email '{$email}' tidak valid.";
                }

                // Cek duplikat email di file itu sendiri
                if (in_array($email, $emailInFile)) {
                    $rowErrors[] = "Email '{$email}' duplikat di dalam file.";
                } else {
                    $emailInFile[] = $email;
                }

                // Cek email sudah ada di DB
                if (User::where('email', $email)->exists()) {
                    $rowErrors[] = "Email '{$email}' sudah terdaftar.";
                }

                if ($rowErrors) {
                    $errors[] = "Baris ke-{$barisExcel}: " . implode(' ', $rowErrors);
                    continue;
                }

                // Simpan data yang valid untuk insert nanti
                $insert[] = [
                    'id_level' => $id_level,
                    'email' => $email,
                    'nama' => $nama,
                    'password' => Hash::make($password_plain),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Kalau ada error → batalkan semua
            if (!empty($errors)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat kesalahan pada data Excel. Tidak ada data yang disimpan.',
                    'errors' => $errors,
                    'msgField' => ['file_user' => $errors]
                ], 422);
            }

            // Step 2: Jalankan Transaction
            DB::transaction(function () use ($insert) {
                if (!empty($insert)) {
                    User::insert($insert);
                }
            });

            return response()->json([
                'status' => true,
                'message' => 'Data User berhasil diimport.',
                'count' => count($insert)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat proses import: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function convertErrorsToFields(array $errors)
    {
        $result = [];

        foreach ($errors as $error) {
            // Coba deteksi field dari string error
            if (str_contains($error, 'email')) {
                $result['email'] = [$error];
            } elseif (str_contains($error, 'level') || str_contains($error, 'id_level')) {
                $result['id_level'] = [$error];
            } elseif (str_contains($error, 'nama')) {
                $result['nama'] = [$error];
            } else {
                $result['file_user'] = [$error]; // fallback ke file_user
            }
        }

        return $result;
    }
}
