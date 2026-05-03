@extends('layouts.app')

@section('title', 'Edit Video Source Audit - FSV Incident')

@section('content')
    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Video Source Audit</h1>
                <p>{{ $audit->videoSource->name ?? 'Video Source' }} - {{ $audit->audit_type_label }}</p>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Captured Trust Chain Data</h2>

        <div class="details">
            <div>
                <div class="label">Performed By</div>
                <div class="value">{{ $audit->performer_display_name }}</div>
            </div>

            <div>
                <div class="label">Performed At</div>
                <div class="value">{{ $audit->performed_at ? $audit->performed_at->format('M j, Y g:i A') : '-' }}</div>
            </div>

            <div>
                <div class="label">IP Address</div>
                <div class="value">{{ $audit->ip_address ?? '-' }}</div>
            </div>

            <div>
                <div class="label">Request</div>
                <div class="value">{{ $audit->request_method ?? '-' }} {{ $audit->request_path ?? '-' }}</div>
            </div>

            <div style="grid-column: 1 / -1;">
                <div class="label">User Agent</div>
                <div class="value" style="font-family: monospace; font-size: 12px; word-break: break-all;">{{ $audit->user_agent ?? '-' }}</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('video-source-audits.update', $audit) }}">
        @csrf
        @method('PUT')

        @include('video-source-audits._form', [
            'audit' => $audit,
            'nvrSystem' => $audit->videoSource,
        ])

        <div class="card">
            <button type="submit" class="btn">Update Audit</button>
            <a href="{{ route('nvr-systems.edit', $audit->videoSource) }}" class="btn btn-secondary">Back to Video Source</a>
        </div>
    </form>
@endsection
