<x-auth-layout>
    <x-slot name="title">Masuk - {{ config('app.name') }}</x-slot>

    <div class="col-lg-5">
        <!-- Basic login form-->
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center text-center">
                <h3 class="fw-light my-4">Masuk ke Akun</h3>
                <div class="small"><a href="{{ url('/') }}" class="text-primary fw-bold text-decoration-none"><i data-feather="arrow-left" class="me-1"></i> Kembali ke Beranda</a></div>
            </div>
            <div class="card-body">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="small mb-1" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan alamat email" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="small mb-1" for="password">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CAPTCHA -->
                    <div class="mb-3">
                        <label class="small mb-1" for="captcha">Captcha</label>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="{{ route('captcha13.image') }}" alt="captcha" class="rounded border" id="captcha-img">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('captcha-img').src = '{{ route('captcha13.image') }}?' + Math.random();">
                                <i data-feather="refresh-cw"></i>
                            </button>
                        </div>
                        <input class="form-control @error('captcha') is-invalid @enderror" id="captcha" type="text" name="captcha" required placeholder="Masukkan kode captcha" />
                        @error('captcha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" id="remember_me" type="checkbox" name="remember" />
                            <label class="form-check-label" for="remember_me">Ingat Saya</label>
                        </div>
                    </div>

                    <!-- Form Group (login box)-->
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        @if (Route::has('password.request'))
                            <a class="small text-decoration-none" href="{{ route('password.request') }}">Lupa Password?</a>
                        @endif
                        <button type="submit" class="btn btn-primary px-4">Masuk</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? Daftar sekarang!</a></div>
            </div>
        </div>
    </div>
</x-auth-layout>
