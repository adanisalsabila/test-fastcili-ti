<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KriteriaPenilaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KriteriaPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KriteriaPenilaian::insert([
            [
                'id_laporan' => 3, // Printer tidak bisa mencetak
                'tingkat_kerusakan' => 5,
                'frekuensi_digunakan' => 3,
                'dampak' => 5,
                'estimasi_biaya' => 3,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 9, // Monitor tidak menampilkan gambar
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 5,
                'dampak' => 3,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 12, // Mic tidak menangkap suara
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 3,
                'dampak' => 3,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 14, // Mic tidak menangkap suara
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 5,
                'dampak' => 3,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 4,
                'tingkat_kerusakan' => 1,
                'frekuensi_digunakan' => 3,
                'dampak' => 3,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 6,
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 5,
                'dampak' => 5,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_laporan' => 13,
                'tingkat_kerusakan' => 3,
                'frekuensi_digunakan' => 5,
                'dampak' => 5,
                'estimasi_biaya' => 1,
                'potensi_bahaya' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
