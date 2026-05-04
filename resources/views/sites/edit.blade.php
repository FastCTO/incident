@extends('layouts.app')

@section('title', 'Edit Site - FSV Incident')

@section('content')
    <style>
        .site-summary-strip {
            display: grid;
            grid-template-columns: repeat(5, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .summary-pill {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            background: #f9fafb;
            min-height: 62px;
        }

        .summary-pill .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .summary-pill .value {
            font-size: 15px;
            color: #111827;
            font-weight: 700;
            line-height: 1.25;
        }

        .summary-pill a {
            color: #2563eb;
            text-decoration: none;
        }

        .summary-pill a:hover {
            text-decoration: underline;
        }

        .site-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        @media (max-width: 900px) {
            .site-summary-strip {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }
        }

        @media (max-width: 560px) {
            .site-summary-strip {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Site</h1>
                <p>{{ $site->name }}</p>

                <div class="site-summary-strip">
                    <div class="summary-pill">
                        <div class="label">Site ID</div>
                        <div class="value">#{{ $site->id }}</div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Organization</div>
                        <div class="value">
                            @if($site->organization)
                                <a href="{{ route('organizations.edit', $site->organization) }}" title="Open organization">
                                    {{ \Illuminate\Support\Str::limit($site->organization->name, 24) }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Incidents</div>
                        <div class="value">
                            <a href="{{ route('incidents.index', ['site_id' => $site->id]) }}" title="View incidents for this site">
                                {{ $site->incidents()->count() }}
                            </a>
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Video Sources</div>
                        <div class="value">
                            <a href="{{ route('nvr-systems.index', ['site_id' => $site->id]) }}" title="View video sources for this site">
                                {{ method_exists($site, 'nvrSystems') ? $site->nvrSystems()->count() : 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Time Zone</div>
                        <div class="value">{{ $site->time_zone ?? '-' }}</div>
                    </div>
                </div>

                <div class="site-actions">
                    <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back to Sites</a>

                    @if(\Illuminate\Support\Facades\Route::has('nvr-systems.create'))
                        <a href="{{ route('nvr-systems.create', ['site_id' => $site->id]) }}" class="btn btn-secondary">Add Video Source</a>
                    @endif

                    <a href="{{ route('incidents.create', ['site_id' => $site->id]) }}" class="btn btn-secondary">Add Incident</a>
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Site Details</h2>

        <form method="POST" action="{{ route('sites.update', $site) }}">
            @csrf
            @method('PUT')

            @include('sites._form', ['site' => $site, 'organizations' => $organizations])

            <button type="submit" class="btn">Update Site</button>
            <a href="{{ route('sites.index') }}" class="btn btn-secondary">Back to Sites</a>
        </form>
    </div>
@endsection
