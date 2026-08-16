<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-2">
        @csrf
        @method('patch')

        <div class="row gx-3 mb-3">
            <div class="col-md-6">
                <label class="small mb-1" for="name">Nama Lengkap</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="small mb-1" for="email">Alamat Email</label>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="small text-danger">
                            {{ __('Email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="btn btn-sm btn-link text-decoration-none p-0">
                                {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium small text-success">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-4 mt-4">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

            @if (session('status') === 'profile-updated')
                <div class="small text-success animate__animated animate__fadeOut animate__delay-2s">
                    <i data-feather="check-circle" class="me-1"></i> Perubahan berhasil disimpan.
                </div>
            @endif
        </div>
    </form>
</section>
