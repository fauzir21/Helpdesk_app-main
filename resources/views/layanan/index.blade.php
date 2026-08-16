<x-landing-layout>

    <x-slot name="title">
        Daftar Seluruh Layanan - {{ config('app.name') }}
    </x-slot>


    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        .services-page {
            position: relative;

            min-height: 100vh;

            padding:
                32px
                45px
                70px;

            font-family:
                "Poppins",
                sans-serif;

            overflow-x: hidden;
        }


        /* =====================================================
           BACKGROUND
        ===================================================== */

        .services-background {
            position: fixed;

            inset: 0;

            z-index: -10;

            background-image:
                url("/assets/img/backgrounds/bg-bogor.png");

            background-size: cover;

            background-position:
                center center;

            background-repeat: no-repeat;

            background-attachment: fixed;
        }


        .services-background-overlay {
            position: fixed;

            inset: 0;

            z-index: -9;

            background:
                rgba(255, 255, 255, 0.20);
        }


        /* =====================================================
           BACK TO HOME
        ===================================================== */

        .services-topbar {
            width: 100%;

            margin-bottom: 15px;
        }


        .services-back {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            color:
                #1558e8;

            font-size:
                13px;

            font-weight:
                500;

            text-decoration:
                none;

            transition:
                .2s ease;
        }


        .services-back:hover {
            color:
                #0d43bd;

            transform:
                translateX(-2px);
        }


        .services-back svg {
            width: 18px;

            height: 18px;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .services-header {
            width: 100%;

            text-align: center;

            margin:
                0 auto;
        }


        .services-title {
            margin: 0;

            color:
                #1558e8;

            font-size:
                clamp(36px, 4.5vw, 60px);

            line-height:
                1.08;

            font-weight:
                700;

            letter-spacing:
                -2px;
        }


        .services-subtitle {
            margin:
                10px auto 0;

            max-width:
                720px;

            color:
                #181818;

            font-size:
                13px;

            line-height:
                1.5;

            font-weight:
                400;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .services-search-wrapper {
            width:
                min(610px, 100%);

            margin:
                35px auto 12px;
        }


        .services-search {
            width: 100%;

            height: 48px;

            display: flex;

            align-items: center;

            border:
                1.5px solid
                #161616;

            border-radius:
                28px;

            background:
                rgba(255, 255, 255, 0.90);

            overflow:
                hidden;

            transition:
                .2s ease;
        }


        .services-search:focus-within {
            border-color:
                #1558e8;

            box-shadow:
                0 0 0 4px
                rgba(21, 88, 232, .10);
        }


        .services-search input {
            flex:
                1;

            min-width:
                0;

            height:
                100%;

            padding:
                0 22px;

            border:
                0;

            outline:
                0;

            background:
                transparent;

            color:
                #222;

            font-family:
                "Poppins",
                sans-serif;

            font-size:
                12px;
        }


        .services-search input::placeholder {
            color:
                #858995;
        }


        .services-search-clear {
            width:
                40px;

            height:
                100%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border:
                0;

            background:
                transparent;

            color:
                #222;

            cursor:
                pointer;
        }


        .services-search-clear svg {
            width:
                17px;

            height:
                17px;
        }


        .services-search-divider {
            width:
                1px;

            height:
                25px;

            background:
                #999;
        }


        .services-search-button {
            width:
                50px;

            height:
                100%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border:
                0;

            background:
                transparent;

            color:
                #111;

            cursor:
                pointer;
        }


        .services-search-button svg {
            width:
                19px;

            height:
                19px;
        }


        /* =====================================================
           FILTER
        ===================================================== */

        .services-filter {
            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            gap:
                25px;

            margin:
                0 auto 45px;
        }


        .service-filter-button {
            min-width:
                110px;

            height:
                28px;

            padding:
                0 16px;

            border:
                1px solid
                #111;

            border-radius:
                18px;

            background:
                rgba(255,255,255,.80);

            color:
                #111;

            font-family:
                "Poppins",
                sans-serif;

            font-size:
                10px;

            cursor:
                pointer;

            transition:
                .2s ease;
        }


        .service-filter-button:hover {
            border-color:
                #1558e8;

            color:
                #1558e8;
        }


        .service-filter-button.active {
            border-color:
                #1558e8;

            background:
                #1558e8;

            color:
                #ffffff;
        }


        /* =====================================================
           SERVICES CONTAINER
        ===================================================== */

        .services-container {
            width:
                min(1160px, 100%);

            margin:
                0 auto;
        }


        /* =====================================================
           GRID
        ===================================================== */

        .services-grid {
            display:
                grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap:
                22px;
        }


        /* =====================================================
           SERVICE CARD
        ===================================================== */

        .service-card {
            position:
                relative;

            min-height:
                225px;

            padding:
                22px 23px 20px;

            display:
                flex;

            flex-direction:
                column;

            border:
                1.2px solid
                #171717;

            border-radius:
                19px;

            background:
                rgba(255,255,255,.86);

            backdrop-filter:
                blur(5px);

            box-shadow:
                0 7px 20px
                rgba(0,0,0,.05);

            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }


        .service-card:hover {
            transform:
                translateY(-4px);

            background:
                rgba(255,255,255,.96);

            box-shadow:
                0 14px 28px
                rgba(0,0,0,.11);
        }


        /* =====================================================
           ICON
        ===================================================== */

        .service-card-icon {
            width:
                30px;

            height:
                30px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                flex-start;

            margin-bottom:
                10px;

            color:
                #111;
        }


        .service-card-icon svg {
            width:
                23px;

            height:
                23px;

            stroke-width:
                1.6;
        }


        /* =====================================================
           CARD TITLE
        ===================================================== */

        .service-card-title {
            margin:
                0 0 8px;

            color:
                #171717;

            font-size:
                19px;

            line-height:
                1.3;

            font-weight:
                500;
        }


        /* =====================================================
           CARD DESCRIPTION
        ===================================================== */

        .service-card-description {
            margin:
                0;

            color:
                #777b8c;

            font-size:
                12px;

            line-height:
                1.55;

            display:
                -webkit-box;

            -webkit-line-clamp:
                3;

            -webkit-box-orient:
                vertical;

            overflow:
                hidden;
        }


        /* =====================================================
           CARD FOOTER
        ===================================================== */

        .service-card-footer {
            margin-top:
                auto;

            padding-top:
                17px;

            display:
                flex;

            justify-content:
                flex-end;
        }


        /* =====================================================
           DETAIL BUTTON
        ===================================================== */

        .service-detail-button {
            min-width:
                125px;

            height:
                33px;

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            gap:
                6px;

            padding:
                0 14px;

            border:
                0;

            border-radius:
                18px;

            background:
                #1558e8;

            color:
                #ffffff;

            text-decoration:
                none;

            font-size:
                9px;

            font-weight:
                500;

            transition:
                .2s ease;
        }


        .service-detail-button:hover {
            background:
                #0d43bd;

            color:
                #ffffff;

            transform:
                translateX(2px);
        }


        .service-detail-button svg {
            width:
                14px;

            height:
                14px;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .services-empty {
            grid-column:
                1 / -1;

            min-height:
                220px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            text-align:
                center;

            border:
                1px solid
                #111;

            border-radius:
                20px;

            background:
                rgba(255,255,255,.85);

            color:
                #777;

            font-size:
                13px;
        }


        /* =====================================================
           PAGINATION
        ===================================================== */

        .services-pagination {
            display:
                flex;

            justify-content:
                center;

            margin-top:
                40px;
        }


        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 1000px) {

            .services-page {
                padding:
                    28px
                    25px
                    60px;
            }


            .services-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 650px) {

            .services-page {
                padding:
                    22px
                    15px
                    50px;
            }


            .services-title {
                font-size:
                    37px;

                letter-spacing:
                    -1.5px;
            }


            .services-subtitle {
                font-size:
                    11px;
            }


            .services-search-wrapper {
                margin-top:
                    28px;
            }


            .services-filter {
                gap:
                    7px;

                margin-bottom:
                    30px;
            }


            .service-filter-button {
                min-width:
                    90px;

                padding:
                    0 9px;

                font-size:
                    9px;
            }


            .services-grid {
                grid-template-columns:
                    1fr;

                gap:
                    17px;
            }


            .service-card {
                min-height:
                    205px;
            }
        }


        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 400px) {

            .services-filter {
                flex-wrap:
                    wrap;
            }


            .service-filter-button {
                flex:
                    1;
            }


            .services-title {
                font-size:
                    32px;
            }
        }

    </style>


    <!-- =====================================================
         PAGE
    ====================================================== -->

    <div class="services-page">


        <!-- BACKGROUND -->

        <div class="services-background"></div>

        <div class="services-background-overlay"></div>


        <!-- =================================================
             BACK
        ================================================== -->

        <div class="services-topbar">

            <a
                href="{{ route('home') }}"
                class="services-back"
            >

                <i data-feather="arrow-left"></i>

                <span>
                    Kembali Ke Beranda
                </span>

            </a>

        </div>


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="services-header">

            <h1 class="services-title">
                Daftar Seluruh Layanan
            </h1>


            <p class="services-subtitle">
                Temukan Layanan yang Anda butuhkan dan lihat persyaratan yang di perlukan
            </p>

        </header>


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="services-search-wrapper">

            <form
                method="GET"
                action="{{ route('layanan.semua') }}"
                class="services-search"
            >

                <input
                    type="text"
                    name="q"
                    id="service-search-input"
                    value="{{ request('q') }}"
                    placeholder="Cari Layanan........"
                    autocomplete="off"
                >


                @if(request('q'))

                    <button
                        type="button"
                        class="services-search-clear"
                        onclick="clearServiceSearch()"
                        aria-label="Hapus pencarian"
                    >

                        <i data-feather="x"></i>

                    </button>

                @else

                    <div
                        class="services-search-clear"
                        style="visibility:hidden;"
                    >

                        <i data-feather="x"></i>

                    </div>

                @endif


                <div class="services-search-divider"></div>


                <button
                    type="submit"
                    class="services-search-button"
                    aria-label="Cari"
                >

                    <i data-feather="search"></i>

                </button>

            </form>

        </div>


        <!-- =================================================
             FILTER
        ================================================== -->

        <div class="services-filter">


            <button
                type="button"
                class="service-filter-button
                    {{ request('kategori', 'semua') === 'semua' ? 'active' : '' }}"
                onclick="filterService('semua')"
            >

                Semua Layanan

            </button>


            <button
                type="button"
                class="service-filter-button
                    {{ request('kategori') === 'eksternal' ? 'active' : '' }}"
                onclick="filterService('eksternal')"
            >

                Eksternal

            </button>


            <button
                type="button"
                class="service-filter-button
                    {{ request('kategori') === 'internal' ? 'active' : '' }}"
                onclick="filterService('internal')"
            >

                Internal

            </button>


        </div>


        <!-- =================================================
             SERVICE LIST
        ================================================== -->

        <main class="services-container">

            <div class="services-grid">


                @forelse($layanan as $item)


                    <article class="service-card">


                        <!-- ICON -->

                        <div class="service-card-icon">

                            <i data-feather="file-text"></i>

                        </div>


                        <!-- TITLE -->

                        <h2 class="service-card-title">

                            {{ $item->nama_layanan }}

                        </h2>


                        <!-- DESCRIPTION -->

                        <p class="service-card-description">

                            {{ $item->deskripsi ?: 'Layanan pengajuan online Pemerintah Kota Bogor.' }}

                        </p>


                        <!-- DETAIL -->

                        <div class="service-card-footer">

                            <a
                                href="{{ route('layanan.detail', $item->slug) }}"
                                class="service-detail-button"
                            >

                                <span>
                                    Detail Layanan
                                </span>

                                <i data-feather="arrow-right"></i>

                            </a>

                        </div>


                    </article>


                @empty


                    <div class="services-empty">

                        <div>

                            <i
                                data-feather="search"
                                style="
                                    width:35px;
                                    height:35px;
                                    margin-bottom:10px;
                                "
                            ></i>

                            <div>
                                Layanan yang Anda cari belum tersedia.
                            </div>

                        </div>

                    </div>


                @endforelse


            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->

            @if($layanan->hasPages())

                <div class="services-pagination">

                    {{ $layanan->appends(request()->query())->links() }}

                </div>

            @endif


        </main>


    </div>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>


        /* =====================================================
           FILTER
        ===================================================== */

        function filterService(category) {

            const url =
                new URL(
                    "{{ route('layanan.semua') }}",
                    window.location.origin
                );


            const searchInput =
                document.getElementById(
                    "service-search-input"
                );


            if (
                searchInput &&
                searchInput.value.trim() !== ""
            ) {

                url.searchParams.set(
                    "q",
                    searchInput.value.trim()
                );

            }


            if (category !== "semua") {

                url.searchParams.set(
                    "kategori",
                    category
                );

            }


            window.location.href =
                url.toString();

        }


        /* =====================================================
           CLEAR SEARCH
        ===================================================== */

        function clearServiceSearch() {

            const url =
                new URL(
                    "{{ route('layanan.semua') }}",
                    window.location.origin
                );


            const category =
                "{{ request('kategori') }}";


            if (
                category &&
                category !== "semua"
            ) {

                url.searchParams.set(
                    "kategori",
                    category
                );

            }


            window.location.href =
                url.toString();

        }


        /* =====================================================
           FEATHER ICON
        ===================================================== */

        document.addEventListener(
            "DOMContentLoaded",
            function () {

                if (
                    typeof feather !==
                    "undefined"
                ) {

                    feather.replace();

                }

            }
        );

    </script>


</x-landing-layout>