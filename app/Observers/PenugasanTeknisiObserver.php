<?php

namespace App\Observers;

use App\Models\PenugasanTeknisi;
use App\Models\User; 
use App\Notifications\TeknisiNotifikasi; 

class PenugasanTeknisiObserver
{
    /**
     * Handle the PenugasanTeknisi "created" event.
     */
    public function created(PenugasanTeknisi $penugasanTeknisi): void
    {
        static $notifiedIds = [];

        if (in_array($penugasanTeknisi->id, $notifiedIds)) {
            return;
        }
    
        $assignedUser = User::find($penugasanTeknisi->id_user);
        if ($assignedUser) {
            $assignedUser->notify(new TeknisiNotifikasi($penugasanTeknisi, 'Penugasan Baru ' . 
                                    $penugasanTeknisi->laporan->fasilitas->ruangan->nama_ruangan . 
                                    ', ' . $penugasanTeknisi->laporan->fasilitas->ruangan->gedung->nama_gedung));
            $notifiedIds[] = $penugasanTeknisi->id;
        }
    }

    /**
     * Handle the PenugasanTeknisi "updated" event.
     */
    public function updated(PenugasanTeknisi $penugasanTeknisi): void
    {
    
    }

    /**
     * Handle the PenugasanTeknisi "deleted" event.
     */
    public function deleted(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }

    /**
     * Handle the PenugasanTeknisi "restored" event.
     */
    public function restored(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }

    /**
     * Handle the PenugasanTeknisi "force deleted" event.
     */
    public function forceDeleted(PenugasanTeknisi $penugasanTeknisi): void
    {
        //
    }
}
