<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $levels = Level::all();

            return DataTables::of($levels)
                ->addIndexColumn()
                ->addColumn('aksi', function ($levels) {
                    return '
                    <div class="d-flex">
                        <button onclick="modalAction(\'' . url('/level/edit/' . $levels->id_level) . '\')" class="btn btn-sm btn-info mr-2">Edit</button>
                        <form class="form-delete" action="' . url('/level/delete/' . $levels->id_level) . '" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                ';
                })
                ->rawColumns(['aksi'])
                ->toJson();
        }
        return view('level.index');
    }

    public function create()
    {
        if (auth()->user()->id_level == 1) {
            return view('level.create');
        } else {
            return back();
        }
    }

    public function store(Request $request)
{
    $rules = [
        'kode_level' => 'required|string|max:10|unique:level,kode_level',
        'nama_level' => 'required|string|max:25'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi inputan gagal. Silakan periksa kembali!',
            'msgField' => $validator->errors()
        ], 422);
    }

    try {
        Level::create($request->only(['kode_level', 'nama_level']));

        return response()->json([
            'success' => true,
            'message' => 'Data level berhasil ditambahkan'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan data',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function edit(string $id)
    {
        if (auth()->user()->id_level == 1) {
            $level = Level::find($id);
    
            return view('level.edit', ['level' => $level]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'kode_level' => 'required|string|max:10|unique:level,kode_level,' . $id . ',id_level',
            'nama_level' => 'required|string|max:25'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal',
                'msgField' => $validator->errors()
            ], 422);
        }

        $data = Level::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        try {
            $data->update($request->only(['kode_level', 'nama_level']));

            return response()->json([
                'success' => true,
                'message' => 'Data level berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->id_level == 1) {
            if ($request->ajax() || $request->wantsJson()) {
                try {
                    $level = Level::find($id);
                    if ($level) {
                        $level->delete();
                        return response()->json([
                            'success' => true,
                            'message' => 'Data berhasil dihapus'
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Data tidak ditemukan'
                        ], 404);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data gagal dihapus karena masih terdapat user yang menggunakan level ini'
                    ], 422);
                }
            }
            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        } else {
            return back();
        }
    }
}
