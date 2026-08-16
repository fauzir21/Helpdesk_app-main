<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="Layanan Diskominfo Kota Bogor"
    >

    <title>
        {{ $title ?? 'Layanan Diskominfo Kota Bogor' }}
    </title>


    <!-- Favicon -->
    <link
        rel="icon"
        type="image/x-icon"
        href="{{ asset('assets/img/favicon.png') }}"
    >


    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    >


    <!-- Feather Icons -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"
    ></script>


    <!-- Custom Landing CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('css/landing-custom.css') }}"
    >

</head>


<body>

    {{ $slot }}


    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    <!-- Existing scripts -->
    <script src="{{ asset('js/scripts.js') }}"></script>

</body>

</html>