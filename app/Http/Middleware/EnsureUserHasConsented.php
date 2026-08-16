<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserConsent;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasConsented
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Periksa apakah user sudah menyetujui pernyataan hari ini
            $hasConsentedToday = UserConsent::where('user_id', $user->id)
                ->where('consent_date', now()->toDateString())
                ->exists();

            // Simpan status ke session untuk digunakan di View
            session(['needs_consent' => !$hasConsentedToday]);
        }

        return $next($request);
    }
}
