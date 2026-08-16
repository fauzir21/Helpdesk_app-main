<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>{{ config('app.name', 'Laravel') }}</title>

@stack('before-styles')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}" />

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@auth
    <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
@endauth

@stack('after-styles')
</head>

<body class="nav-fixed">
    @include('layouts.includes.adm-topnav')
    <div id="layoutSidenav">
        @include('layouts.includes.adm-sidenav')
        <div id="layoutSidenav_content">
            <main>
                {{ $slot }}
                @if(session('needs_consent'))
                    @include('components.consent-modal')
                @endif
                @include('components.riwayat-modal')
            </main>
            <footer class="footer-admin mt-auto footer-light">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="col-md-6 small">Copyright &copy; {{ config('app.name') }}</div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
    @stack('before-scripts')
    @include('layouts.includes.adm-scripts')
    @auth
        <script src="{{ asset('js/push-notification.js') }}"></script>
    @endauth
    @stack('after')
</body>

</html>
