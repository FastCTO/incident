<div class="top-nav">
    <div class="top-nav-left">
        <strong class="brand">FSV Incident</strong>

        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>

            <div class="nav-dropdown">
                <button type="button" class="nav-dropdown-button">Incidents</button>

                <div class="nav-dropdown-menu">
                    <a href="{{ route('incidents.index') }}">Incident List</a>

                    <form method="POST" action="{{ route('incidents.start') }}">
                        @csrf
                        <button type="submit" class="nav-dropdown-form-button">Start Incident</button>
                    </form>

                    <a href="{{ route('incidents.index', ['archived' => 1]) }}">Archived Incidents</a>
                </div>
            </div>

            <div class="nav-dropdown">
                <button type="button" class="nav-dropdown-button">Sites</button>

                <div class="nav-dropdown-menu">
                    <a href="{{ route('sites.index') }}">Site List</a>
                    <a href="{{ route('sites.create') }}">Add Site</a>
                </div>
            </div>

            @if(\Illuminate\Support\Facades\Route::has('nvr-systems.index'))
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropdown-button">Video Sources</button>

                    <div class="nav-dropdown-menu">
                        <a href="{{ route('nvr-systems.index') }}">Video Source List</a>
                        <a href="{{ route('nvr-systems.create') }}">Add Video Source</a>
                    </div>
                </div>
            @endif

            <div class="nav-dropdown">
                <button type="button" class="nav-dropdown-button">Organizations</button>

                <div class="nav-dropdown-menu">
                    <a href="{{ route('organizations.index') }}">Organization List</a>
                    <a href="{{ route('organizations.create') }}">Add Organization</a>
                    <a href="{{ route('organization.edit') }}">My Organization</a>
                </div>
            </div>
        @endauth
    </div>

    <div class="top-nav-right">
        @auth
            <div class="nav-dropdown nav-dropdown-right">
                <button type="button" class="nav-dropdown-button">
                    {{ Auth::user()->display_name ?? Auth::user()->name ?? Auth::user()->email }}
                </button>

                <div class="nav-dropdown-menu">
                    <a href="{{ route('profile.edit') }}">My Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-dropdown-form-button">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>

            @if(\Illuminate\Support\Facades\Route::has('free-checkup.create'))
                <a href="{{ route('free-checkup.create') }}" class="btn" style="color: #ffffff;">Start Free Checkup</a>
            @endif
        @endauth
    </div>
</div>
