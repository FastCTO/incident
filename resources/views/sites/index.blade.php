@extends('layouts.app')

@section('title', 'Sites - FSV Incident')

@section('content')
    <style>
        .site-dashboard {
            display: grid;
            grid-template-columns: repeat(6, minmax(110px, 1fr));
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

        .compact-table th,
        .compact-table td {
            vertical-align: top;
            font-size: 14px;
        }

        .muted-small {
            font-size: 12px;
            color: #6b7280;
        }

        .cell-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .cell-link:hover {
            text-decoration: underline;
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

        .action-icons {
            white-space: nowrap;
        }

        .action-icons a,
        .action-icons button {
            color: #2563eb;
            text-decoration: none;
            font-size: 17px;
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

        .address-link {
            color: #2563eb;
            text-decoration: none;
        }

        .address-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 1100px) {
            .site-dashboard {
                grid-template-columns: repeat(3, minmax(110px, 1fr));
            }
        }

        @media (max-width: 650px) {
            .site-dashboard {
                grid-template-columns: repeat(2, minmax(110px, 1fr));
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>All Accessible Sites</h1>
                <p>
                    Sites are physical customer facilities or locations. A site can contain video sources,
                    cameras, incidents, evidence files, audits, and related activity.
                </p>

                <div class="site-dashboard">
                    <div class="dash-pill">
                        <div class="label">Customers</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['type' => 'customer']) }}" title="View customer organizations">
                                {{ $summary['customers'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Sites</div>
                        <div class="value">
                            <a href="{{ route('sites.index') }}" title="View all accessible sites">
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
                        <div class="label">Incidents</div>
                        <div class="value">
                            <a href="{{ route('incidents.index') }}" title="View incidents">
                                {{ $summary['incidents'] ?? 0 }}
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
                            <a href="{{ route('nvr-systems.index', ['needs_audit' => 1]) }}" title="Video sources needing audit">
                                {{ $summary['needs_audit'] ?? 0 }}
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

    <div class="card">
        <div class="header-row" style="margin-bottom: 20px;">
            <div>
                <h2>
                    @if($showOrganizationColumn)
                        Customer / Organization Sites
                    @else
                        {{ $organization->name ?? 'Organization' }} Sites
                    @endif
                </h2>

                <p style="margin-top: -8px;">
                    @if($showOrganizationColumn)
                        All sites available to your organization.
                    @else
                        Sites assigned to this organization.
                    @endif
                </p>
            </div>

            <a href="{{ route('sites.create') }}" class="btn">Add Site</a>
        </div>

        @if($sites->count())
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Site</th>

                        @if($showOrganizationColumn)
                            <th>Organization</th>
                        @endif

                        <th>Type</th>
                        <th>Status</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Video</th>
                        <th>Inc.</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sites as $site)
                        @php
                            $fullAddress = $site->full_address;
                            $shortAddress = $fullAddress && $fullAddress !== '-'
                                ? \Illuminate\Support\Str::limit($fullAddress, 22)
                                : '-';

                            $phoneHref = $site->contact_phone
                                ? preg_replace('/[^0-9+]/', '', $site->contact_phone)
                                : null;

                            $siteType = $site->site_type
                                ? ucwords(str_replace('_', ' ', $site->site_type))
                                : '-';

                            $siteStatus = strtolower((string) $site->status);
                            $isActive = in_array($siteStatus, ['active', 'open', 'enabled'], true);
                        @endphp

                        <tr>
                            <td>
                                <a class="cell-link" href="{{ route('sites.edit', $site) }}" title="Open site">
                                    {{ $site->display_name }}
                                </a>
                            </td>

                            @if($showOrganizationColumn)
                                <td>
                                    @if($site->organization)
                                        <a class="cell-link" href="{{ route('organizations.edit', $site->organization) }}" title="Open organization">
                                            {{ \Illuminate\Support\Str::limit($site->organization->name, 24) }}
                                        </a>

                                        @if($site->organization->organization_type)
                                            <div class="muted-small">
                                                {{ ucwords(str_replace('_', ' ', $site->organization->organization_type)) }}
                                            </div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif

                            <td title="{{ $siteType }}">
                                {{ \Illuminate\Support\Str::limit($siteType, 14) }}
                            </td>

                            <td title="{{ $site->status }}">
                                @if($isActive)
                                    <span class="status-check" title="Active">✓</span>
                                @else
                                    <span class="status-muted">
                                        {{ $site->status ? \Illuminate\Support\Str::limit(ucfirst($site->status), 10) : '-' }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($site->contact_email)
                                    <a class="cell-link" href="mailto:{{ $site->contact_email }}" title="{{ $site->contact_email }}">
                                        Email
                                    </a>
                                @else
                                    <span class="muted-small">No email</span>
                                @endif

                                @if($site->contact_phone && $phoneHref)
                                    <div>
                                        <a class="cell-link" href="tel:{{ $phoneHref }}" title="{{ $site->contact_phone }}">
                                            Call
                                        </a>
                                    </div>
                                @endif

                                @if($site->contact_name)
                                    <div class="muted-small" title="{{ $site->contact_name }}">
                                        {{ \Illuminate\Support\Str::limit($site->contact_name, 18) }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($fullAddress && $fullAddress !== '-')
                                    <a class="address-link" href="{{ route('sites.edit', $site) }}" title="{{ $fullAddress }}">
                                        {{ $shortAddress }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('nvr-systems.index', ['site_id' => $site->id]) }}" title="View video sources for this site">
                                    {{ $site->nvr_systems_count ?? 0 }}
                                </a>
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('incidents.index', ['site_id' => $site->id]) }}" title="View incidents for this site">
                                    {{ $site->incidents_count ?? 0 }}
                                </a>
                            </td>

                            <td class="action-icons">
                                <a href="{{ route('sites.edit', $site) }}" title="View site">👁</a>
                                <span class="muted-small">/</span>
                                <a href="{{ route('sites.edit', $site) }}" title="Edit site">✎</a>
                                <span class="muted-small">/</span>
                                <a href="{{ route('nvr-systems.index', ['site_id' => $site->id]) }}" title="Review video sources">Review</a>

                                @if(($site->incidents_count ?? 0) === 0 && ($site->nvr_systems_count ?? 0) === 0)
                                    <span class="muted-small">/</span>
                                    <form method="POST" action="{{ route('sites.destroy', $site) }}" onsubmit="return confirm('Delete this site?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete site">×</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $sites->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No sites yet</h3>
                <p>Add the first site for this organization.</p>
                <a href="{{ route('sites.create') }}" class="btn">Add Site</a>
            </div>
        @endif
    </div>
@endsection
