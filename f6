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
        		<a class="nav-link" href="{{ route('school.management') }}">School Management</a>
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
<div id="chat-widget" style="position: fixed; bottom: 20px; right: 20px; width: 300px;">
    <div id="chat-header" style="background: #007bff; color: #fff; padding: 10px; cursor: pointer; border-radius: 10px 10px 0 0;">
        Chat with Us
    </div>
    <div id="chat-body" style="display: none; border: 1px solid #007bff; background: #fff; padding: 10px; max-height: 400px; overflow-y: auto; border-radius: 0 0 10px 10px;">
        <div id="chat-box" style="height: 300px; overflow-y: auto; border-bottom: 1px solid #ccc; margin-bottom: 10px;"></div>
        <input type="text" id="chat-input" class="form-control" placeholder="Type a message...">
        <button class="btn btn-primary mt-2 w-100" onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
    document.getElementById('chat-header').addEventListener('click', () => {
        const chatBody = document.getElementById('chat-body');
        chatBody.style.display = chatBody.style.display === 'none' ? 'block' : 'none';
    });

    async function sendMessage() {
        const message = document.getElementById('chat-input').value;
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `<div class="text-end"><strong>You:</strong> ${message}</div>`;

        const response = await fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message }),
        });

        const data = await response.json();
        chatBox.innerHTML += `<div class="text-start"><strong>Bot:</strong> ${data.response}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        document.getElementById('chat-input').value = '';
    }
</script>

</body>
</html>

