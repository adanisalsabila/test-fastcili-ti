<?php

namespace Database\Seeders;

use App\Models\PelaporLaporan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class pelaporLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PelaporLaporan::insert([
            [
                'id_user' => 10,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Sudah coba ganti kabel HDMI tapi tetap tidak muncul tampilan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 11,
                'id_laporan' => 2, // Lemari Arsip pintunya rusak
                'deskripsi_tambahan' => 'Engsel pintu sudah longgar sejak minggu lalu.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 10,
                'id_laporan' => 3, // Printer tidak bisa mencetak
                'deskripsi_tambahan' => 'Sudah diinstal ulang drivernya tapi tetap tidak bisa cetak.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 10,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Papan sudah di lem tapi tetap retak.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 11,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Retakan menyebar dan bisa membahayakan jika jatuh.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 5, // AC mengeluarkan bunyi keras
                'deskripsi_tambahan' => 'Bunyi sangat mengganggu saat perkuliahan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 6, // Printer 3D nozzle tersumbat
                'deskripsi_tambahan' => 'Sudah dibersihkan tapi nozzle tetap mampet.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 7, // Kit Robotik tidak menyala
                'deskripsi_tambahan' => 'Dugaan masalah di kabel powernya.',
                'rating_pengguna' => 5,
                'feedback_pengguna' => 'Terima kasih sudah memperbaiki masalah tersebut.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 8, // Dispenser tidak keluar air panas
                'deskripsi_tambahan' => 'Sudah coba isi ulang, tapi air tetap tidak panas.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Tampilan hanya muncul sebentar lalu hilang.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Sudah coba ganti kabel HDMI tapi tetap tidak muncul tampilan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 1, // Proyektor tidak bisa menyambung
                'deskripsi_tambahan' => 'Sudah coba ganti kabel HDMI tapi tetap tidak muncul tampilan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 2, // Lemari Arsip pintunya rusak
                'deskripsi_tambahan' => 'Pintu lemari sudah tidak bisa ditutup rapat.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 3, // Printer tidak bisa mencetak
                'deskripsi_tambahan' => 'Kertas selalu macet saat proses mencetak.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 4, // Whiteboard retak
                'deskripsi_tambahan' => 'Retakan melebar ke tengah papan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 24,
                'id_laporan' => 5, // AC mengeluarkan bunyi keras
                'deskripsi_tambahan' => 'Bunyi keras terdengar saat dinyalakan.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 6, // Printer 3D nozzle tersumbat
                'deskripsi_tambahan' => 'Hasil cetak menjadi berantakan dan tidak presisi.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 7, // Kit Robotik tidak menyala
                'deskripsi_tambahan' => 'Sudah coba ganti kabel dan baterai, masih mati.',
                'rating_pengguna' => 2,
                'feedback_pengguna' => 'Kit Robotik terkadang masih error.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 8, // Dispenser tidak keluar air panas
                'deskripsi_tambahan' => 'Lampu indikator panas tidak menyala.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 23,
                'id_laporan' => 9, // Monitor tidak menampilkan gambar
                'deskripsi_tambahan' => 'Sudah ganti kabel VGA/HDMI tetap blank.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 24,
                'id_laporan' => 10, // Router tidak memancarkan sinyal
                'deskripsi_tambahan' => 'Lampu indikator Wi-Fi mati total.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 11, // CCTV mati total
                'deskripsi_tambahan' => 'Tidak bisa dipantau dari ruang kontrol.',
                'rating_pengguna' => 4,
                'feedback_pengguna' => 'Terima kasih sudah memperbaiki masalah tersebut.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 21,
                'id_laporan' => 12, // Mic tidak menangkap suara
                'deskripsi_tambahan' => 'Mic menyala tapi tidak mengeluarkan suara.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 13, // AC tidak dingin
                'deskripsi_tambahan' => 'Udara keluar tapi tetap panas.',
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 22,
                'id_laporan' => 14, // Speaker Rusak
                'deskripsi_tambahan' => null,
                'rating_pengguna' => null,
                'feedback_pengguna' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 20,
                'id_laporan' => 15, // Speaker Rusak
                'deskripsi_tambahan' => 'Test',
                'rating_pengguna' => 3,
                'feedback_pengguna' => 'Gooooood',
                'created_at' => Carbon::parse('2024-05-18'),
                'updated_at' => Carbon::parse('2024-05-25'),
            ],
        ]);
    }
}
