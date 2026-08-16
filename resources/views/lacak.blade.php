<x-landing-layout>

    <style>
        /* =========================================================
           LACAK PERMOHONAN - FULL PAGE
           ========================================================= */

        .lacak-page {
            position: relative;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            background: #ffffff;
            font-family: 'Metropolis', sans-serif;
            color: #111827;
        }

        /* =========================================================
           BACKGROUND HERO
           Bentuk dasar kotak -> dipotong ellipse.
           
           BAGIAN BAWAH ELLIPSE TURUN KE TENGAH.
           ========================================================= */

        .lacak-hero-bg {
            position: absolute;
            z-index: 0;

            top: 0;
            left: -5%;

            width: 110%;
            height: 395px;

            background:
                linear-gradient(
                    135deg,
                    rgba(91, 177, 255, 0.96) 0%,
                    rgba(112, 143, 238, 0.96) 48%,
                    rgba(139, 111, 224, 0.97) 100%
                ),
                url('/assets/img/backgrounds/bg-bogor.png');

            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;

            /*
             * INI YANG MEMBALIK ARAH ELLIPSE.
             *
             * At 50% 0%:
             * bagian tengah turun paling bawah,
             * bagian kiri-kanan naik.
             *
             * Jadi putih masuk ke area biru dari bawah,
             * persis seperti screenshot referensi.
             */
            clip-path: ellipse(
                75% 100%
                at 50% 0%
            );
        }


        /* =========================================================
           DEKORASI TITIK KANAN
           ========================================================= */

        .lacak-dots {
            position: absolute;
            z-index: 1;

            top: 102px;
            right: 48px;

            width: 105px;
            height: 150px;

            opacity: 0.55;

            background-image: radial-gradient(
                circle,
                rgba(255, 255, 255, 0.85) 2px,
                transparent 2.5px
            );

            background-size: 22px 22px;

            pointer-events: none;
        }


        /* =========================================================
           KONTEN UTAMA
           ========================================================= */

        .lacak-content {
            position: relative;
            z-index: 3;

            min-height: 100vh;

            padding-top: 42px;
            padding-bottom: 80px;
        }


        /* =========================================================
           BACK BUTTON
           ========================================================= */

        .lacak-back {
            position: absolute;

            top: 47px;
            left: 100px;

            display: inline-flex;
            align-items: center;

            gap: 12px;

            color: #ffffff;

            text-decoration: none;

            font-size: 15px;
            font-weight: 500;

            transition: all 0.2s ease;
        }

        .lacak-back:hover {
            color: #ffffff;
            transform: translateX(-3px);
        }

        .lacak-back-icon {
            font-size: 28px;
            line-height: 1;
            font-weight: 300;
        }


        /* =========================================================
           HEADER
           ========================================================= */

        .lacak-heading {
            text-align: center;

            color: #ffffff;

            padding-top: 15px;
        }

        .lacak-heading h1 {
            margin: 0;

            font-size: 62px;
            line-height: 1.08;

            font-weight: 700;

            letter-spacing: -2px;

            color: #ffffff;
        }

        .lacak-heading p {
            margin: 18px auto 0;

            max-width: 700px;

            font-size: 20px;
            line-height: 1.45;

            font-weight: 400;

            color: #ffffff;
        }


        /* =========================================================
           CARD
           ========================================================= */

        .lacak-card {
            position: relative;

            width: 735px;
            max-width: calc(100% - 40px);

            margin: 68px auto 0;

            padding: 34px 52px 40px;

            background: #ffffff;

            border: 1px solid rgba(120, 120, 120, 0.75);

            border-radius: 20px;

            box-shadow:
                0 18px 40px rgba(0, 0, 0, 0.18);

            box-sizing: border-box;
        }


        /* =========================================================
           ICON BULAT DI ATAS CARD
           ========================================================= */

        .lacak-card-icon {
            position: absolute;

            top: -49px;
            left: 50%;

            transform: translateX(-50%);

            width: 84px;
            height: 84px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #ffffff;

            border: 6px solid #d0d6e5;

            border-radius: 50%;

            box-shadow:
                0 3px 8px rgba(0, 0, 0, 0.08);

            box-sizing: border-box;
        }

        .lacak-card-icon-inner {
            width: 51px;
            height: 51px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #1994ef;

            color: #ffffff;

            font-size: 25px;

            box-shadow:
                inset 0 0 0 2px rgba(255, 255, 255, 0.35);
        }


        /* =========================================================
           FORM GROUP
           ========================================================= */

        .lacak-form-group {
            width: 100%;
        }

        .lacak-form-title {
            display: flex;
            align-items: center;

            gap: 12px;

            margin-bottom: 20px;

            font-size: 17px;
            font-weight: 600;

            color: #111111;
        }

        .lacak-section-icon {
            width: 31px;
            height: 31px;

            flex: 0 0 31px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #168fea;

            color: #ffffff;

            font-size: 16px;

            box-shadow:
                0 0 0 2px rgba(22, 143, 234, 0.15);
        }


        /* =========================================================
           INPUT
           ========================================================= */

        .lacak-input {
            width: 100%;
            height: 41px;

            padding: 0 16px;

            border: 1px solid #969696;

            border-radius: 10px;

            outline: none;

            background: #ffffff;

            color: #111111;

            font-family: inherit;

            font-size: 13px;

            box-sizing: border-box;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .lacak-input::placeholder {
            color: #a5acbd;
            opacity: 1;
        }

        .lacak-input:focus {
            border-color: #2161ed;

            box-shadow:
                0 0 0 3px rgba(33, 97, 237, 0.10);
        }


        /* =========================================================
           ERROR
           ========================================================= */

        .lacak-error {
            margin-top: 7px;

            color: #dc3545;

            font-size: 12px;
        }


        /* =========================================================
           DIVIDER
           ========================================================= */

        .lacak-divider {
            width: 100%;
            height: 1px;

            margin: 20px 0;

            background: #dddddd;
        }


        /* =========================================================
           CAPTCHA
           ========================================================= */

        .lacak-captcha-row {
            display: flex;
            align-items: center;

            gap: 12px;

            margin-bottom: 15px;
        }

        .lacak-captcha-image {
            width: 168px;
            height: 70px;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            background: #ffffff;

            border: 1px solid #999999;

            border-radius: 10px;

            box-sizing: border-box;
        }

        .lacak-captcha-image img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;
        }

        .lacak-refresh {
            width: 43px;
            height: 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            border: 1px solid #a4a4a4;

            border-radius: 10px;

            background: #ffffff;

            color: #1498d6;

            font-size: 24px;

            cursor: pointer;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .lacak-refresh:hover {
            background: #f1f7ff;

            transform: rotate(180deg);
        }


        /* =========================================================
           SEARCH BUTTON
           ========================================================= */

        .lacak-submit {
            width: 100%;
            height: 50px;

            margin-top: 25px;

            border: none;

            border-radius: 9px;

            background: #1d58e8;

            color: #ffffff;

            font-family: inherit;

            font-size: 20px;
            font-weight: 500;

            cursor: pointer;

            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .lacak-submit:hover {
            background: #1649ca;

            box-shadow:
                0 7px 18px rgba(29, 88, 232, 0.25);
        }

        .lacak-submit:active {
            transform: translateY(1px);
        }


        /* =========================================================
           ALERT
           ========================================================= */

        .lacak-alert {
            width: 735px;
            max-width: calc(100% - 40px);

            margin: 25px auto 0;

            padding: 15px 20px;

            border-radius: 10px;

            background: #fff1f1;

            border: 1px solid #ffcaca;

            color: #c82333;

            box-sizing: border-box;
        }


        /* =========================================================
           HASIL PENCARIAN
           ========================================================= */

        .lacak-result {
            width: 735px;
            max-width: calc(100% - 40px);

            margin: 35px auto 0;

            background: #ffffff;

            border: 1px solid #dddddd;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.10);
        }

        .lacak-result-header {
            padding: 20px 25px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid #eeeeee;
        }

        .lacak-result-header h3 {
            margin: 0;

            font-size: 18px;
            font-weight: 600;

            color: #1559e9;
        }

        .lacak-status {
            padding: 7px 12px;

            border-radius: 8px;

            font-size: 11px;
            font-weight: 600;

            color: #ffffff;
        }

        .lacak-result-body {
            padding: 25px;
        }

        .lacak-result-grid {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 25px;

            margin-bottom: 25px;
        }

        .lacak-result-label {
            margin-bottom: 6px;

            font-size: 11px;
            font-weight: 700;

            color: #8991a4;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }

        .lacak-result-value {
            font-size: 16px;
            font-weight: 600;

            color: #111827;
        }


        /* =========================================================
           TIMELINE
           ========================================================= */

        .lacak-timeline-title {
            margin: 25px 0 20px;

            font-size: 13px;
            font-weight: 700;

            color: #111827;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }

        .lacak-timeline {
            position: relative;
        }

        .lacak-timeline-item {
            position: relative;

            display: flex;

            gap: 18px;

            padding-bottom: 24px;
        }

        .lacak-timeline-item:last-child {
            padding-bottom: 0;
        }

        .lacak-timeline-line {
            position: absolute;

            top: 27px;
            left: 8px;

            width: 2px;
            height: calc(100% - 10px);

            background: #dbe2ef;
        }

        .lacak-timeline-dot {
            position: relative;
            z-index: 2;

            width: 18px;
            height: 18px;

            flex: 0 0 18px;

            margin-top: 2px;

            border-radius: 50%;

            background: #2161ed;

            box-shadow:
                0 0 0 4px #eaf0ff;
        }

        .lacak-timeline-content {
            flex: 1;
        }

        .lacak-timeline-status {
            margin-bottom: 5px;

            font-size: 14px;
            font-weight: 600;

            color: #111827;
        }

        .lacak-timeline-description {
            margin: 0;

            font-size: 13px;

            color: #687086;
        }

        .lacak-timeline-time {
            margin-top: 5px;

            font-size: 11px;

            color: #9aa2b2;
        }


        /* =========================================================
           FAQ
           ========================================================= */

        .lacak-faq {
            width: 735px;
            max-width: calc(100% - 40px);

            margin: 45px auto 0;
        }

        .lacak-faq-heading {
            margin-bottom: 20px;

            text-align: center;
        }

        .lacak-faq-heading h2 {
            margin: 0;

            font-size: 30px;
            font-weight: 700;

            color: #111827;
        }

        .lacak-faq-heading p {
            margin: 8px 0 0;

            font-size: 14px;

            color: #737b8f;
        }

        .lacak-faq-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .lacak-faq-button {
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 18px 5px;

            border: none;

            background: transparent;

            color: #111827;

            font-family: inherit;

            font-size: 14px;
            font-weight: 600;

            text-align: left;

            cursor: pointer;
        }

        .lacak-faq-answer {
            padding: 0 5px 18px;

            font-size: 13px;
            line-height: 1.6;

            color: #687086;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1100px) {

            .lacak-back {
                left: 40px;
            }

            .lacak-heading h1 {
                font-size: 54px;
            }

        }


        @media (max-width: 768px) {

            .lacak-hero-bg {
                height: 350px;

                left: -20%;
                width: 140%;

                clip-path: ellipse(
                    80% 100%
                    at 50% 0%
                );
            }

            .lacak-dots {
                display: none;
            }

            .lacak-content {
                padding-top: 30px;
            }

            .lacak-back {
                position: relative;

                top: auto;
                left: auto;

                margin-left: 25px;
            }

            .lacak-heading {
                padding: 50px 20px 0;
            }

            .lacak-heading h1 {
                font-size: 42px;

                letter-spacing: -1px;
            }

            .lacak-heading p {
                font-size: 16px;
            }

            .lacak-card {
                margin-top: 65px;

                padding: 32px 25px 30px;
            }

            .lacak-result-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 500px) {

            .lacak-heading h1 {
                font-size: 34px;
            }

            .lacak-heading p {
                font-size: 14px;
            }

            .lacak-card {
                max-width: calc(100% - 25px);

                padding: 30px 18px 25px;
            }

            .lacak-card-icon {
                width: 74px;
                height: 74px;

                top: -43px;
            }

            .lacak-card-icon-inner {
                width: 45px;
                height: 45px;

                font-size: 21px;
            }

            .lacak-captcha-row {
                gap: 8px;
            }

            .lacak-captcha-image {
                width: 155px;
            }

            .lacak-submit {
                font-size: 17px;
            }

        }

    </style>


    <div class="lacak-page">

        {{-- =====================================================
             BACKGROUND
             ===================================================== --}}
        <div class="lacak-hero-bg"></div>

        <div class="lacak-dots"></div>


        {{-- =====================================================
             CONTENT
             ===================================================== --}}
        <div class="lacak-content">

            {{-- BACK --}}
            <a href="{{ url('/') }}" class="lacak-back">
                <span class="lacak-back-icon">←</span>
                <span>Kembali Ke Beranda</span>
            </a>


            {{-- =================================================
                 HEADER
                 ================================================= --}}
            <div class="lacak-heading">

                <h1>
                    Lacak Permohonan
                </h1>

                <p>
                    Masukkan nomor tiket Anda untuk melihat status terbaru dari
                    <br class="d-none d-md-block">
                    permohonan anda
                </p>

            </div>


            {{-- =================================================
                 FORM CARD
                 ================================================= --}}
            <div class="lacak-card">

                {{-- ICON ATAS --}}
                <div class="lacak-card-icon">
                    <div class="lacak-card-icon-inner">
                        🔎
                    </div>
                </div>


                <form
                    action="{{ route('lacak.proses') }}"
                    method="POST"
                >

                    @csrf


                    {{-- =========================================
                         NOMOR TIKET
                         ========================================= --}}
                    <div class="lacak-form-group">

                        <div class="lacak-form-title">

                            <span class="lacak-section-icon">
                                🎟
                            </span>

                            <span>
                                Nomor Tiket
                            </span>

                        </div>


                        <input
                            type="text"
                            id="nomor_tiket"
                            name="nomor_tiket"
                            class="lacak-input @error('nomor_tiket') is-invalid @enderror"
                            placeholder="Masukan nomor tiket ( Contoh : TK-20260720-xxxxx )"
                            value="{{ $nomor_tiket ?? '' }}"
                            required
                        >


                        @error('nomor_tiket')
                            <div class="lacak-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- GARIS --}}
                    <div class="lacak-divider"></div>


                    {{-- =========================================
                         CAPTCHA
                         ========================================= --}}
                    <div class="lacak-form-group">

                        <div class="lacak-form-title">

                            <span class="lacak-section-icon">
                                🛡
                            </span>

                            <span>
                                Captcha
                            </span>

                        </div>


                        <div class="lacak-captcha-row">

                            <div class="lacak-captcha-image">

                                <img
                                    src="{{ route('captcha13.image') }}"
                                    alt="Captcha"
                                    id="captcha-img"
                                >

                            </div>


                            <button
                                type="button"
                                class="lacak-refresh"
                                onclick="
                                    document.getElementById('captcha-img').src =
                                    '{{ route('captcha13.image') }}?' + Math.random();
                                "
                                title="Refresh Captcha"
                            >
                                ↻
                            </button>

                        </div>


                        <input
                            type="text"
                            id="captcha"
                            name="captcha"
                            class="lacak-input @error('captcha') is-invalid @enderror"
                            placeholder="Masukan Code Captcha"
                            required
                        >


                        @error('captcha')
                            <div class="lacak-error">
                                {{ $message }}
                            </div>
                        @enderror


                        {{-- BUTTON --}}
                        <button
                            type="submit"
                            class="lacak-submit"
                        >
                            Cari Tiket
                        </button>

                    </div>

                </form>

            </div>


            {{-- =================================================
                 ERROR SESSION
                 ================================================= --}}
            @if (session('error'))

                <div class="lacak-alert">

                    {{ session('error') }}

                </div>

            @endif


            {{-- =================================================
                 HASIL PENCARIAN
                 ================================================= --}}
            @if ($pengajuan)

                @php

                    $statusClass = match ($pengajuan->status_pengajuan) {

                        'DRAFT'
                            => '#6c757d',

                        'MENUNGGU_DIPROSES'
                            => '#f59e0b',

                        'DIPROSES'
                            => '#0ea5e9',

                        'DITOLAK'
                            => '#ef4444',

                        'PERBAIKAN'
                            => '#343a40',

                        'SELESAI'
                            => '#22c55e',

                        'SELESAI_PEMERIKSAAN'
                            => '#2563eb',

                        default
                            => '#6c757d',

                    };

                @endphp


                <div class="lacak-result">

                    {{-- RESULT HEADER --}}
                    <div class="lacak-result-header">

                        <h3>
                            Hasil Pencarian
                        </h3>

                        <span
                            class="lacak-status"
                            style="background: {{ $statusClass }};"
                        >
                            {{ str_replace('_', ' ', $pengajuan->status_pengajuan) }}
                        </span>

                    </div>


                    {{-- RESULT BODY --}}
                    <div class="lacak-result-body">

                        <div class="lacak-result-grid">

                            <div>

                                <div class="lacak-result-label">
                                    Nomor Tiket
                                </div>

                                <div class="lacak-result-value">
                                    {{ $pengajuan->nomor_tiket }}
                                </div>

                            </div>


                            <div>

                                <div class="lacak-result-label">
                                    Jenis Layanan
                                </div>

                                <div class="lacak-result-value">
                                    {{ $pengajuan->layanan->nama_layanan }}
                                </div>

                            </div>


                            <div>

                                <div class="lacak-result-label">
                                    Tanggal Pengajuan
                                </div>

                                <div class="lacak-result-value">

                                    {{
                                        $pengajuan->tanggal_pengajuan
                                            ? $pengajuan->tanggal_pengajuan->translatedFormat('d F Y')
                                            : '-'
                                    }}

                                </div>

                            </div>


                            <div>

                                <div class="lacak-result-label">
                                    Update Terakhir
                                </div>

                                <div class="lacak-result-value">

                                    {{
                                        $pengajuan->updated_at
                                            ->translatedFormat('d F Y, H:i')
                                    }}

                                </div>

                            </div>

                        </div>


                        <div class="lacak-divider"></div>


                        {{-- TIMELINE --}}
                        <div class="lacak-timeline-title">
                            Riwayat Perjalanan Tiket
                        </div>


                        <div class="lacak-timeline">

                            @forelse($pengajuan->riwayat as $log)

                                <div class="lacak-timeline-item">

                                    @if (!$loop->last)
                                        <div class="lacak-timeline-line"></div>
                                    @endif


                                    @php

                                        $markerColor = match ($log->status) {

                                            'DRAFT'
                                                => '#6c757d',

                                            'MENUNGGU_DIPROSES'
                                                => '#f59e0b',

                                            'DIPROSES'
                                                => '#0ea5e9',

                                            'DITOLAK'
                                                => '#ef4444',

                                            'PERBAIKAN'
                                                => '#343a40',

                                            'SELESAI'
                                                => '#22c55e',

                                            default
                                                => '#2563eb',

                                        };

                                    @endphp


                                    <div
                                        class="lacak-timeline-dot"
                                        style="
                                            background: {{ $markerColor }};
                                            box-shadow: 0 0 0 4px {{ $markerColor }}22;
                                        "
                                    ></div>


                                    <div class="lacak-timeline-content">

                                        <div class="lacak-timeline-status">
                                            {{ str_replace('_', ' ', $log->status) }}
                                        </div>

                                        <p class="lacak-timeline-description">
                                            {{ $log->keterangan }}
                                        </p>

                                        <div class="lacak-timeline-time">

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $log->tanggal_disposisi
                                                )->translatedFormat('d M Y, H:i')
                                            }}

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div
                                    style="
                                        text-align:center;
                                        padding:30px 0;
                                        color:#8a92a3;
                                        font-size:13px;
                                    "
                                >
                                    Belum ada riwayat pergerakan tiket.
                                </div>

                            @endforelse

                        </div>


                        {{-- DOKUMEN HASIL --}}
                        @if (
                            $pengajuan->status_pengajuan === 'SELESAI'
                            &&
                            $pengajuan->dokumenHasil->count() > 0
                        )

                            <div
                                style="
                                    margin-top:30px;
                                    padding:20px;
                                    background:#eefbf3;
                                    border:1px solid #b7ebc8;
                                    border-radius:12px;
                                "
                            >

                                <div
                                    style="
                                        font-weight:600;
                                        color:#198754;
                                        margin-bottom:8px;
                                    "
                                >
                                    ✓ Dokumen Hasil Selesai
                                </div>

                                <div
                                    style="
                                        font-size:13px;
                                        color:#596579;
                                        line-height:1.6;
                                        margin-bottom:15px;
                                    "
                                >
                                    Permohonan Anda telah selesai diproses.
                                    Anda dapat mengunduh dokumen hasil melalui
                                    dashboard setelah melakukan login.
                                </div>

                                <a
                                    href="{{ route('login') }}"
                                    style="
                                        display:inline-flex;
                                        align-items:center;
                                        justify-content:center;
                                        padding:9px 18px;
                                        border-radius:8px;
                                        background:#198754;
                                        color:#ffffff;
                                        text-decoration:none;
                                        font-size:12px;
                                        font-weight:600;
                                    "
                                >
                                    Login untuk Unduh Hasil
                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- =================================================
                 FAQ
                 ================================================= --}}
            <div class="lacak-faq">

                <div class="lacak-faq-heading">

                    <h2>
                        Bantuan & FAQ
                    </h2>

                    <p>
                        Punya kendala saat melacak tiket?
                        Simak informasi berikut.
                    </p>

                </div>


                <div class="lacak-faq-item">

                    <button
                        type="button"
                        class="lacak-faq-button"
                        onclick="toggleLacakFaq('faq-1', this)"
                    >
                        <span>
                            Dimana saya bisa mendapatkan Nomor Tiket?
                        </span>

                        <span>
                            +
                        </span>

                    </button>

                    <div
                        id="faq-1"
                        class="lacak-faq-answer"
                        style="display:none;"
                    >
                        Nomor tiket didapatkan sesaat setelah Anda berhasil
                        membuat permohonan baru di dashboard. Nomor tiket juga
                        biasanya dikirimkan melalui email notifikasi jika
                        fitur tersebut dikonfigurasi.
                    </div>

                </div>


                <div class="lacak-faq-item">

                    <button
                        type="button"
                        class="lacak-faq-button"
                        onclick="toggleLacakFaq('faq-2', this)"
                    >
                        <span>
                            Tiket saya tidak ditemukan, apa yang harus saya lakukan?
                        </span>

                        <span>
                            +
                        </span>

                    </button>

                    <div
                        id="faq-2"
                        class="lacak-faq-answer"
                        style="display:none;"
                    >
                        Pastikan format nomor tiket sudah benar. Jika masih
                        tidak ditemukan, kemungkinan tiket telah dihapus atau
                        terjadi kesalahan sistem. Silahkan hubungi admin melalui
                        kontak yang tersedia.
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FAQ SCRIPT
         ========================================================= --}}
    <script>

        function toggleLacakFaq(id, button) {

            const element = document.getElementById(id);

            if (!element) {
                return;
            }

            const isOpen =
                element.style.display === 'block';

            element.style.display =
                isOpen ? 'none' : 'block';

            const icon =
                button.querySelector('span:last-child');

            if (icon) {

                icon.textContent =
                    isOpen ? '+' : '−';

            }

        }

    </script>

</x-landing-layout>