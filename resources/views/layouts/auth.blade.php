<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta name="description"
        content="Layanan Diskominfo Kota Bogor">

    <title>{{ $title ?? 'Layanan Diskominfo Kota Bogor' }}</title>

    <link rel="icon"
        type="image/png"
        href="{{ asset('assets/img/logo_kotabogor.png') }}">

    {{-- Google Font --}}
    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Custom Auth CSS --}}
    <link rel="stylesheet"
        href="{{ asset('css/auth-custom.css') }}">

    {{-- Feather Icons --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"
        crossorigin="anonymous">
    </script>

    @stack('styles')
</head>

<body>

    <div class="auth-page">

        {{-- Background --}}
        <div class="auth-background"></div>

        {{-- Overlay --}}
        <div class="auth-overlay"></div>

        {{-- Main --}}
        <main class="auth-main">

            {{ $slot }}

        </main>

    </div>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>

    @stack('scripts')

</body>

</html>