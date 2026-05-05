@extends('layouts.app')

@section('title', 'Start Free Checkup - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Start Free Checkup</h1>
                <p>
                    Request a basic surveillance system checkup. We will collect enough information
                    to understand the site, contact you, and begin a video source profile.
                </p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <div class="card">
        <h2>Required Contact Information</h2>

        <form method="POST" action="{{ route('free-checkup.store') }}">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="contact_name">Contact Name *</label>
                    <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required>
                    @error('contact_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="contact_email">Contact Email *</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required>
                    @error('contact_email') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label for="contact_phone">Cell Phone *</label>
                <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" required>
                <div style="font-size: 13px; color: #4b5563; margin-top: 6px;">
                    We use this for follow-up and future secure SMS notifications.
                </div>
                @error('contact_phone') <div class="error">{{ $message }}</div> @enderror
            </div>

            <h2 style="margin-top: 24px;">Camera Access Basics</h2>

            <div class="grid">
                <div class="field">
                    <label for="can_view_on_phone">Can you view your cameras from a phone app? *</label>
                    <select name="can_view_on_phone" id="can_view_on_phone" required>
                        <option value="">Select one</option>
                        <option value="yes" {{ old('can_view_on_phone') === 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('can_view_on_phone') === 'no' ? 'selected' : '' }}>No</option>
                        <option value="not_sure" {{ old('can_view_on_phone') === 'not_sure' ? 'selected' : '' }}>Not sure</option>
                    </select>
                    @error('can_view_on_phone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="can_view_away_from_site">Can you view cameras when you are away from the site? *</label>
                    <select name="can_view_away_from_site" id="can_view_away_from_site" required>
                        <option value="">Select one</option>
                        <option value="yes" {{ old('can_view_away_from_site') === 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('can_view_away_from_site') === 'no' ? 'selected' : '' }}>No</option>
                        <option value="not_sure" {{ old('can_view_away_from_site') === 'not_sure' ? 'selected' : '' }}>Not sure</option>
                    </select>
                    @error('can_view_away_from_site') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <h2 style="margin-top: 24px;">Optional Site Details</h2>

            <div class="grid">
                <div class="field">
                    <label for="organization_name">Business / Organization Name</label>
                    <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name') }}">
                    @error('organization_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="site_name">Site / Location Name</label>
                    <input type="text" name="site_name" id="site_name" value="{{ old('site_name') }}" placeholder="Main Office, Store 101, Warehouse, etc.">
                    @error('site_name') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label for="physical_address">Physical Address</label>
                <input type="text" name="physical_address" id="physical_address" value="{{ old('physical_address') }}">
                @error('physical_address') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="dvr_location">Where is the DVR/NVR located?</label>
                <input type="text" name="dvr_location" id="dvr_location" value="{{ old('dvr_location') }}" placeholder="Office closet, server room, manager office, unknown, etc.">
                @error('dvr_location') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" placeholder="Anything you know about the camera system, login access, issues, or recent incidents.">{{ old('notes') }}</textarea>
                @error('notes') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn">Start Free Checkup</button>
            <a href="{{ route('register') }}" class="btn btn-secondary">Register Instead</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Already Have an Account?</a>
        </form>
    </div>
@endsection
