<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::insert([
            ['kode_level' => 'ADM', 'nama_level' => 'Admin'],
            ['kode_level' => 'SRP', 'nama_level' => 'Sarana Prasarana'],
            ['kode_level' => 'TKN', 'nama_level' => 'Teknisi'],
            ['kode_level' => 'MHS', 'nama_level' => 'Mahasiswa'],
            ['kode_level' => 'DSN', 'nama_level' => 'Dosen'],
            ['kode_level' => 'TDK', 'nama_level' => 'Tendik'],
        ]);
        
    }
}
