<x-landing-layout>
    <x-slot name="title">
        {{ $layanan->nama_layanan }} - Layanan Diskominfo Kota Bogor
    </x-slot>

    <style>
        /* =========================================================
           DETAIL LAYANAN
           ========================================================= */

        .detail-page {
            position: relative;
            min-height: 100vh;
            width: 100%;

            overflow-x: hidden;

            color: #111;

            font-family:
                "Poppins",
                "Montserrat",
                "Segoe UI",
                Arial,
                sans-serif;

            /*
             * BACKGROUND LANGSUNG DI SINI
             * Tidak menggunakan z-index negatif.
             */
            background-image:
                linear-gradient(
                    rgba(255, 255, 255, 0.12),
                    rgba(255, 255, 255, 0.12)
                ),
                url("{{ asset('assets/img/backgrounds/bg-detail.png') }}");

            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;

            background-attachment: fixed;
        }

        /*
         * Lapisan putih tipis supaya isi tetap jelas
         * tanpa menghilangkan gambar background.
         */
        .detail-background::after {
            content: "";
            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    180deg,
                    rgba(255, 255, 255, 0.08) 0%,
                    rgba(255, 255, 255, 0.20) 100%
                );

            pointer-events: none;
        }

        /* =========================================================
           TOP NAVIGATION
           ========================================================= */

        .detail-top {
            width: 100%;
            max-width: 1350px;

            margin: 0 auto;

            padding:
                35px
                45px
                0;

            display: flex;
            align-items: center;
        }

        .detail-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            color: #1764ed;
            text-decoration: none;

            font-size: 16px;
            font-weight: 400;

            transition: all 0.2s ease;
        }

        .detail-back:hover {
            color: #0648c9;
            transform: translateX(-2px);
        }

        .detail-back-icon {
            font-size: 19px;
            line-height: 1;
        }

        /* =========================================================
           LOGO
           ========================================================= */

        .detail-logo {
            position: absolute;

            left: 43px;
            top: 38px;

            width: 54px;
            height: 68px;

            object-fit: contain;

            z-index: 2;
        }

        /* =========================================================
           HERO TITLE
           ========================================================= */

        .detail-hero {
            width: 100%;

            text-align: center;

            padding:
                0
                25px
                25px;
        }

        .detail-title {
            margin: 0;

            color: #1764ed;

            font-size: clamp(
                42px,
                5.2vw,
                68px
            );

            line-height: 1.05;

            letter-spacing: -2.5px;

            font-weight: 800;
        }

        .detail-subtitle {
            margin:
                7px
                auto
                0;

            max-width: 850px;

            color: #111;

            font-size: 16px;

            line-height: 1.45;

            font-weight: 400;
        }

        /* =========================================================
           MAIN CARD
           ========================================================= */

        .detail-card-wrapper {
            width: 100%;

            max-width: 1270px;

            margin:
                5px
                auto
                45px;

            padding:
                0
                20px;
        }

        .detail-card {
            position: relative;

            width: 100%;

            min-height: 545px;

            background: rgba(
                255,
                255,
                255,
                0.96
            );

            border-radius: 22px;

            box-shadow:
                0 25px 45px
                rgba(0, 0, 0, 0.20);

            padding:
                48px
                38px
                32px;

            box-sizing: border-box;
        }

        /* =========================================================
           DESCRIPTION
           ========================================================= */

        .detail-section-title {
            margin: 0 0 18px;

            font-size: 21px;

            line-height: 1.3;

            font-weight: 700;

            color: #111;
        }

        .detail-description {
            margin: 0;

            padding-left: 22px;
            padding-right: 15px;

            font-size: 16px;

            line-height: 1.55;

            color: #161616;

            font-weight: 400;
        }

        /* =========================================================
           DIVIDER
           ========================================================= */

        .detail-divider {
            width: 100%;

            height: 1px;

            margin:
                38px
                7px
                32px;

            background: #777;

            opacity: 0.75;
        }

        /* =========================================================
           REQUIREMENTS
           ========================================================= */

        .detail-requirements-title {
            margin:
                0
                0
                20px;

            font-size: 21px;

            line-height: 1.3;

            font-weight: 700;

            color: #111;
        }

        .detail-requirements {
            width: 100%;

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 43px;

            padding:
                0
                35px;
        }

        /* =========================================================
           REQUIREMENT CARD
           ========================================================= */

        .requirement-card {
            position: relative;

            min-height: 107px;

            background: #fff;

            border:
                1.3px solid #111;

            border-radius: 10px;

            padding:
                17px
                16px;

            box-sizing: border-box;

            display: flex;

            align-items: center;

            gap: 15px;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .requirement-card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 18px
                rgba(0, 0, 0, 0.10);
        }

        /* =========================================================
           REQUIREMENT ICON
           ========================================================= */

        .requirement-icon {
            flex:
                0 0 56px;

            width: 56px;
            height: 56px;

            display: flex;

            align-items: center;

            justify-content: center;
        }

        .requirement-icon i {
            font-size: 43px;
        }

        .requirement-icon.file {
            color: #08bde9;
        }

        .requirement-icon.text {
            color: #f5c742;
        }

        /* =========================================================
           REQUIREMENT CONTENT
           ========================================================= */

        .requirement-content {
            min-width: 0;

            flex: 1;
        }

        .requirement-name {
            margin: 0 0 4px;

            font-size: 16px;

            line-height: 1.25;

            font-weight: 700;

            color: #111;
        }

        .requirement-required {
            display: block;

            margin-bottom: 7px;

            font-size: 15px;

            line-height: 1;

            color: #ff1f1f;

            font-weight: 400;
        }

        .requirement-required.optional {
            color: #777;
        }

        /* =========================================================
           REQUIREMENT META
           ========================================================= */

        .requirement-meta {
            display: flex;

            align-items: center;

            flex-wrap: wrap;

            gap: 20px;

            color: #111;

            font-size: 12px;

            line-height: 1.2;
        }

        .requirement-meta-item {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            white-space: nowrap;
        }

        .requirement-meta-item i {
            font-size: 13px;
        }

        /* =========================================================
           EMPTY REQUIREMENTS
           ========================================================= */

        .detail-empty {
            grid-column: 1 / -1;

            min-height: 100px;

            display: flex;

            align-items: center;

            justify-content: center;

            text-align: center;

            border:
                1px solid #aaa;

            border-radius: 10px;

            color: #777;

            font-size: 14px;

            background: #fff;
        }

        /* =========================================================
           ACTION BUTTON
           ========================================================= */

        .detail-action {
            position: absolute;

            right: 67px;

            bottom: 33px;
        }

        .detail-action-button {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            min-width: 163px;

            height: 29px;

            padding:
                0
                14px;

            border: 0;

            border-radius: 13px;

            background: #1764ed;

            color: #fff;

            text-decoration: none;

            font-size: 12px;

            font-weight: 400;

            cursor: pointer;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .detail-action-button:hover {
            background: #0648c9;

            color: #fff;

            transform: translateY(-1px);
        }

        .detail-action-button i {
            font-size: 12px;
        }

        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1100px) {

            .detail-logo {
                left: 25px;
                top: 25px;

                width: 48px;
                height: 60px;
            }

            .detail-top {
                padding-left: 90px;
                padding-right: 30px;
            }

            .detail-card-wrapper {
                max-width: 1000px;
            }

            .detail-requirements {
                gap: 20px;
                padding: 0 15px;
            }

            .detail-card {
                padding-left: 30px;
                padding-right: 30px;
            }

            .detail-action {
                right: 40px;
            }
        }

        @media (max-width: 850px) {

            .detail-top {
                padding:
                    25px
                    25px
                    0;
            }

            .detail-logo {
                position: relative;

                left: auto;
                top: auto;

                width: 45px;
                height: 55px;

                margin-right: 10px;
            }

            .detail-hero {
                padding-top: 20px;
            }

            .detail-title {
                font-size: 45px;

                letter-spacing: -1.5px;
            }

            .detail-subtitle {
                font-size: 14px;
            }

            .detail-card {
                min-height: auto;

                padding:
                    32px
                    25px
                    95px;
            }

            .detail-requirements {
                grid-template-columns: 1fr;

                padding: 0;
            }

            .requirement-card {
                min-height: 100px;
            }

            .detail-action {
                right: 25px;
                bottom: 28px;
            }
        }

        @media (max-width: 576px) {

            .detail-top {
                padding:
                    20px
                    18px
                    0;
            }

            .detail-back {
                font-size: 14px;
            }

            .detail-hero {
                padding:
                    18px
                    18px
                    20px;
            }

            .detail-title {
                font-size: 36px;
            }

            .detail-subtitle {
                font-size: 13px;
            }

            .detail-card-wrapper {
                padding: 0 12px;
            }

            .detail-card {
                border-radius: 18px;

                padding:
                    27px
                    20px
                    85px;
            }

            .detail-section-title,
            .detail-requirements-title {
                font-size: 19px;
            }

            .detail-description {
                padding:
                    0
                    5px;

                font-size: 14px;
            }

            .detail-divider {
                margin:
                    30px
                    0
                    27px;
            }

            .requirement-card {
                padding: 14px;

                gap: 10px;
            }

            .requirement-icon {
                flex-basis: 48px;

                width: 48px;
                height: 48px;
            }

            .requirement-icon i {
                font-size: 36px;
            }

            .requirement-name {
                font-size: 14px;
            }

            .requirement-required {
                font-size: 13px;
            }

            .requirement-meta {
                gap: 10px;

                font-size: 11px;
            }

            .detail-action {
                left: 20px;
                right: 20px;

                bottom: 23px;
            }

            .detail-action-button {
                width: 100%;
            }
        }
    </style>


    <div class="detail-page">

        {{-- BACKGROUND --}}
        <div class="detail-background"></div>


        {{-- =====================================================
             BACK
             ===================================================== --}}
        <div class="detail-top">

            <a
                href="{{ route('layanan.semua') }}"
                class="detail-back"
            >
                <span class="detail-back-icon">←</span>

                <span>
                    Kembali ke Daftar Layanan
                </span>
            </a>

        </div>


        {{-- =====================================================
             HERO
             ===================================================== --}}
        <section class="detail-hero">

            <h1 class="detail-title">
                {{ $layanan->nama_layanan }}
            </h1>

            <p class="detail-subtitle">
                Temukan Layanan yang Anda butuhkan dan lihat persyaratan yang di perlukan
            </p>

        </section>


        {{-- =====================================================
             MAIN CARD
             ===================================================== --}}
        <div class="detail-card-wrapper">

            <div class="detail-card">

                {{-- =================================================
                     DESKRIPSI
                     ================================================= --}}
                <section>

                    <h2 class="detail-section-title">
                        Deskripsi layanan
                    </h2>

                    <p class="detail-description">
                        {{ $layanan->deskripsi }}
                    </p>

                </section>


                {{-- =================================================
                     DIVIDER
                     ================================================= --}}
                <div class="detail-divider"></div>


                {{-- =================================================
                     PERSYARATAN
                     ================================================= --}}
                <section>

                    <h2 class="detail-requirements-title">
                        Persyaratan Berkas
                    </h2>


                    <div class="detail-requirements">

                        @forelse($layanan->persyaratan as $p)

                            @php
                                $tipe = strtolower((string) $p->tipe);

                                $isFile = $tipe === 'file';

                                $formatLabel = $isFile
                                    ? 'File'
                                    : 'Teks';

                                $maxLabel = $isFile
                                    ? '2 MB'
                                    : '500 Char';
                            @endphp


                            <div class="requirement-card">

                                {{-- ICON --}}
                                <div
                                    class="requirement-icon {{ $isFile ? 'file' : 'text' }}"
                                >

                                    @if($isFile)

                                        <i class="fa-solid fa-file-arrow-up"></i>

                                    @else

                                        <i class="fa-solid fa-file-pen"></i>

                                    @endif

                                </div>


                                {{-- CONTENT --}}
                                <div class="requirement-content">

                                    <h3 class="requirement-name">
                                        {{ $p->nama_persyaratan }}
                                    </h3>


                                    @if($p->wajib)

                                        <span class="requirement-required">
                                            Wajib
                                        </span>

                                    @else

                                        <span class="requirement-required optional">
                                            Opsional
                                        </span>

                                    @endif


                                    <div class="requirement-meta">

                                        <span class="requirement-meta-item">

                                            <i class="fa-regular fa-file-lines"></i>

                                            <span>
                                                Format : {{ $formatLabel }}
                                            </span>

                                        </span>


                                        <span class="requirement-meta-item">

                                            <i class="fa-regular fa-file"></i>

                                            <span>
                                                Maks : {{ $maxLabel }}
                                            </span>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="detail-empty">

                                Belum ada persyaratan khusus untuk layanan ini.

                            </div>

                        @endforelse

                    </div>

                </section>


                {{-- =================================================
                     ACTION
                     ================================================= --}}
                <div class="detail-action">

                    @auth

                        <a
                            href="{{ route('permohonan.create', ['layanan' => $layanan->slug]) }}"
                            class="detail-action-button"
                        >
                            Ajukan Permohonan

                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="detail-action-button"
                        >
                            Ajukan Permohonan

                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </div>

</x-landing-layout>