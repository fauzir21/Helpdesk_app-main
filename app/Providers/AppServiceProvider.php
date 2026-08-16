<?php

namespace App\Providers;

use App\Models\Layanan;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        // Kustomisasi Email Verifikasi (Formal)
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - '.config('app.name'))
                ->greeting('Halo, '.$notifiable->name.'!')
                ->line('Terima kasih telah mendaftar di '.config('app.name').'.')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda agar dapat mulai menggunakan layanan kami.')
                ->action('Verifikasi Alamat Email', $url)
                ->line('Jika Anda tidak merasa melakukan pendaftaran ini, abaikan saja email ini.')
                ->salutation('Hormat kami,'."\r\n".'Tim IT '.config('app.name'));
        });

        // Kustomisasi Reset Password (Formal)
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Permintaan Atur Ulang Password - '.config('app.name'))
                ->greeting('Halo, '.$notifiable->name.'!')
                ->line('Kami menerima permintaan untuk mengatur ulang password akun Anda.')
                ->line('Klik tombol di bawah ini untuk melanjutkan proses pengaturan ulang password. Tautan ini akan kedaluwarsa dalam 60 menit.')
                ->action('Atur Ulang Password', $url)
                ->line('Jika Anda tidak meminta pengaturan ulang password, tidak ada tindakan lebih lanjut yang diperlukan.')
                ->salutation('Hormat kami,'."\r\n".'Tim IT '.config('app.name'));
        });

        // Share data ke semua view (untuk footer, dsb)
        View::composer('layouts.landing', function ($view) {
            $view->with('footerServices', Layanan::where('status', 'aktif')->take(5)->get());
        });

        View::composer('layouts.includes.adm-sidenav', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $count = 0;

                if ($user->tipe === 'helpdesk') {
                    $count = Pengajuan::where('status_pengajuan', 'MENUNGGU_DIPROSES')->count();
                } elseif ($user->tipe === 'pegawai') {
                    $userTeamIds = $user->timKerjas->pluck('id_tim_kerja')->toArray();
                    $count = Pengajuan::whereIn('status_pengajuan', ['DIPROSES', 'PERBAIKAN'])
                        ->whereHas('layanan', function ($q) use ($userTeamIds) {
                            $q->whereIn('tim_kerja_id', $userTeamIds);
                        })->count();
                }

                $view->with('pending_count', $count);
            }
        });
    }
}
