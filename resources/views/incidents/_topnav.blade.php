<div class="top-nav">
    <div class="top-nav-left">
        <strong>FSV Incident</strong>

        <a href="{{ route('incidents.index') }}">Incidents</a>

        <form method="POST" action="{{ route('incidents.start') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="nav-button-link">Start New Incident</button>
        </form>

        <a href="{{ route('incidents.index', ['archived' => 1]) }}">Archived</a>

        <a href="{{ route('sites.index') }}">Sites</a>

        @if(\Illuminate\Support\Facades\Route::has('nvr-systems.index'))
            <a href="{{ route('nvr-systems.index') }}">NVR/VMS</a>
        @endif

        <a href="{{ route('organizations.index') }}">Organizations</a>

        <a href="{{ route('organization.edit') }}">My Organization</a>

        <a href="{{ route('profile.edit') }}">My Profile</a>
    </div>

    <div class="top-nav-right">
        @auth
            <span>{{ Auth::user()->display_name ?? Auth::user()->name ?? Auth::user()->email }}</span>

            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        @endauth
    </div>
</div>
