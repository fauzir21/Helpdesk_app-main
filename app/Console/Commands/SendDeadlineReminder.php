<?php

namespace App\Console\Commands;

use App\Models\Pengajuan;
use App\Models\User;
use App\Notifications\DeadlineReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-deadline-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder for submissions that are 1 day before deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $pengajuans = Pengajuan::with(['layanan.timKerja.users'])
            ->whereIn('status_pengajuan', ['MENUNGGU_DIPROSES', 'DIPROSES', 'PERBAIKAN'])
            ->whereDate('deadline', $tomorrow)
            ->get();

        if ($pengajuans->isEmpty()) {
            $this->info('No submissions with deadline tomorrow.');

            return;
        }

        foreach ($pengajuans as $pengajuan) {
            $this->info("Sending reminder for tiket: {$pengajuan->nomor_tiket}");

            // Recipients: Helpdesk users and Team users
            $helpdeskUsers = User::where('tipe', 'helpdesk')->get();
            $teamUsers = $pengajuan->layanan->timKerja->users;

            $recipients = $helpdeskUsers->concat($teamUsers)->unique('id');

            Notification::send($recipients, new DeadlineReminder($pengajuan));
        }

        $this->info('Reminders sent successfully.');
    }
}
