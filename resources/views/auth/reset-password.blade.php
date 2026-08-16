<x-auth-layout>
    <x-slot name="title">Atur Ulang Password - {{ config('app.name') }}</x-slot>

    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center text-center">
                <h3 class="fw-light my-4">Atur Ulang Password</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="small mb-1" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="small mb-1" for="password">Password Baru</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan password baru" />
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label class="small mb-1" for="password_confirmation">Konfirmasi Password</label>
                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" />
                    </div>

                    <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                        <button type="submit" class="btn btn-primary w-100">Simpan Password Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-auth-layout>
