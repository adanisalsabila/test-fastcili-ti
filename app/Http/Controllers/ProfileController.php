<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.edit');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah ada perubahan data
        $isChanged = false;

        if ($request->nama !== $user->nama || $request->email !== $user->email) {
            $isChanged = true;
            $user->nama = $request->nama;
            $user->email = $request->email;
        }

        if ($request->hasFile('foto_profil')) {
            $isChanged = true;

            // Hapus foto lama
            $oldPhotoPath = public_path('storage/uploads/foto_profil/' . $user->foto_profil);
            if ($user->foto_profil && File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath);
            }

            // Simpan foto baru
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/foto_profil'), $filename);
            $user->foto_profil = $filename;
        }

        if (!$isChanged) {
            return redirect()->route('profile.edit')->with('info', 'Tidak ada perubahan yang disimpan.');
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Kata sandi lama tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('password_status', 'Kata sandi berhasil diperbarui.');
    }
}
