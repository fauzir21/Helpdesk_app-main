<x-auth-layout>
    <x-slot name="title">Daftar Akun - {{ config('app.name') }}</x-slot>

    <div class="col-lg-7">
        <!-- Basic registration form-->
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center text-center">
                <h3 class="fw-light my-4">Buat Akun Baru</h3>
                <div class="small"><a href="{{ url('/') }}" class="text-primary fw-bold text-decoration-none"><i data-feather="arrow-left" class="me-1"></i> Kembali ke Beranda</a></div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="small mb-1" for="name">Nama Lengkap</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori User -->
                    <div class="mb-3">
                        <label class="small mb-1" for="kategori_user">Kategori Pengguna</label>
                        <select class="form-select @error('kategori_user') is-invalid @enderror" id="kategori_user" name="kategori_user" required onchange="updateEmailPlaceholder(this.value)">
                            <option value="umum" {{ old('kategori_user') == 'umum' ? 'selected' : '' }}>Umum (Email Pribadi)</option>
                            <option value="pemerintah" {{ old('kategori_user') == 'pemerintah' ? 'selected' : '' }}>Pemerintah (Email Dinas .go.id)</option>
                        </select>
                        @error('kategori_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="small mb-1" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email aktif" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="small text-muted mt-1" id="email-hint">Silakan masukkan email aktif Anda.</div>
                    </div>

                    <script>
                        function updateEmailPlaceholder(val) {
                            const emailInput = document.getElementById('email');
                            const emailHint = document.getElementById('email-hint');
                            if (val === 'pemerintah') {
                                emailInput.placeholder = "Masukkan alamat email dinas (.go.id)";
                                emailHint.textContent = "Pengguna Pemerintah wajib menggunakan email dinas berakhiran .go.id";
                            } else {
                                emailInput.placeholder = "Masukkan alamat email pribadi";
                                emailHint.textContent = "Silakan masukkan email pribadi aktif Anda.";
                            }
                        }
                        // Initialize placeholder on load
                        window.onload = function() {
                            updateEmailPlaceholder(document.getElementById('kategori_user').value);
                        };
                    </script>

                    <div class="row gx-3">
                        <div class="col-md-6">
                            <!-- Password -->
                            <div class="mb-3">
                                <label class="small mb-1" for="password">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="Buat password" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="small mb-1" for="password_confirmation">Konfirmasi Password</label>
                                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                            </div>
                        </div>
                    </div>

                    <!-- CAPTCHA -->
                    <div class="mb-3 mt-3">
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

                    <!-- Form Group (create account submit)-->
                    <button type="submit" class="btn btn-primary w-100 mt-4">Daftar Akun</button>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Masuk di sini!</a></div>
            </div>
        </div>
    </div>
</x-auth-layout>
