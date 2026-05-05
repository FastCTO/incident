@extends('layouts.app')

@section('title', 'Dashboard - FSV Incident')

@section('content')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(115px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .dash-pill {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            background: #f9fafb;
            min-height: 62px;
        }

        .dash-pill .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .dash-pill .value {
            font-size: 20px;
            color: #111827;
            font-weight: 800;
            line-height: 1.1;
        }

        .dash-pill a {
            color: #2563eb;
            text-decoration: none;
        }

        .dash-pill a:hover {
            text-decoration: underline;
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .compact-list {
            margin: 0;
            padding-left: 20px;
            color: #374151;
        }

        .compact-list li {
            margin-bottom: 6px;
        }

        .mini-row {
            border-top: 1px solid #e5e7eb;
            padding: 12px 0;
        }

        .mini-row:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .cell-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }

        .cell-link:hover {
            text-decoration: underline;
        }

        .muted-small {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.25;
        }

        @media (max-width: 1100px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, minmax(115px, 1fr));
            }

            .dashboard-columns {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(115px, 1fr));
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident Dashboard</h1>

                <ul class="compact-list">
                    <li>Monitor customer accounts, sites, video sources, audits, and incidents.</li>
                    <li>Identify DVR/NVR systems that need review.</li>
                    <li>Jump quickly into active incident and evidence workflows.</li>
                </ul>

                <div class="dashboard-grid">
                    <div class="dash-pill">
                        <div class="label">Organizations</div>
                        <div class="value">
                            <a href="{{ route('organizations.index') }}" title="View organizations">
                                {{ $summary['organizations'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Customers</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['type' => 'customer']) }}" title="View customer accounts">
                                {{ $summary['customer_accounts'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Sites</div>
                        <div class="value">
                            <a href="{{ route('sites.index') }}" title="View sites">
                                {{ $summary['sites'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Video Sources</div>
                        <div class="value">
                            <a href="{{ route('nvr-systems.index') }}" title="View video sources">
                                {{ $summary['video_sources'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Need Audit</div>
                        <div class="value">
                            <a href="{{ route('nvr-systems.index', ['needs_audit' => 1]) }}" title="View video sources needing audit">
                                {{ $summary['need_audit'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Active Incidents</div>
                        <div class="value">
                            <a href="{{ route('incidents.index') }}" title="View active incidents">
                                {{ $summary['active_incidents'] ?? 0 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-columns">
        <div class="card">
            <h2>Needs Audit</h2>
            <p style="margin-top: -8px;">Video sources with no recent checkup or audit activity.</p>

            @forelse($auditNeeded as $nvrSystem)
                <div class="mini-row">
                    <a class="cell-link" href="{{ route('nvr-systems.edit', $nvrSystem) }}">
                        {{ $nvrSystem->name }}
                    </a>

                    <div class="muted-small">
                        {{ $nvrSystem->organization->name ?? 'No organization' }}
                        @if($nvrSystem->site)
                            / {{ $nvrSystem->site->name }}
                        @endif
                    </div>

                    <div style="margin-top: 6px;">
                        <a class="cell-link" href="{{ route('video-source-audits.create', $nvrSystem) }}">
                            Start Audit
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty">
                    <h3>No audit warnings</h3>
                    <p>All visible video sources have a recent check recorded.</p>
                </div>
            @endforelse
        </div>

        <div class="card">
            <h2>Active Incidents</h2>
            <p style="margin-top: -8px;">
                {{ $summary['sites_with_active_incidents'] ?? 0 }} site(s) currently have active incidents.
            </p>

            @forelse($recentIncidents as $incident)
                <div class="mini-row">
                    <a class="cell-link" href="{{ route('incidents.show', $incident) }}">
                        #{{ $incident->id }} - {{ $incident->title }}
                    </a>

                    <div class="muted-small">
                        {{ $incident->organization->name ?? 'No organization' }}
                        @if($incident->site)
                            / {{ $incident->site->name }}
                        @endif
                    </div>

                    @if($incident->incident_datetime)
                        <div class="muted-small">
                            {{ $incident->incident_datetime->format('M j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty">
                    <h3>No active incidents</h3>
                    <p>Active incidents will appear here when they are created.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
