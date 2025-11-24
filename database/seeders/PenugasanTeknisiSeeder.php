<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\PenugasanTeknisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PenugasanTeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenugasanTeknisi::insert([
            // ✅ Kondisi ketika Sudah ditugaskan dan selesai dikerjakan
            [
                'id_laporan' => 7,
                'id_user' => 5,
                'status_perbaikan' => 'Selesai Dikerjakan',
                'tanggal_selesai' =>  $penugasan1 = Carbon::parse('2025-01-10'),
                'tenggat' =>  $penugasan1->copy()->addDays(3),
                'catatan_teknisi' => 'Kit Robotik sudah diperbaiki.',
                'skor_kinerja' => '+5',
                'dokumentasi' => 'kit-rusak.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_laporan' => 11,
                'id_user' => 5,
                'status_perbaikan' => 'Selesai Dikerjakan',
                'tanggal_selesai' => $penugasan2 = Carbon::parse('2025-02-16'),
                'tenggat' => $penugasan2->copy()->addDays(4),
                'catatan_teknisi' => 'CCTV sudah diperbaiki dan terpasang.',
                'skor_kinerja' => '+5',
                'dokumentasi' => 'cctv-rusak.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);


        PenugasanTeknisi::insert([
            // ✅ Kondisi ketika Sudah ditugaskan, belum selesai, dan lewat tenggat
            [
                'id_laporan' => 4,
                'id_user' => 6,
                'status_perbaikan' => 'Sedang Dikerjakan',
                'tanggal_selesai' => null,
                'tenggat' => now()->subDays(2),
                'catatan_teknisi' => null,
                'dokumentasi' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // ✅ Kondisi ketika Sudah ditugaskan, Sudah dikerjakan
            [
                'id_laporan' => 6,
                'id_user' => 6,
                'status_perbaikan' => 'Selesai Dikerjakan',
                'tanggal_selesai' => now()->addDays(2),
                'tenggat' => now()->addDays(7),
                'catatan_teknisi' => 'printer 3D sudah diperbaiki.',
                'dokumentasi' => 'printer3d-rusak.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // ✅ Kondisi ketika Sudah ditugaskan, Belum selesai
            [
                'id_laporan' => 13,
                'id_user' => 7,
                'status_perbaikan' => 'Sedang Dikerjakan',
                'tanggal_selesai' => null,
                'tenggat' => now()->addDays(2),
                'catatan_teknisi' => null,
                'dokumentasi' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_laporan' => 15,
                'id_user' => 7,
                'status_perbaikan' => 'Selesai Dikerjakan',
                'tanggal_selesai' => Carbon::parse('2024-05-25'),
                'tenggat' => Carbon::parse('2024-05-26'),
                'catatan_teknisi' => 'Test',
                'dokumentasi' => null,
                'created_at' => Carbon::parse('2024-05-20'),
                'updated_at' => Carbon::parse('2024-05-25')
            ],
        ]);
    }
}
