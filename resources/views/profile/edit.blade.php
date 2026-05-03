@extends('layouts.app')

@section('title', 'My Profile - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>My Profile</h1>
                <p>Update the identity information used in incident records, uploads, and chain-of-custody events.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>User Information</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid">
                <div class="field">
                    <label for="first_name">First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        id="first_name"
                        value="{{ old('first_name', $user->first_name) }}"
                    >
                    @error('first_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="last_name">Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        id="last_name"
                        value="{{ old('last_name', $user->last_name) }}"
                    >
                    @error('last_name') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label for="name">Display Name / Fallback Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->name) }}"
                >
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="cell_phone">Cell Phone</label>
                    <input
                        type="text"
                        name="cell_phone"
                        id="cell_phone"
                        value="{{ old('cell_phone', $user->cell_phone) }}"
                        placeholder="Example: 615-555-1212"
                    >
                    @error('cell_phone') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="organization_name">Organization / Company</label>
                    <input
                        type="text"
                        name="organization_name"
                        id="organization_name"
                        value="{{ old('organization_name', $user->organization_name) }}"
                        placeholder="Example: Focus Secure Video"
                    >
                    @error('organization_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="role_title">Role / Title</label>
                    <input
                        type="text"
                        name="role_title"
                        id="role_title"
                        value="{{ old('role_title', $user->role_title) }}"
                        placeholder="Example: Evidence Analyst"
                    >
                    @error('role_title') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn">Update Profile</button>
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Back to Incidents</a>
        </form>
    </div>

    <div class="card">
        <h2>Evidence Identity Preview</h2>

        <div class="details">
            <div>
                <div class="label">Display Name</div>
                <div class="value">{{ $user->display_name }}</div>
            </div>

            <div>
                <div class="label">Profile Label</div>
                <div class="value">{{ $user->profile_label }}</div>
            </div>

            <div>
                <div class="label">Email</div>
                <div class="value">{{ $user->email }}</div>
            </div>

            <div>
                <div class="label">Cell Phone</div>
                <div class="value">{{ $user->cell_phone ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Organization</div>
                <div class="value">{{ $user->organization_name ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Role</div>
                <div class="value">{{ $user->role_title ?? '-' }}</div>
            </div>
        </div>
    </div>
@endsection
