<?php

namespace App\Observers;

use App\Models\LaporanKerusakan;
use App\Models\PelaporLaporan;
use App\Models\User;
use App\Notifications\SarprasNotifikasi;
use App\Notifications\PelaporNotifikasi;

class PelaporLaporanObserver
{
    public function created(PelaporLaporan $pelapor_laporan): void
    {
        $laporan = $pelapor_laporan->laporan;

        $namaFasilitas = optional($laporan->fasilitas)->nama_fasilitas ?? 'Fasilitas';
        $namaRuangan = optional($laporan->fasilitas->ruangan ?? null)->nama_ruangan ?? 'Ruangan';
        $deskripsi = $pelapor_laporan->deskripsi_tambahan ?: '-';

        $tipe = "Laporan Baru: {$namaFasilitas} di {$namaRuangan}";
        $pesan = "Pesan: {$deskripsi}";
        $link = url('/lapor_kerusakan/trending/' . $pelapor_laporan->id);

        $sarprasUsers = User::where('id_level', 2)->get();

        foreach ($sarprasUsers as $user) {
            $user->notify(new SarprasNotifikasi([
                'tipe' => $tipe,
                'pesan' => $pesan,
                'link' => $link,
            ]));
        }
    }


    /**
     * Handle the LaporanKerusakan "updated" event.
     */
    public function updated(PelaporLaporan $pelaporLaporan): void
    {
        $laporan = $pelaporLaporan->laporan;
    
        if ($laporan && $laporan->isDirty('id_status') && !$laporan->wasRecentlyCreated) {
            $user = $pelaporLaporan->user;
            if ($user) {
                $user->notify(new PelaporNotifikasi($pelaporLaporan));
            }
        }
    }


    /**
     * Handle the LaporanKerusakan "deleted" event.
     */
    public function deleted(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }

    /**
     * Handle the LaporanKerusakan "restored" event.
     */
    public function restored(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }

    /**
     * Handle the LaporanKerusakan "force deleted" event.
     */
    public function forceDeleted(LaporanKerusakan $laporanKerusakan): void
    {
        //
    }
}
