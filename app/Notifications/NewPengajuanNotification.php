<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewPengajuanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Pengajuan $pengajuan) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class, 'database'];
    }

    public function toWebPush(object $notifiable, array $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Permohonan Baru')
            ->icon('/assets/img/logo.png')
            ->body("Ada permohonan baru ({$this->pengajuan->nomor_tiket}) yang perlu diproses.")
            ->action('Lihat Permohonan', 'view_pengajuan')
            ->data(['url' => route('permohonan.show', $this->pengajuan->id)]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_tiket' => $this->pengajuan->nomor_tiket,
            'title' => 'Permohonan Baru',
            'message' => "Ada permohonan baru ({$this->pengajuan->nomor_tiket}) yang perlu diproses.",
            'url' => route('permohonan.show', $this->pengajuan->id),
        ];
    }
}
