@extends('layouts.app')

@section('title', 'New Video Source Audit - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>New Video Source Audit</h1>
                <p>Record a baseline, quarterly check, incident check, or security review for this trusted video source.</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    <form method="POST" action="{{ route('video-source-audits.store', $nvrSystem) }}">
        @csrf

        @include('video-source-audits._form', [
            'audit' => null,
            'nvrSystem' => $nvrSystem,
        ])

        <div class="card">
            <button type="submit" class="btn">Save Audit</button>
            <a href="{{ route('nvr-systems.edit', $nvrSystem) }}" class="btn btn-secondary">Back to Video Source</a>
        </div>
    </form>
@endsection
