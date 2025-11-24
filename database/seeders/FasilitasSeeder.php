<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fasilitas::insert([
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-PROY',  'nama_fasilitas' => 'Proyektor',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-LMRI',  'nama_fasilitas' => 'Lemari Arsip',        'jumlah' => 3, 'created_at' => now()],
            ['id_ruangan' => 1,  'kode_fasilitas' => 'AA-01-PRNT',  'nama_fasilitas' => 'Printer',             'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'AA-02-WHBD',  'nama_fasilitas' => 'Whiteboard',          'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 2,  'kode_fasilitas' => 'AA-02-MNTR',  'nama_fasilitas' => 'Monitor Presentasi',  'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 3,  'kode_fasilitas' => 'AA-03-MNTR',  'nama_fasilitas' => 'Monitor Presentasi',  'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 5,  'kode_fasilitas' => 'GP-01-SNDS',  'nama_fasilitas' => 'Sound System',        'jumlah' => 4, 'created_at' => now()],
            ['id_ruangan' => 62, 'kode_fasilitas' => 'TS-01-RTRM',  'nama_fasilitas' => 'Router Mikrotik',     'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 62, 'kode_fasilitas' => 'TS-01-SWHB',  'nama_fasilitas' => 'Switch Hub',          'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-KRSI',  'nama_fasilitas' => 'Kursi Kuliah',        'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-MJKL',  'nama_fasilitas' => 'Meja Kuliah',         'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 63, 'kode_fasilitas' => 'TS-02-ACAC',  'nama_fasilitas' => 'AC',                  'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-DSPN',  'nama_fasilitas' => 'Dispenser',           'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-ACAC',  'nama_fasilitas' => 'AC',                  'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 71, 'kode_fasilitas' => 'TS-10-LCDT',  'nama_fasilitas' => 'LCD TV',              'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 75, 'kode_fasilitas' => 'TS-14-KTRB',  'nama_fasilitas' => 'Kit Robotik',         'jumlah' => 15, 'created_at' => now()],
            ['id_ruangan' => 75, 'kode_fasilitas' => 'TS-14-PR3D',  'nama_fasilitas' => 'Printer 3D',          'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 76, 'kode_fasilitas' => 'TS-15-RKSR',  'nama_fasilitas' => 'Rak Server',          'jumlah' => 5, 'created_at' => now()],
            ['id_ruangan' => 76, 'kode_fasilitas' => 'TS-15-UPSS',  'nama_fasilitas' => 'UPS',                 'jumlah' => 4, 'created_at' => now()],


            ['id_ruangan' => 10, 'kode_fasilitas' => 'AA-01-KMPT', 'nama_fasilitas' => 'Komputer Dosen',        'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 10, 'kode_fasilitas' => 'AA-02-KMHS', 'nama_fasilitas' => 'Komputer Mahasiswa',    'jumlah' => 15, 'created_at' => now()],
            ['id_ruangan' => 10, 'kode_fasilitas' => 'AA-03-PJKT', 'nama_fasilitas' => 'Proyektor',             'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 11, 'kode_fasilitas' => 'BB-01-WFRT', 'nama_fasilitas' => 'WiFi Router',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 11, 'kode_fasilitas' => 'BB-02-KRSD', 'nama_fasilitas' => 'Kursi Dosen',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 11, 'kode_fasilitas' => 'BB-03-KRMH', 'nama_fasilitas' => 'Kursi Mahasiswa',       'jumlah' => 30, 'created_at' => now()],
            ['id_ruangan' => 12, 'kode_fasilitas' => 'CC-01-ACSP', 'nama_fasilitas' => 'AC Split',              'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 12, 'kode_fasilitas' => 'CC-02-WBTR', 'nama_fasilitas' => 'Whiteboard + Spidol',   'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 13, 'kode_fasilitas' => 'DD-01-MCRO', 'nama_fasilitas' => 'Microphone',            'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 13, 'kode_fasilitas' => 'DD-02-SPKR', 'nama_fasilitas' => 'Speaker Aktif',         'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 14, 'kode_fasilitas' => 'EE-01-LPTR', 'nama_fasilitas' => 'Laptop',                'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 14, 'kode_fasilitas' => 'EE-02-MSRG', 'nama_fasilitas' => 'Meja Rapat',            'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 14, 'kode_fasilitas' => 'EE-03-PTSN', 'nama_fasilitas' => 'Papan Tulis',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 15, 'kode_fasilitas' => 'FF-01-KRHR', 'nama_fasilitas' => 'Kursi Roda',            'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 15, 'kode_fasilitas' => 'FF-02-LEMP', 'nama_fasilitas' => 'Lampu LED',             'jumlah' => 6, 'created_at' => now()],
            ['id_ruangan' => 16, 'kode_fasilitas' => 'GG-01-TLRK', 'nama_fasilitas' => 'Televisi Rakitan',      'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 16, 'kode_fasilitas' => 'GG-02-DSPS', 'nama_fasilitas' => 'Display Panel Siswa',   'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 17, 'kode_fasilitas' => 'HH-01-KLMP', 'nama_fasilitas' => 'Kulkas Mini',           'jumlah' => 1, 'created_at' => now()],
            ['id_ruangan' => 17, 'kode_fasilitas' => 'HH-02-MNTR', 'nama_fasilitas' => 'Monitor Tambahan',      'jumlah' => 2, 'created_at' => now()],
            ['id_ruangan' => 18, 'kode_fasilitas' => 'II-01-CCTV', 'nama_fasilitas' => 'CCTV Indoor',           'jumlah' => 2, 'created_at' => now()],


        ]);
    }
}
