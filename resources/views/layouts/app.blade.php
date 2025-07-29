@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
@endphp

<!doctype html>
<html lang="{{ str_replace('', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Projects') }}</title>

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        @if (!request()->is('login') && !request()->is('register'))
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Projects') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <!-- Add any left side links here -->
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                @if (Auth::user()->role == 'admin')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('adminAccept') }}">{{ __('Admin Accept') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('adminBandUser') }}">{{ __('Admin Band User') }}</a>
                                    </li>

                                @endif
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('home') }}">
                                            {{ __('Profile') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                                                                                                                    document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <main class="py-4 flex-grow-1" style="background-color: #fbf5e9;">
            @yield('content')
        </main>

        @if (!request()->is('login') && !request()->is('register'))
            <footer class="text-white py-4 mt-auto" style="background-color: #9b9b9b;">
                <div class="container">
                    <!-- Sección superior -->
                    <div class="row text-center border-bottom pb-3 mb-3">
                        <div class="col-md-4">
                            <span>Email: contacto@stemgranada.com</span>
                        </div>
                        <div class="col-md-4">
                            <span>Teléfono: +34 641 200 411</span>
                        </div>
                        <div class="col-md-4">
                            <span>Dirección: Avenida de Cádiz, 35 Granada, Andalucia 18007</span>
                        </div>
                    </div>

                    <!-- Sección inferior -->
                    <div class="row text-center align-items-center">
                        <div class="col-md-4">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo de Tu Empresa" class="img-fluid"
                                style="height: 50px;">
                        </div>
                        <div class="col-md-4">
                            <a href="https://stemgranada.com/" style="color: white;">STEM Granada</a>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center gap-3">
                            <a href="https://www.facebook.com/STEMgranada"
                                class="rounded-circle bg-light text-dark d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px;"><i class="bi bi-facebook"></i></a>
                            <a href="https://x.com/STEMgranada"
                                class="rounded-circle bg-light text-dark d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px;"><i class="bi bi-twitter"></i></a>
                            <a href="https://www.instagram.com/stemgranada/"
                                class="rounded-circle bg-light text-dark d-flex justify-content-center align-items-center"
                                style="width: 30px; height: 30px;"><i class="bi bi-instagram    "></i></a>
                        </div>
                    </div>
                </div>
            </footer>
        @endif

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        @stack('scripts')
    </div>
</body>

</html>