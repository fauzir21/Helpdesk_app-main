<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-2">
        @csrf
        @method('put')

        <div class="mb-3">
            <label class="small mb-1" for="update_password_current_password">Password Saat Ini</label>
            <input class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan password lama" />
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row gx-3 mb-3">
            <div class="col-md-6">
                <label class="small mb-1" for="update_password_password">Password Baru</label>
                <input class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="update_password_password" name="password" type="password" autocomplete="new-password" placeholder="Minimal 8 karakter" />
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="small mb-1" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
                <input class="form-control" id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password baru" />
            </div>
        </div>

        <div class="d-flex align-items-center gap-4 mt-4">
            <button type="submit" class="btn btn-primary">Ubah Password</button>

            @if (session('status') === 'password-updated')
                <div class="small text-success animate__animated animate__fadeOut animate__delay-2s">
                    <i data-feather="check-circle" class="me-1"></i> Password berhasil diperbarui.
                </div>
            @endif
        </div>
    </form>
</section>
