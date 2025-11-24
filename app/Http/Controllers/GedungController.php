<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GedungController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getGedungData($request);
        }

        return view('gedung.index');
    }

    private function getGedungData(Request $request)
    {
        $gedung = Gedung::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $gedung->where(function ($query) use ($search) {
                $query->where('nama_gedung', 'like', '%' . $search . '%')
                    ->orWhere('kode_gedung', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Fixed sorting by newest first
        $gedung->orderBy('created_at', 'desc');

        // Pagination - 12 items per page (4x3)
        return $gedung->paginate(12);
    }

    public function detail(string $id)
    {
        $gedung = Gedung::find($id);

        return view('gedung.detail', ['gedung' => $gedung]);
    }

    public function create()
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            return view('gedung.create');
        } else {
            return back();
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_gedung' => 'required|string|max:10|unique:gedung,kode_gedung',
            'nama_gedung' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:255',
            'foto_gedung' => 'required|image|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'errors' => $validator->errors()
            ], 422);
        }


        // Upload foto jika ada
        $filename = null;
        if ($request->hasFile('foto_gedung')) {
            $file = $request->file('foto_gedung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/foto_gedung'), $filename);
        }

        // Gedung::create($request->only(['kode_gedung', 'nama_gedung', 'deskripsi', 'deskripsi']));

        // Simpan data ke database
        Gedung::create([
            'kode_gedung' => $request->kode_gedung,
            'nama_gedung' => $request->nama_gedung,
            'deskripsi' => $request->deskripsi,
            'foto_gedung' => $filename,
            'created_at' => now()
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Data gedung berhasil ditambahkan'
        ]);
    }

    public function edit(string $id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $gedung = Gedung::find($id);

            return view('gedung.edit', ['gedung' => $gedung]);
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $gedung = Gedung::findOrFail($id);

        // Manual validation
        $validator = Validator::make($request->all(), [
            'nama_gedung' => 'required|string|max:50',
            'deskripsi' => 'required|string|max:255',
            'foto_gedung' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi inputan gagal. Mohon cek kembali inputan Anda!',
                'errors' => $validator->errors()
            ], 422);
        }
        $validated = $validator->validated();

        // Cek apakah ada file baru yang diunggah
        if ($request->hasFile('foto_gedung')) {
            // Hapus file lama jika ada
            if ($gedung->foto_gedung && Storage::exists('public/uploads/foto_gedung/' . $gedung->foto_gedung)) {
                Storage::delete('public/uploads/foto_gedung/' . $gedung->foto_gedung);
            }

            // Simpan file baru
            $file = $request->file('foto_gedung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads/foto_gedung', $filename);

            // Update nama file ke model
            $gedung->foto_gedung = $filename;
        }

        // Update field lain
        $gedung->nama_gedung = $validated['nama_gedung'];
        $gedung->deskripsi = $validated['deskripsi'];
        $gedung->save();

        return response()->json(['success' => true, 'message' => 'Gedung berhasil diperbarui']);
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            $gedung = Gedung::find($id);

            if ($gedung) {
                // Hapus foto jika ada dan file-nya masih ada di storage
                if ($gedung->foto_gedung && Storage::exists('public/uploads/gedung/' . $gedung->foto_gedung)) {
                    Storage::delete('public/uploads/gedung/' . $gedung->foto_gedung);
                }

                // Hapus data gedung
                $gedung->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        } else {
            return back();
        }
    }
}
