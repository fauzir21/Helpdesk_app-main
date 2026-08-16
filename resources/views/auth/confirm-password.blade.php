<x-auth-layout>
    <x-slot name="title">Konfirmasi Password - {{ config('app.name') }}</x-slot>

    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center text-center">
                <h3 class="fw-light my-4">Konfirmasi Password</h3>
                <div class="small mb-3 text-muted">Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="small mb-1" for="password">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda" />
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                        <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-auth-layout>
