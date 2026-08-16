<section class="space-y-6">
    <div class="small text-muted mb-3">
        {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
    </div>

    <button class="btn btn-danger-soft text-danger" type="button" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true" @if($errors->userDeletion->isNotEmpty()) data-bs-show="true" @endif>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-header">
                        <h5 class="modal-header-title" id="confirmUserDeletionLabel">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-4">
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>

                        <div class="mb-3">
                            <label class="small mb-1 sr-only" for="password">Password</label>
                            <input class="form-control @error('password', 'userDeletion') is-invalid @enderror" id="password" name="password" type="password" placeholder="Masukkan password Anda untuk konfirmasi" />
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger" type="submit">{{ __('Hapus Akun Permanen') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
    @push('after-scripts')
    <script>
        $(document).ready(function() {
            var myModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));
            myModal.show();
        });
    </script>
    @endpush
    @endif
</section>
