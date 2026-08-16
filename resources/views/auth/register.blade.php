<x-auth-layout>
    <x-slot name="title">Buat Akun Baru - {{ config('app.name') }}</x-slot>

    <style>
        /* =====================================================
           REGISTER PAGE
        ===================================================== */

        .register-page {
            min-height: 100vh;

            width: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px 15px;

            position: relative;
        }


        /* =====================================================
           BACKGROUND
        ===================================================== */

        .register-background {
            position: fixed;

            inset: 0;

            z-index: -10;

            background-image:
                url("/assets/img/backgrounds/bg-kota-bogor.png");

            background-size: cover;

            background-position: center center;

            background-repeat: no-repeat;
        }


        .register-overlay {
            position: fixed;

            inset: 0;

            z-index: -9;

            background:
                rgba(255, 255, 255, 0.18);

            backdrop-filter: blur(1px);
        }


        /* =====================================================
           CARD
        ===================================================== */

        .register-card {
            width: min(765px, 100%);

            background:
                rgba(255, 255, 255, 0.94);

            border-radius: 25px;

            padding:
                28px
                62px
                32px;

            border:
                1px solid
                rgba(255, 255, 255, 0.9);

            box-shadow:
                0 20px 55px
                rgba(0, 0, 0, 0.20);

            backdrop-filter: blur(10px);

            position: relative;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .register-header {
            text-align: center;

            margin-bottom: 20px;
        }


        .register-logo {
            width: 58px;

            height: 58px;

            object-fit: contain;

            display: block;

            margin:
                0 auto
                7px;
        }


        .register-title {
            margin: 0;

            font-family:
                "Poppins",
                sans-serif;

            font-size: 24px;

            line-height: 1.2;

            font-weight: 500;

            color: #222;
        }


        .register-subtitle {
            margin:
                3px 0 0;

            font-size: 9px;

            color: #666;

            font-family:
                "Poppins",
                sans-serif;
        }


        /* =====================================================
           FORM
        ===================================================== */

        .register-form {
            width: 100%;
        }


        .register-field {
            margin-bottom: 12px;
        }


        .register-label {
            display: block;

            margin-bottom: 5px;

            font-family:
                "Poppins",
                sans-serif;

            font-size: 11px;

            font-weight: 500;

            color: #222;
        }


        /* =====================================================
           INPUT
        ===================================================== */

        .register-input,
        .register-select {
            width: 100%;

            height: 40px;

            border:
                1px solid
                #8e9298;

            border-radius: 13px;

            background:
                rgba(248, 248, 248, 0.92);

            padding:
                0 17px;

            font-family:
                "Poppins",
                sans-serif;

            font-size: 11px;

            color: #333;

            outline: none;

            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }


        .register-input::placeholder {
            color: #858a96;
        }


        .register-input:focus,
        .register-select:focus {
            border-color:
                #1558e8;

            background:
                #ffffff;

            box-shadow:
                0 0 0 3px
                rgba(21, 88, 232, .08);
        }


        /* =====================================================
           SELECT
        ===================================================== */

        .register-select {
            cursor: pointer;

            appearance: auto;
        }


        /* =====================================================
           EMAIL HINT
        ===================================================== */

        .email-hint {
            margin-top: 4px;

            font-size: 8px;

            color: #777;

            font-family:
                "Poppins",
                sans-serif;
        }


        /* =====================================================
           PASSWORD GRID
        ===================================================== */

        .password-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 28px;

            margin-bottom: 12px;
        }


        /* =====================================================
           CAPTCHA
        ===================================================== */

        .captcha-section {
            margin-top: 3px;

            margin-bottom: 17px;
        }


        .captcha-label {
            display: block;

            margin-bottom: 5px;

            font-size: 11px;

            font-weight: 500;

            color: #222;
        }


        .captcha-row {
            display: grid;

            grid-template-columns:
                216px
                42px
                1fr;

            gap: 10px;

            align-items: center;
        }


        .captcha-image {
            width: 216px;

            height: 72px;

            object-fit: contain;

            background:
                #ffffff;

            border:
                1px solid
                #8d929a;

            border-radius: 9px;

            padding: 3px;
        }


        .captcha-refresh {
            width: 42px;

            height: 42px;

            border:
                1px solid
                #8d929a;

            border-radius: 9px;

            background:
                #ffffff;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            color:
                #1558e8;

            transition:
                all .2s ease;
        }


        .captcha-refresh:hover {
            background:
                #1558e8;

            color:
                #ffffff;

            border-color:
                #1558e8;
        }


        .captcha-refresh svg {
            width: 18px;

            height: 18px;
        }


        /* =====================================================
           SUBMIT
        ===================================================== */

        .register-button {
            width: 100%;

            height: 48px;

            border: 0;

            border-radius: 13px;

            background:
                #1558e8;

            color:
                #ffffff;

            font-family:
                "Poppins",
                sans-serif;

            font-size: 13px;

            font-weight: 600;

            letter-spacing: .2px;

            cursor: pointer;

            transition:
                all .2s ease;
        }


        .register-button:hover {
            background:
                #0f47c7;

            transform:
                translateY(-1px);

            box-shadow:
                0 8px 20px
                rgba(21, 88, 232, .22);
        }


        .register-button:active {
            transform:
                translateY(0);
        }


        /* =====================================================
           BACK LINK
        ===================================================== */

        .register-back {
            margin-top: 19px;

            display: flex;

            align-items: center;

            gap: 7px;

            font-family:
                "Poppins",
                sans-serif;

            font-size: 11px;

            color:
                #1558e8;

            text-decoration:
                none;
        }


        .register-back:hover {
            color:
                #0f47c7;

            text-decoration:
                underline;
        }


        .register-back svg {
            width: 16px;

            height: 16px;
        }


        /* =====================================================
           ERROR
        ===================================================== */

        .register-error {
            margin-top: 4px;

            font-size: 9px;

            color:
                #dc2626;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 700px) {

            .register-card {
                width: 100%;

                padding:
                    28px
                    25px
                    30px;

                border-radius: 20px;
            }


            .password-grid {
                grid-template-columns:
                    1fr;

                gap: 0;
            }


            .captcha-row {
                grid-template-columns:
                    1fr auto;
            }


            .captcha-image {
                width: 100%;
            }


            .captcha-row .captcha-input-wrapper {
                grid-column:
                    1 / -1;

                grid-row: 2;
            }
        }


        @media (max-width: 430px) {

            .register-page {
                padding:
                    15px 10px;
            }


            .register-card {
                padding:
                    25px
                    18px
                    25px;
            }


            .register-title {
                font-size: 21px;
            }


            .captcha-row {
                display: flex;

                flex-wrap: wrap;
            }


            .captcha-image {
                width:
                    calc(100% - 52px);

                height: 65px;
            }


            .captcha-input-wrapper {
                width: 100%;
            }


            .captcha-input-wrapper .register-input {
                width: 100%;
            }
        }
    </style>


    <!-- =====================================================
         REGISTER PAGE
    ====================================================== -->

    <div class="register-page">

        <div class="register-background"></div>

        <div class="register-overlay"></div>


        <div class="register-card">

            <!-- HEADER -->

            <div class="register-header">

                <img
                    src="{{ asset('assets/img/logo_kotabogor.png') }}"
                    alt="Logo Kota Bogor"
                    class="register-logo"
                >

                <h1 class="register-title">
                    Buat Akun Baru
                </h1>

                <p class="register-subtitle">
                    Silahkan lengkapi data diri untuk membuat akun anda
                </p>

            </div>


            <!-- FORM -->

            <form
                method="POST"
                action="{{ route('register') }}"
                class="register-form"
            >

                @csrf


                <!-- NAMA -->

                <div class="register-field">

                    <label
                        for="name"
                        class="register-label"
                    >
                        Nama Lengkap
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Masukan Nama Lengkap Anda"
                        class="register-input @error('name') is-invalid @enderror"
                    >

                    @error('name')
                        <div class="register-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- KATEGORI PENGGUNA -->

                <div class="register-field">

                    <label
                        for="kategori_user"
                        class="register-label"
                    >
                        Kategori Pengguna
                    </label>

                    <select
                        id="kategori_user"
                        name="kategori_user"
                        required
                        class="register-select @error('kategori_user') is-invalid @enderror"
                        onchange="updateEmailPlaceholder(this.value)"
                    >

                        <option
                            value=""
                            disabled
                            {{ old('kategori_user') ? '' : 'selected' }}
                        >
                            Kategori Pengguna
                        </option>

                        <option
                            value="umum"
                            {{ old('kategori_user') == 'umum' ? 'selected' : '' }}
                        >
                            Umum (Email Pribadi)
                        </option>

                        <option
                            value="pemerintah"
                            {{ old('kategori_user') == 'pemerintah' ? 'selected' : '' }}
                        >
                            Pemerintah (Email Dinas .go.id)
                        </option>

                    </select>

                    @error('kategori_user')
                        <div class="register-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- EMAIL -->

                <div class="register-field">

                    <label
                        for="email"
                        class="register-label"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="Masukan Email Anda"
                        class="register-input @error('email') is-invalid @enderror"
                    >

                    <div
                        class="email-hint"
                        id="email-hint"
                    >
                        *Masukan Nama Lengkap Anda
                    </div>

                    @error('email')
                        <div class="register-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- PASSWORD -->

                <div class="password-grid">

                    <div class="register-field">

                        <label
                            for="password"
                            class="register-label"
                        >
                            Kata Sandi
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Buat Kata Sandi"
                            class="register-input @error('password') is-invalid @enderror"
                        >

                        @error('password')
                            <div class="register-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="register-field">

                        <label
                            for="password_confirmation"
                            class="register-label"
                        >
                            Konfirmasi Kata Sandi
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Konfirmasi Kata Sandi"
                            class="register-input"
                        >

                    </div>

                </div>


                <!-- CAPTCHA -->

                <div class="captcha-section">

                    <label
                        for="captcha"
                        class="captcha-label"
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
                            onclick="refreshRegisterCaptcha()"
                            aria-label="Refresh Captcha"
                        >

                            <i
                                data-feather="refresh-cw"
                            ></i>

                        </button>


                        <div class="captcha-input-wrapper">

                            <input
                                id="captcha"
                                type="text"
                                name="captcha"
                                required
                                placeholder="Masukan Kode Captcha"
                                class="register-input @error('captcha') is-invalid @enderror"
                            >

                            @error('captcha')
                                <div class="register-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    class="register-button"
                >
                    Daftar Akun
                </button>

            </form>


            <!-- BACK -->

            <a
                href="{{ url('/') }}"
                class="register-back"
            >

                <i
                    data-feather="arrow-left"
                ></i>

                <span>
                    Kembali ke Beranda
                </span>

            </a>

        </div>

    </div>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>

        function updateEmailPlaceholder(value) {

            const emailInput =
                document.getElementById('email');

            const emailHint =
                document.getElementById('email-hint');


            if (!emailInput || !emailHint) {
                return;
            }


            if (value === 'pemerintah') {

                emailInput.placeholder =
                    'Masukan alamat email dinas (.go.id)';

                emailHint.textContent =
                    '*Pengguna Pemerintah wajib menggunakan email dinas berakhiran .go.id';

            } else if (value === 'umum') {

                emailInput.placeholder =
                    'Masukan Email Anda';

                emailHint.textContent =
                    '*Gunakan email pribadi yang masih aktif';

            } else {

                emailInput.placeholder =
                    'Masukan Email Anda';

                emailHint.textContent =
                    '*Masukan Email Anda';

            }

        }


        function refreshRegisterCaptcha() {

            const captcha =
                document.getElementById('captcha-img');

            if (!captcha) {
                return;
            }


            captcha.src =
                "{{ route('captcha13.image') }}?" +
                new Date().getTime();

        }


        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const category =
                    document.getElementById(
                        'kategori_user'
                    );

                if (category) {

                    updateEmailPlaceholder(
                        category.value
                    );

                }


                if (
                    typeof feather !==
                    'undefined'
                ) {

                    feather.replace();

                }

            }
        );

    </script>

</x-auth-layout>