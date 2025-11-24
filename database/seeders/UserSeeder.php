<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        // Admin (1 user)
        $data[] = [
            'nama' => 'Yefta Dwi Prasetya',
            'email' => 'admin@jti.com',
            'akses' => '1',
            'id_level' => 1,
            'password' => Hash::make('password')
        ];

        // Sarpras (3 users)
        $sarpras = ['Farhan Rizky', 'Laras Ayu', 'Bima Aditya'];
        foreach ($sarpras as $i => $name) {
            $data[] = [
                'nama' => $name,
                'email' => Str::slug($name, '.') . '@jti.com',
                'akses' => '1',
                'id_level' => 2,
                'password' => Hash::make('password')
            ];
        }

        // Teknisi (5 users)
        $teknisi = ['Rendi Saputra', 'Galih Prakoso', 'Vino Ardian', 'Satria Wibowo', 'Ilham Ramadhan'];
        foreach ($teknisi as $name) {
            $data[] = [
                'nama' => $name,
                'email' => Str::slug($name, '.') . '@jti.com',
                'akses' => '1',
                'id_level' => 3,
                'password' => Hash::make('password')
            ];
        }

        // Dosen (10 users, termasuk nama spesifik)
        $dosen = [
            'Ulla Delfana Rosiana, ST., MT., Dr.',
            'Dimas Wahyu Wibowo, ST., MT.',
            'Adevian Fairuz P, S.ST, M.Eng',
            'Prof. Dr. Eng. Cahya Rahmad, ST., M.Kom',
            'Renaldi Primaswara Prasetya, S.Kom., M.Kom',
            'Rizki Putri Ramadhani, S.S., M.Pd',
            'Sofyan Noor Arief, S.ST., M.Kom.',
            'Siti Nurhaliza Sari, S.ST., M.Kom.',
            'Budi Santoso Saputra, S.ST., M.Kom.',
            'Arif Nugroho, S.T., M.T.'
        ];
        foreach ($dosen as $name) {
            $data[] = [
                'nama' => $name,
                'email' => Str::slug(Str::before($name, ','), '.') . '@jti.com',
                'akses' => '1',
                'id_level' => 5,
                'password' => Hash::make('password')
            ];
        }

        // Mahasiswa (10 users, termasuk nama spesifik)
        $mahasiswa = [
            'Yefta Octa',
            'Dwi Khairy',
            'Rafi Ody',
            'Annisa Eka',
            'Nabila Azzahra',
            'Fauzan Maulana',
            'Alya Putri',
            'Bagus Pratama',
            'Tiara Amalia',
            'Raka Saputra'
        ];
        foreach ($mahasiswa as $name) {
            $data[] = [
                'nama' => $name,
                'email' => Str::slug($name, '.') . '@jti.com',
                'akses' => '1',
                'id_level' => 4,
                'password' => Hash::make('password')
            ];
        }

        // Tendik (10 users)
        $tendik = [
            'Yuniarti Damanik',
            'Deni Kurniawan',
            'Melati Anggraini',
            'Hendra Saputra',
            'Sulastri',
            'Ahmad Fauzi',
            'Ratna Wulandari',
            'Dian Kartika',
            'Indra Gunawan',
            'Rizka Maharani'
        ];
        foreach ($tendik as $name) {
            $data[] = [
                'nama' => $name,
                'email' => Str::slug($name, '.') . '@jti.com',
                'akses' => '1',
                'id_level' => 6,
                'password' => Hash::make('password')
            ];
        }

        // Insert ke tabel users
        User::insert($data);
    }
}
