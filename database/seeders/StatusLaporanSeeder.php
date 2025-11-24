<?php

namespace Database\Seeders;

use App\Models\StatusLaporan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusLaporan::insert([
            ['nama_status' => 'Diajukan'],
            ['nama_status' => 'Diproses'],
            ['nama_status' => 'Diperbaiki'],
            ['nama_status' => 'Selesai Diperbaiki'],
        ]);
    }
}
