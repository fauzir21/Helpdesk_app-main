<x-auth-layout>

    <x-slot name="title">
        Masuk - Layanan Diskominfo Kota Bogor
    </x-slot>


    <div class="login-card">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="login-header">

            <img
                src="{{ asset('assets/img/logo_kotabogor.png') }}"
                alt="Logo Kota Bogor"
                class="login-logo"
            >

            <h1 class="login-title">
                Layanan Diskominfo
                <span>Kota Bogor</span>
            </h1>

            <p class="login-subtitle">
                Silakan masuk untuk mengakses layanan Diskominfo Kota Bogor
            </p>

        </div>


        {{-- =====================================================
             SESSION STATUS
        ====================================================== --}}

        <x-auth-session-status
            class="mb-3"
            :status="session('status')"
        />


        {{-- =====================================================
             LOGIN FORM
        ====================================================== --}}

        <form
            method="POST"
            action="{{ route('login') }}"
            class="login-form"
        >

            @csrf


            {{-- =================================================
                 EMAIL
            ================================================== --}}

            <div class="login-field">

                <label
                    for="email"
                    class="login-label"
                >
                    Email
                </label>

                <div class="input-wrapper">

                    <i
                        data-feather="mail"
                        class="input-icon"
                    ></i>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan Email"
                        class="login-input @error('email') is-invalid @enderror"
                    >

                </div>

                @error('email')

                    <div class="login-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- =================================================
                 PASSWORD
            ================================================== --}}

            <div class="login-field">

                <label
                    for="password"
                    class="login-label"
                >
                    Password
                </label>

                <div class="input-wrapper">

                    <i
                        data-feather="lock"
                        class="input-icon"
                    ></i>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan Password"
                        class="login-input @error('password') is-invalid @enderror"
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Tampilkan password"
                    >
                        <i
                            data-feather="eye"
                            id="passwordIcon"
                        ></i>
                    </button>

                </div>

                @error('password')

                    <div class="login-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- =================================================
                 OPTIONS
            ================================================== --}}

            <div class="login-options">

                <div class="remember-wrapper">

                    <input
                        type="checkbox"
                        id="remember_me"
                        name="remember"
                        class="remember-checkbox"
                    >

                    <label
                        for="remember_me"
                        class="remember-label"
                    >
                        Ingat Saya
                    </label>

                </div>


                @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="forgot-link"
                    >
                        Lupa Password?
                    </a>

                @endif

            </div>


            {{-- =================================================
                 CAPTCHA
            ================================================== --}}

            <div class="captcha-section">

                <label
                    for="captcha"
                    class="login-label"
                >
                    Captcha
                </label>


                <div class="captcha-row">

                    <img
                        src="{{ route('captcha13.image') }}"
                        alt="Captcha"
                        class="captcha-image"
                        id="captcha-img"
                    >


                    <button
                        type="button"
                        class="captcha-refresh"
                        id="captchaRefresh"
                        title="Refresh Captcha"
                    >

                        <i data-feather="refresh-cw"></i>

                    </button>

                </div>


                <input
                    id="captcha"
                    type="text"
                    name="captcha"
                    required
                    placeholder="Masukkan Captcha"
                    class="login-input captcha-input @error('captcha') is-invalid @enderror"
                >


                @error('captcha')

                    <div class="login-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- =================================================
                 LOGIN BUTTON
            ================================================== --}}

            <button
                type="submit"
                class="login-button"
            >
                Masuk
            </button>

        </form>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="login-footer">

            <a
                href="{{ url('/') }}"
            >
                ←&nbsp; Kembali ke Beranda
            </a>


            <div>

                <span class="register-text">
                    Belum Punya Akun?
                </span>

                <a
                    href="{{ route('register') }}"
                    class="register-link"
                >
                    Daftar Sekarang
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                /*
                |--------------------------------------------------------------------------
                | PASSWORD TOGGLE
                |--------------------------------------------------------------------------
                */

                const passwordInput =
                    document.getElementById('password');

                const passwordToggle =
                    document.getElementById('passwordToggle');

                const passwordIcon =
                    document.getElementById('passwordIcon');


                if (passwordToggle) {

                    passwordToggle.addEventListener(
                        'click',
                        function () {

                            const isPassword =
                                passwordInput.type === 'password';


                            passwordInput.type =
                                isPassword
                                    ? 'text'
                                    : 'password';


                            passwordIcon.setAttribute(
                                'data-feather',
                                isPassword
                                    ? 'eye-off'
                                    : 'eye'
                            );


                            feather.replace();

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | CAPTCHA REFRESH
                |--------------------------------------------------------------------------
                */

                const captchaImage =
                    document.getElementById('captcha-img');

                const captchaRefresh =
                    document.getElementById('captchaRefresh');


                if (
                    captchaImage &&
                    captchaRefresh
                ) {

                    captchaRefresh.addEventListener(
                        'click',
                        function () {

                            captchaImage.src =
                                "{{ route('captcha13.image') }}"
                                + '?'
                                + Date.now();

                        }
                    );

                }

            });

        </script>

    @endpush

</x-auth-layout>