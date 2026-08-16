<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous">
    </script>
    <style>
        .navbar-marketing .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #0061f2;
        }

        .navbar-marketing .nav-link {
            font-weight: 500;
            color: #4a515b;
        }

        .hero-section {
            padding-top: 8rem;
            padding-bottom: 8rem;
            background-color: #f2f6fc;
        }

        .icon-stack {
            height: 3.5rem;
            width: 3.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .hover-lift:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body>
    <div id="layoutDefault">
        <div id="layoutDefault_content">
            <main>
                <!-- Navbar-->
                <nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light fixed-top shadow-sm">
                    <div class="container px-4">
                        <a class="navbar-brand text-primary" href="{{ url('/') }}">{{ config('app.name') }}</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation"><i data-feather="menu"></i></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto me-lg-5 text-black">
                                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#layanan">Layanan</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lacak') }}">Cek Status
                                        Permohonan</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#faq">FAQ</a></li>
                            </ul>
                            @if (Route::has('login'))
                                @auth
                                    <a class="btn btn-primary fw-500" href="{{ url('/dashboard') }}">Dashboard <i
                                            class="ms-2" data-feather="arrow-right"></i></a>
                                @else
                                    <a class="btn btn-primary-soft text-primary fw-500 me-2"
                                        href="{{ route('login') }}">Masuk</a>
                                    @if (Route::has('register'))
                                        <a class="btn btn-primary fw-500" href="{{ route('register') }}">Daftar</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </nav>

                {{ $slot }}

            </main>
        </div>
        <div id="layoutDefault_footer">
            <footer class="footer pt-10 pb-5 mt-auto bg-white footer-light">
                <div class="container px-4">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="footer-brand">{{ config('app.name') }}</div>
                            <div class="mb-3">Pusat Layanan Terpadu TIK</div>
                            <div class="icon-list-social mb-5">
                                <a class="icon-list-social-link" href="https://www.instagram.com/kominfobogor/"><i class="fab fa-instagram"></i></a>
                                <a class="icon-list-social-link" href="https://web.facebook.com/kominfobogor/?locale=id_ID&_rdc=1&_rdr#"><i class="fab fa-facebook"></i></a>
                                <a class="icon-list-social-link" href="https://twitter.com/kominfobogor"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            {{-- <div class="row">
                                <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                                    <div class="text-uppercase-expanded text-xs mb-4">Layanan</div>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($footerServices as $service)
                                            <li class="mb-2"><a
                                                    href="{{ route('layanan.detail', $service->slug) }}">{{ $service->nama_layanan }}</a>
                                            </li>
                                        @endforeach
                                        <li class="mb-2"><a href="{{ route('layanan.semua') }}">Lihat Semua</a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                                    <div class="text-uppercase-expanded text-xs mb-4">Informasi</div>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="#!">Cara Penggunaan</a></li>
                                        <li class="mb-2"><a href="#!">Syarat & Ketentuan</a></li>
                                        <li class="mb-2"><a href="#!">FAQ</a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="text-uppercase-expanded text-xs mb-4">Kontak</div>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><a href="#!">Hubungi Kami</a></li>
                                        <li class="mb-2"><a href="#!">Lokasi Kantor</a></li>
                                    </ul>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <hr class="my-5" />
                    <div class="row align-items-center">
                        <div class="col-md-6 small">Copyright &copy; {{ config('app.name') }} {{ date('Y') }}
                        </div>
                        {{-- <div class="col-md-6 text-md-end small">
                            <a href="#!">Privacy Policy</a>
                            &middot;
                            <a href="#!">Terms &amp; Conditions</a>
                        </div> --}}
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
