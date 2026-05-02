<div class="top-nav">
    <div class="top-nav-left">
        <strong>FSV Incident</strong>
        <a href="{{ route('incidents.index') }}">Incidents</a>
        <a href="{{ route('incidents.create') }}">New Incident</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </div>

    <div class="top-nav-right">
        @auth
            <span>{{ Auth::user()->name ?? Auth::user()->email }}</span>

            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        @endauth
    </div>
</div>
