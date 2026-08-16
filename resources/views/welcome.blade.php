<x-landing-layout>
    <div class="landing-page">

        <!-- =========================================
             BACKGROUND OVERLAY
        ========================================== -->
        <div class="landing-background"></div>

        <!-- =========================================
             HEADER / BRAND
        ========================================== -->
        <header class="landing-header">
            <a href="{{ url('/') }}" class="landing-brand">
                <img
                    src="{{ asset('assets/img/logo_kotabogor.png') }}"
                    alt="Logo Kota Bogor"
                    class="landing-logo"
                >

                <div class="landing-brand-text">
                    <span>Diskominfo</span>
                    <strong>Kota Bogor</strong>
                </div>
            </a>
        </header>


        <!-- =========================================
             MAIN CONTENT
        ========================================== -->
        <main class="landing-main">

            <!-- =====================================
                 LEFT SIDE
            ====================================== -->
            <section class="landing-left">

                <div class="landing-title-wrapper">
                    <h1 class="landing-title">
                        Layanan Diskominfo
                        <span>Kota Bogor</span>
                    </h1>

                    <p class="landing-description">
                        Akses berbagai layanan Diskominfo Kota Bogor secara mudah
                        dan terpadu. Ajukan layanan, lengkapi persyaratan, dan
                        pantau status permohonan Anda secara online.
                    </p>
                </div>


                <!-- =================================
                     SEARCH
                ================================== -->
                <div class="landing-search-wrapper">

                    <div class="landing-search-box">
                        <input
                            type="text"
                            id="landingServiceSearch"
                            placeholder="Cari Layanan........"
                            autocomplete="off"
                        >

                        <button
                            type="button"
                            id="landingClearSearch"
                            class="search-clear"
                            aria-label="Hapus pencarian"
                        >
                            ×
                        </button>

                        <div class="search-divider"></div>

                        <button
                            type="button"
                            class="search-submit"
                            aria-label="Cari layanan"
                        >
                            <i data-feather="search"></i>
                        </button>
                    </div>

                </div>


                <!-- =================================
                     SERVICE CARDS
                ================================== -->
                <div
                    class="landing-services"
                    id="landingServices"
                >

                    @forelse ($layanan->take(2) as $item)

                        <article
                            class="landing-service-card"
                            data-service-name="{{ strtolower($item->nama_layanan) }}"
                            data-service-description="{{ strtolower($item->deskripsi ?? '') }}"
                        >

                            <div class="service-card-icon">
                                <i data-feather="file-text"></i>
                            </div>

                            <h3>
                                {{ $item->nama_layanan }}
                            </h3>

                            <p>
                                {{ Str::limit($item->deskripsi, 105) }}
                            </p>

                            <a
                                href="{{ route('layanan.detail', $item->slug) }}"
                                class="service-detail-button"
                            >
                                Detail Layanan
                                <i data-feather="arrow-right"></i>
                            </a>

                        </article>

                    @empty

                        <div class="landing-empty-service">
                            <i data-feather="file-x"></i>
                            <p>Belum ada layanan tersedia.</p>
                        </div>

                    @endforelse

                </div>


                <!-- =================================
                     BOTTOM BUTTONS
                ================================== -->
                <div class="landing-action-buttons">

                    <a
                        href="{{ route('lacak') }}"
                        class="landing-action-button"
                    >
                        <i data-feather="arrow-left"></i>
                        <span>Cek Status Permohonan</span>
                    </a>


                    <a
                        href="{{ route('layanan.semua') }}"
                        class="landing-action-button landing-action-primary"
                    >
                        <span>Lihat Semua Layanan</span>
                        <i data-feather="arrow-right"></i>
                    </a>

                </div>

            </section>


            <!-- =====================================
                 RIGHT SIDE — LOGIN
            ====================================== -->
            <section class="landing-login-wrapper">

                <div class="landing-login-card">

                    <!-- Login Header -->
                    <div class="landing-login-header">

                        <div class="login-welcome">
                            Selamat Datang di
                        </div>

                        <div class="login-brand-title">
                            <span>Layanan Diskominfo</span>
                            <strong>Kota Bogor</strong>
                        </div>

                    </div>


                    @if (session('status'))
                        <div class="landing-alert landing-alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                    @if ($errors->any())
                        <div class="landing-alert landing-alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif


                    <!-- =================================
                         LOGIN FORM
                    ================================== -->
                    @if (Route::has('login'))

                        @auth

                            <div class="landing-authenticated">

                                <div class="authenticated-icon">
                                    <i data-feather="check-circle"></i>
                                </div>

                                <h3>Anda sudah masuk</h3>

                                <p>
                                    Selamat datang kembali.
                                </p>

                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="landing-login-button"
                                >
                                    Buka Dashboard
                                    <i data-feather="arrow-right"></i>
                                </a>

                            </div>

                        @else

                            <form
                                method="POST"
                                action="{{ route('login') }}"
                                class="landing-login-form"
                            >

                                @csrf


                                <!-- EMAIL -->
                                <div class="landing-input-group">

                                    <div class="landing-input-icon">
                                        <i data-feather="mail"></i>
                                    </div>

                                    <input
                                        id="landing-email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Email"
                                        required
                                        autofocus
                                        autocomplete="username"
                                    >

                                </div>


                                <!-- PASSWORD -->
                                <div class="landing-input-group">

                                    <div class="landing-input-icon">
                                        <i data-feather="lock"></i>
                                    </div>

                                    <input
                                        id="landing-password"
                                        type="password"
                                        name="password"
                                        placeholder="Password"
                                        required
                                        autocomplete="current-password"
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        id="landingPasswordToggle"
                                        aria-label="Tampilkan password"
                                    >
                                        <i data-feather="eye-off"></i>
                                    </button>

                                </div>


                                <!-- REMEMBER ME -->
                                <div class="landing-remember">

                                    <label class="landing-checkbox-wrapper">

                                        <input
                                            type="checkbox"
                                            name="remember"
                                            id="landing-remember"
                                        >

                                        <span class="landing-checkbox"></span>

                                        <span>Ingat Saya</span>

                                    </label>

                                </div>


                                <!-- CAPTCHA -->
                                <div class="landing-captcha">

                                    <div class="landing-captcha-image-wrapper">

                                        <img
                                            src="{{ route('captcha13.image') }}"
                                            alt="Captcha"
                                            id="landingCaptchaImage"
                                        >

                                    </div>

                                </div>


                                <!-- CAPTCHA INPUT -->
                                <div class="landing-input-group captcha-input">

                                    <input
                                        id="landing-captcha"
                                        type="text"
                                        name="captcha"
                                        placeholder="Masukan Captcha"
                                        required
                                    >

                                </div>


                                <!-- FORGOT PASSWORD -->
                                @if (Route::has('password.request'))

                                    <div class="landing-forgot">

                                        <a href="{{ route('password.request') }}">
                                            Lupa Kata Sandi?
                                        </a>

                                    </div>

                                @endif


                                <!-- LOGIN BUTTON -->
                                <div class="landing-login-submit-wrapper">

                                    <button
                                        type="submit"
                                        class="landing-login-button"
                                    >
                                        Masuk
                                    </button>

                                </div>

                            </form>

                        @endauth

                    @endif


                    <!-- =================================
                         REGISTER FOOTER
                    ================================== -->
                    @guest

                        <div class="landing-register-footer">

                            <span>
                                Belum Punya Akun?
                            </span>

                            @if (Route::has('register'))

                                <a href="{{ route('register') }}">
                                    Daftar Sekarang!
                                </a>

                            @endif

                        </div>

                    @endguest

                </div>

            </section>

        </main>

    </div>


    <!-- =========================================
         LANDING PAGE JAVASCRIPT
    ========================================== -->
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | FEATHER ICON
            |--------------------------------------------------------------------------
            */

            if (typeof feather !== 'undefined') {
                feather.replace();
            }


            /*
            |--------------------------------------------------------------------------
            | PASSWORD TOGGLE
            |--------------------------------------------------------------------------
            */

            const passwordInput =
                document.getElementById('landing-password');

            const passwordToggle =
                document.getElementById('landingPasswordToggle');

            if (passwordInput && passwordToggle) {

                passwordToggle.addEventListener('click', function () {

                    const isPassword =
                        passwordInput.type === 'password';

                    passwordInput.type =
                        isPassword ? 'text' : 'password';

                    this.innerHTML = isPassword
                        ? '<i data-feather="eye"></i>'
                        : '<i data-feather="eye-off"></i>';

                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | SEARCH SERVICE
            |--------------------------------------------------------------------------
            */

            const searchInput =
                document.getElementById('landingServiceSearch');

            const clearSearch =
                document.getElementById('landingClearSearch');

            const serviceCards =
                document.querySelectorAll('.landing-service-card');


            if (searchInput) {

                searchInput.addEventListener('input', function () {

                    const keyword =
                        this.value.toLowerCase().trim();

                    serviceCards.forEach(function (card) {

                        const name =
                            card.dataset.serviceName || '';

                        const description =
                            card.dataset.serviceDescription || '';

                        const match =
                            name.includes(keyword) ||
                            description.includes(keyword);

                        card.style.display =
                            match ? '' : 'none';

                    });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | CLEAR SEARCH
            |--------------------------------------------------------------------------
            */

            if (clearSearch && searchInput) {

                clearSearch.addEventListener('click', function () {

                    searchInput.value = '';

                    serviceCards.forEach(function (card) {
                        card.style.display = '';
                    });

                    searchInput.focus();

                });

            }


        });

    </script>

</x-landing-layout>