<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Shahid\Captcha\Rules\ValidCaptcha;

class RegisteredUserController extends Controller
{
    use LogsActivity;

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->kategori_user === 'pemerintah' && ! str_ends_with($value, '.go.id')) {
                        $fail('User Pemerintah wajib menggunakan email dinas berakhiran .go.id');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kategori_user' => ['required', 'in:umum,pemerintah'],
            'captcha' => ['required', new ValidCaptcha],
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'kategori_user.required' => 'Kategori pengguna wajib diisi',
            'captcha.required' => 'Captcha wajib diisi',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'kategori_user' => $request->kategori_user,
            'tipe' => 'users', // default role for registered users
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->logActivity('Register', 'Pengguna baru berhasil mendaftar');

        return redirect(route('dashboard', absolute: false));
    }
}
