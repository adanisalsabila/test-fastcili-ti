<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PelaporLaporan;

class PelaporNotifikasi extends Notification
{
    use Queueable;

    protected $laporan;

    /**
     * Create a new notification instance.
     */
    public function __construct(PelaporLaporan $laporan)
    {
        $this->laporan = $laporan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'id_laporan' => $this->laporan->id_laporan,

            // 'fasilitas' => $this->laporan->laporan->fasilitas->nama_fasilitas, 
            // 'status' => $this->laporan->laporan->status->nama_status, 
            // 'assigned_by' => auth()->check() ? auth()->user()->name : 'System', 
            // 'link' => route('perbaikan.index'), 

            'fasilitas' => $this->laporan->laporan->fasilitas->nama_fasilitas, // Assuming 'judul' is a field in PenugasanTeknisi
            'status' => $this->laporan->laporan->status->nama_status, // Assuming 'deskripsi' is a field
            'assigned_by' => auth()->check() ? auth()->user()->name : 'System', // Who assigned it
            'link' => url('/detail/' . $this->laporan->id_laporan) // Example: link to the task details

        ];
    }
}
