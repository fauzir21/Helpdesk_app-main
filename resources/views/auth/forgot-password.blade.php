<x-auth-layout>
    <x-slot name="title">Lupa Password - {{ config('app.name') }}</x-slot>

    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center text-center">
                <h3 class="fw-light my-4">Pemulihan Password</h3>
                <div class="small mb-3 text-muted">Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.</div>
            </div>
            <div class="card-body">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="small mb-1" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan alamat email" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small text-decoration-none" href="{{ route('login') }}">Kembali ke Login</a>
                        <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="{{ route('register') }}" class="text-decoration-none">Belum punya akun? Daftar!</a></div>
            </div>
        </div>
    </div>
</x-auth-layout>
