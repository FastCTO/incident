@extends('layouts.app')

@section('title', 'Video Sources - FSV Incident')

@section('content')
    <style>
        .video-dashboard {
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

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .compact-table {
            width: 100%;
            table-layout: auto;
        }

        .compact-table th,
        .compact-table td {
            vertical-align: top;
            font-size: 14px;
        }

        .compact-table th {
            white-space: nowrap;
        }

        .cell-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .cell-link:hover {
            text-decoration: underline;
        }

        .muted-small {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.2;
        }

        .status-check {
            color: #15803d;
            font-weight: 800;
            font-size: 18px;
            line-height: 1;
        }

        .status-muted {
            color: #6b7280;
            font-size: 13px;
        }

        .status-warn {
            color: #b45309;
            font-weight: 700;
            font-size: 13px;
        }

        .count-link {
            display: inline-block;
            min-width: 24px;
            text-align: center;
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        .count-link:hover {
            text-decoration: underline;
        }

        .action-icons {
            white-space: nowrap;
        }

        .action-icons a,
        .action-icons button {
            color: #2563eb;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            margin-right: 6px;
        }

        .action-icons a:hover,
        .action-icons button:hover {
            text-decoration: underline;
        }

        .action-icons form {
            display: inline;
        }

        .action-icons button {
            background: none;
            border: 0;
            padding: 0;
            cursor: pointer;
            font-family: inherit;
        }

        @media (max-width: 1100px) {
            .video-dashboard {
                grid-template-columns: repeat(3, minmax(115px, 1fr));
            }
        }

        @media (max-width: 650px) {
            .video-dashboard {
                grid-template-columns: repeat(2, minmax(115px, 1fr));
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>Video Sources</h1>
                <p>
                    Video sources are DVRs, NVRs, VMS platforms, cameras, or cloud systems that may become
                    evidence sources during an incident. Audits help confirm recording, access, time sync, and retention.
                </p>

                <div class="video-dashboard">
                    <div class="dash-pill">
                        <div class="label">Video Sources</div>
                        <div class="value">
                            <a href="{{ route('nvr-systems.index') }}" title="View all video sources">
                                {{ $summary['video_sources'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Sites With Video</div>
                        <div class="value">
                            <a href="{{ route('sites.index') }}" title="View sites">
                                {{ $summary['sites_with_video'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Cameras</div>
                        <div class="value">{{ $summary['cameras'] ?? 0 }}</div>
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
                        <div class="label">Recent Check</div>
                        <div class="value">{{ $summary['recently_checked'] ?? 0 }}</div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Audited</div>
                        <div class="value">{{ $summary['audited'] ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="header-row" style="margin-bottom: 20px;">
            <div>
                <h2>Video Sources</h2>
                @if($organizationId || $siteId || $needsAudit)
                    <p style="margin-top: -8px;">
                        Filtered video source view. Use the Video Sources link to clear filters.
                    </p>
                @else
                    <p style="margin-top: -8px;">
                        Track trusted video systems by organization and site.
                    </p>
                @endif
            </div>

            <a href="{{ route('nvr-systems.create') }}" class="btn">Add Video Source</a>
        </div>

        @if($nvrSystems->count())
            <div class="table-wrap">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Org</th>
                            <th>Site</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>System</th>
                            <th>IP / Host</th>
                            <th>Cam.</th>
                            <th>Ret.</th>
                            <th>Checked</th>
                            <th>Aud.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($nvrSystems as $nvrSystem)
                            @php
                                $status = strtolower((string) $nvrSystem->status);
                                $isActive = in_array($status, ['active', 'online', 'enabled'], true);
                                $needsAuditRow = !$nvrSystem->last_checked_at || $nvrSystem->last_checked_at->lt(now()->subDays(90));

                                $typeLabel = $nvrSystem->system_type
                                    ? ucwords(str_replace('_', ' ', $nvrSystem->system_type))
                                    : '-';

                                $systemLabel = $nvrSystem->system_label ?: '-';
                                $latestAudit = $nvrSystem->latestAudit;
                            @endphp

                            <tr>
                                <td>
                                    <a class="cell-link" href="{{ route('nvr-systems.edit', $nvrSystem) }}" title="{{ $nvrSystem->name }}">
                                        {{ \Illuminate\Support\Str::limit($nvrSystem->name, 24) }}
                                    </a>
                                </td>

                                <td>
                                    @if($nvrSystem->organization)
                                        <a class="cell-link" href="{{ route('organizations.edit', $nvrSystem->organization) }}" title="{{ $nvrSystem->organization->name }}">
                                            {{ \Illuminate\Support\Str::limit($nvrSystem->organization->name, 18) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($nvrSystem->site)
                                        <a class="cell-link" href="{{ route('sites.edit', $nvrSystem->site) }}" title="{{ $nvrSystem->site->name }}">
                                            {{ \Illuminate\Support\Str::limit($nvrSystem->site->name, 18) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td title="{{ $typeLabel }}">
                                    {{ \Illuminate\Support\Str::limit($typeLabel, 10) }}
                                </td>

                                <td title="{{ $nvrSystem->status }}">
                                    @if($isActive)
                                        <span class="status-check" title="Active">✓</span>
                                    @else
                                        <span class="{{ $needsAuditRow ? 'status-warn' : 'status-muted' }}">
                                            {{ $nvrSystem->status ? \Illuminate\Support\Str::limit(ucwords(str_replace('_', ' ', $nvrSystem->status)), 12) : '-' }}
                                        </span>
                                    @endif
                                </td>

                                <td title="{{ $systemLabel }}">
                                    {{ \Illuminate\Support\Str::limit($systemLabel, 18) }}
                                </td>

                                <td>
                                    <span title="{{ $nvrSystem->ip_address ?? '-' }}">
                                        {{ $nvrSystem->ip_address ?? '-' }}
                                    </span>

                                    @if($nvrSystem->hostname)
                                        <div class="muted-small" title="{{ $nvrSystem->hostname }}">
                                            {{ \Illuminate\Support\Str::limit($nvrSystem->hostname, 18) }}
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $nvrSystem->camera_count ?? '-' }}</td>

                                <td>
                                    {{ $nvrSystem->estimated_retention_days ? $nvrSystem->estimated_retention_days . 'd' : '-' }}
                                </td>

                                <td>
                                    @if($nvrSystem->last_checked_at)
                                        <span title="{{ $nvrSystem->last_checked_at->format('M j, Y g:i A') }}">
                                            {{ $nvrSystem->last_checked_at->format('M j') }}
                                        </span>
                                        <div class="muted-small">{{ $nvrSystem->last_checked_at->format('Y') }}</div>
                                    @else
                                        <span class="status-warn" title="Needs audit">Audit</span>
                                    @endif
                                </td>

                                <td>
                                    @if(($nvrSystem->audits_count ?? 0) > 0 && $latestAudit)
                                        <a class="count-link" href="{{ route('video-source-audits.edit', $latestAudit) }}" title="Open latest audit">
                                            {{ $nvrSystem->audits_count }}
                                        </a>
                                    @else
                                        <span class="muted-small">0</span>
                                    @endif
                                </td>

                                <td class="action-icons">
                                    <a href="{{ route('video-source-audits.create', $nvrSystem) }}" title="Start audit">Audit</a>
                                    <span class="muted-small">/</span>
                                    <a href="{{ route('nvr-systems.edit', $nvrSystem) }}" title="View video source">👁</a>
                                    <span class="muted-small">/</span>
                                    <a href="{{ route('nvr-systems.edit', $nvrSystem) }}" title="Edit video source">✎</a>

                                    @if($latestAudit)
                                        <span class="muted-small">/</span>
                                        <a href="{{ route('video-source-audits.edit', $latestAudit) }}" title="Latest audit">Latest</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $nvrSystems->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No video sources yet</h3>
                <p>Add the first camera, NVR, DVR, VMS, or cloud video source for this account.</p>
                <a href="{{ route('nvr-systems.create') }}" class="btn">Add Video Source</a>
            </div>
        @endif
    </div>
@endsection
