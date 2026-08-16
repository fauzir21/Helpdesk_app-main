<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConsentController extends Controller
{
    /**
     * Tampilkan halaman pernyataan dan persetujuan.
     */
    public function show(): View|RedirectResponse
    {
        $hasConsentedToday = UserConsent::where('user_id', Auth::id())
            ->where('consent_date', now()->toDateString())
            ->exists();

        if ($hasConsentedToday) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.consent');
    }

    /**
     * Simpan persetujuan user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'agree' => 'required|accepted',
        ], [
            'agree.accepted' => 'Anda harus menyetujui pernyataan untuk melanjutkan.',
        ]);

        UserConsent::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'consent_date' => now()->toDateString(),
            ],
            [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        return redirect()->intended(route('dashboard'));
    }
}
