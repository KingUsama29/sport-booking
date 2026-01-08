<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Auth - Naisya Sport Booking')</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom -->
    <link href="{{ asset('css/naisya.css') }}" rel="stylesheet">
</head>

<body class="naisya-body">

    <main class="naisya-auth-wrap">
        <div class="container naisya-auth-shell">
            @include('partials.alerts')

            @yield('content')

            <div class="text-center mt-4 small text-muted">
                <a class="naisya-link" href="{{ route('home') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke
                    Beranda</a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
