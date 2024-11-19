<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Guardian Cloud</title>

    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Brand Logo and Name -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/guardian-logo.png') }}" alt="Guardian Cloud Logo" style="height: 40px;">
                    <span style="font-weight: bold; font-size: 1.2rem; margin-left: 10px;">Guardian Cloud</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarContent" aria-controls="navbarContent" 
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Navigation Links -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('emergency.report') }}">Report Emergency</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rooms.index') }}">Rooms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('invite.form') }}">Send Invite</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/maps') }}">Maps</a> <!-- Fixed "Maps" link -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">School Management</a> <!-- Placeholder -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a> <!-- Placeholder -->
                        </li>
                    </ul>

                    <!-- Right Side of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" 
                                   role="button" data-bs-toggle="dropdown" aria-haspopup="true" 
                                   aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

