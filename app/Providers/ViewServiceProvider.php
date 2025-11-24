<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PenugasanTeknisi;
use App\Models\LaporanKerusakan;
use App\Models\PelaporLaporan;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.navbars.navs.auth', function ($view) {
            if (auth()->user()->id_level == 3) {
                $user = Auth::user();

                $penugasan = PenugasanTeknisi::where('id_user', $user->id)
                    ->latest('created_at')
                    ->get();
                $view->with('penugasan', $penugasan);

                $unreadNotifications = $user->unreadNotifications;
                $view->with('unreadNotifications', $unreadNotifications);
            } else if (in_array(auth()->user()->id_level, [4, 5, 6])) {
                $user = Auth::user();
                $lpr = PelaporLaporan::where('id_user', $user)->first();
                if ($lpr) {
                    $idLaporan = $lpr->id_laporan;
                
                    $laporan = LaporanKerusakan::where('id_laporan', $idLaporan)
                            ->latest('updated_at')            
                            ->get();
                
                    $view->with([
                        'laporan' => $laporan,
                        'unreadNotifications' => $user->unreadNotifications,
                    ]);
                } else {
                    $view->with([
                        'laporan' => collect(),
                        'unreadNotifications' => $user->unreadNotifications,
                    ]);
                }
            } else if (auth()->user()->id_level == 2) { 
                $user = Auth::user();
                $view->with('unreadNotifications', $user->unreadNotifications);
            }
        });
    }
}
