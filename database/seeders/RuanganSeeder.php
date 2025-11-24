<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ruangan::insert([

            ['id_gedung' => 1, 'nama_ruangan' => 'Ruangan Direktur', 'kode_ruangan' => 'AA-01'],
        ]);

        Ruangan::insert([
            // Gedung 1
            ['id_gedung' => 1, 'nama_ruangan' => 'Ruang 1 Kantor Pusat', 'kode_ruangan' => 'AA-02'],
            ['id_gedung' => 1, 'nama_ruangan' => 'Ruang 2 Kantor Pusat', 'kode_ruangan' => 'AA-03'],

            // Gedung 2
            ['id_gedung' => 2, 'nama_ruangan' => 'Ruang 1 Graha Polinema', 'kode_ruangan' => 'GP-01'],
            ['id_gedung' => 2, 'nama_ruangan' => 'Ruang 2 Graha Polinema', 'kode_ruangan' => 'GP-02'],
            ['id_gedung' => 2, 'nama_ruangan' => 'Ruang 3 Graha Polinema', 'kode_ruangan' => 'GP-03'],

            // Gedung 3
            ['id_gedung' => 3, 'nama_ruangan' => 'Ruang 1 Adm Niaga', 'kode_ruangan' => 'AB-01'],
            ['id_gedung' => 3, 'nama_ruangan' => 'Ruang 2 Adm Niaga', 'kode_ruangan' => 'AB-02'],
            ['id_gedung' => 3, 'nama_ruangan' => 'Ruang 3 Adm Niaga', 'kode_ruangan' => 'AB-03'],

            // Gedung 4
            ['id_gedung' => 4, 'nama_ruangan' => 'Ruang 1 Akuntansi', 'kode_ruangan' => 'AD-01'],
            ['id_gedung' => 4, 'nama_ruangan' => 'Ruang 2 Akuntansi', 'kode_ruangan' => 'AD-02'],
            ['id_gedung' => 4, 'nama_ruangan' => 'Ruang 3 Akuntansi', 'kode_ruangan' => 'AD-03'],

            // Gedung 5
            ['id_gedung' => 5, 'nama_ruangan' => 'Ruang 1 AE', 'kode_ruangan' => 'AE-01'],
            ['id_gedung' => 5, 'nama_ruangan' => 'Ruang 2 AE', 'kode_ruangan' => 'AE-02'],
            ['id_gedung' => 5, 'nama_ruangan' => 'Ruang 3 AE', 'kode_ruangan' => 'AE-03'],

            // Gedung 6
            ['id_gedung' => 6, 'nama_ruangan' => 'Ruang 1 AF', 'kode_ruangan' => 'AF-01'],
            ['id_gedung' => 6, 'nama_ruangan' => 'Ruang 2 AF', 'kode_ruangan' => 'AF-02'],
            ['id_gedung' => 6, 'nama_ruangan' => 'Ruang 3 AF', 'kode_ruangan' => 'AF-03'],

            // Gedung 7
            ['id_gedung' => 7, 'nama_ruangan' => 'Ruang 1 Elektro', 'kode_ruangan' => 'AG-01'],
            ['id_gedung' => 7, 'nama_ruangan' => 'Ruang 2 Elektro', 'kode_ruangan' => 'AG-02'],
            ['id_gedung' => 7, 'nama_ruangan' => 'Ruang 3 Elektro', 'kode_ruangan' => 'AG-03'],

            // Gedung 8
            ['id_gedung' => 8, 'nama_ruangan' => 'Ruang 1 AH', 'kode_ruangan' => 'AH-01'],
            ['id_gedung' => 8, 'nama_ruangan' => 'Ruang 2 AH', 'kode_ruangan' => 'AH-02'],
            ['id_gedung' => 8, 'nama_ruangan' => 'Ruang 3 AH', 'kode_ruangan' => 'AH-03'],

            // Gedung 9
            ['id_gedung' => 9, 'nama_ruangan' => 'Ruang 1 AI', 'kode_ruangan' => 'AI-01'],
            ['id_gedung' => 9, 'nama_ruangan' => 'Ruang 2 AI', 'kode_ruangan' => 'AI-02'],
            ['id_gedung' => 9, 'nama_ruangan' => 'Ruang 3 AI', 'kode_ruangan' => 'AI-03'],

            // Gedung 10
            ['id_gedung' => 10, 'nama_ruangan' => 'Ruang 1 AJ', 'kode_ruangan' => 'AJ-01'],
            ['id_gedung' => 10, 'nama_ruangan' => 'Ruang 2 AJ', 'kode_ruangan' => 'AJ-02'],
            ['id_gedung' => 10, 'nama_ruangan' => 'Ruang 3 AJ', 'kode_ruangan' => 'AJ-03'],

            // Gedung 11
            ['id_gedung' => 11, 'nama_ruangan' => 'Ruang 1 AK', 'kode_ruangan' => 'AK-01'],
            ['id_gedung' => 11, 'nama_ruangan' => 'Ruang 2 AK', 'kode_ruangan' => 'AK-02'],
            ['id_gedung' => 11, 'nama_ruangan' => 'Ruang 3 AK', 'kode_ruangan' => 'AK-03'],

            // Gedung 12
            ['id_gedung' => 12, 'nama_ruangan' => 'Ruang 1 AL', 'kode_ruangan' => 'AL-01'],
            ['id_gedung' => 12, 'nama_ruangan' => 'Ruang 2 AL', 'kode_ruangan' => 'AL-02'],
            ['id_gedung' => 12, 'nama_ruangan' => 'Ruang 3 AL', 'kode_ruangan' => 'AL-03'],

            // Gedung 13
            ['id_gedung' => 13, 'nama_ruangan' => 'Ruang 1 AM', 'kode_ruangan' => 'AM-01'],
            ['id_gedung' => 13, 'nama_ruangan' => 'Ruang 2 AM', 'kode_ruangan' => 'AM-02'],
            ['id_gedung' => 13, 'nama_ruangan' => 'Ruang 3 AM', 'kode_ruangan' => 'AM-03'],

            // Gedung 14
            ['id_gedung' => 14, 'nama_ruangan' => 'Ruang 1 AU', 'kode_ruangan' => 'AU-01'],
            ['id_gedung' => 14, 'nama_ruangan' => 'Ruang 2 AU', 'kode_ruangan' => 'AU-02'],
            ['id_gedung' => 14, 'nama_ruangan' => 'Ruang 3 AU', 'kode_ruangan' => 'AU-03'],

            // Gedung 15
            ['id_gedung' => 15, 'nama_ruangan' => 'Ruang 1 AO', 'kode_ruangan' => 'AO-01'],
            ['id_gedung' => 15, 'nama_ruangan' => 'Ruang 2 AO', 'kode_ruangan' => 'AO-02'],
            ['id_gedung' => 15, 'nama_ruangan' => 'Ruang 3 AO', 'kode_ruangan' => 'AO-03'],

            // Gedung 16
            ['id_gedung' => 16, 'nama_ruangan' => 'Ruang 1 AP', 'kode_ruangan' => 'AP-01'],
            ['id_gedung' => 16, 'nama_ruangan' => 'Ruang 2 AP', 'kode_ruangan' => 'AP-02'],
            ['id_gedung' => 16, 'nama_ruangan' => 'Ruang 3 AP', 'kode_ruangan' => 'AP-03'],

            // Gedung 17
            ['id_gedung' => 17, 'nama_ruangan' => 'Ruang 1 AQ', 'kode_ruangan' => 'AQ-01'],
            ['id_gedung' => 17, 'nama_ruangan' => 'Ruang 2 AQ', 'kode_ruangan' => 'AQ-02'],
            ['id_gedung' => 17, 'nama_ruangan' => 'Ruang 3 AQ', 'kode_ruangan' => 'AQ-03'],

            // Gedung 18
            ['id_gedung' => 18, 'nama_ruangan' => 'Ruang 1 AR', 'kode_ruangan' => 'AR-01'],
            ['id_gedung' => 18, 'nama_ruangan' => 'Ruang 2 AR', 'kode_ruangan' => 'AR-02'],
            ['id_gedung' => 18, 'nama_ruangan' => 'Ruang 3 AR', 'kode_ruangan' => 'AR-03'],

            // Gedung 19
            ['id_gedung' => 19, 'nama_ruangan' => 'Ruang 1 AS', 'kode_ruangan' => 'AS-01'],
            ['id_gedung' => 19, 'nama_ruangan' => 'Ruang 2 AS', 'kode_ruangan' => 'AS-02'],
            ['id_gedung' => 19, 'nama_ruangan' => 'Ruang 3 AS', 'kode_ruangan' => 'AS-03'],

            // Gedung 20
            ['id_gedung' => 20, 'nama_ruangan' => 'Ruang 1 AX', 'kode_ruangan' => 'AX-01'],
            ['id_gedung' => 20, 'nama_ruangan' => 'Ruang 2 AX', 'kode_ruangan' => 'AX-02'],
            ['id_gedung' => 20, 'nama_ruangan' => 'Ruang 3 AX', 'kode_ruangan' => 'AX-03'],

            // Gedung 21 
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Jaringan',           'kode_ruangan' => 'TS-01'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab RPL',                'kode_ruangan' => 'TS-02'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Basis Data',         'kode_ruangan' => 'TS-03'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-1A',            'kode_ruangan' => 'TS-04'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-1B',            'kode_ruangan' => 'TS-05'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-2A',            'kode_ruangan' => 'TS-06'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-2B',            'kode_ruangan' => 'TS-07'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-3A',            'kode_ruangan' => 'TS-08'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Kelas TI-3B',            'kode_ruangan' => 'TS-09'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Dosen TI',         'kode_ruangan' => 'TS-10'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Kepala Jurusan',   'kode_ruangan' => 'TS-11'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Rapat TI',         'kode_ruangan' => 'TS-12'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Multimedia',         'kode_ruangan' => 'TS-13'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Lab Robotika',           'kode_ruangan' => 'TS-14'],
            ['id_gedung' => 21, 'nama_ruangan' => 'Ruang Server',           'kode_ruangan' => 'TS-15'],

            // Gedung 22
            ['id_gedung' => 22, 'nama_ruangan' => 'Ruang 1 TM', 'kode_ruangan' => 'TM-01'],
            ['id_gedung' => 22, 'nama_ruangan' => 'Ruang 2 TM', 'kode_ruangan' => 'TM-02'],
            ['id_gedung' => 22, 'nama_ruangan' => 'Ruang 3 TM', 'kode_ruangan' => 'TM-03'],
        ]);
    }
}
