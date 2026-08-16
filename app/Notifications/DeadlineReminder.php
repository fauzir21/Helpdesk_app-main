<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_pengajuan' => $this->pengajuan->id,
            'nomor_tiket' => $this->pengajuan->nomor_tiket,
            'layanan' => $this->pengajuan->layanan->nama_layanan,
            'deadline' => $this->pengajuan->deadline->translatedFormat('d F Y'),
            'message' => 'Waktunya sisa 1 hari lagi pengerjaannya untuk tiket '.$this->pengajuan->nomor_tiket,
        ];
    }
}
