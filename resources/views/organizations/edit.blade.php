@extends('layouts.app')

@section('title', 'Edit Organization - FSV Incident')

@section('content')
    @php
        $orgType = $organization->organization_type ?? 'customer';

        $managedLabel = match ($orgType) {
            'platform_owner' => 'Managed Organizations',
            'channel_partner', 'master_account' => 'Customers',
            'customer' => 'Sub-Organizations',
            'site_account' => 'Sub-Organizations',
            default => 'Managed Organizations',
        };

        $childCount = $organization->childOrganizations()->count();
        $userCount = $organization->users()->count();
        $siteCount = $organization->sites()->count();
        $incidentCount = $organization->incidents()->count();

        $status = strtolower((string) $organization->status);
        $isActive = in_array($status, ['active', 'open', 'enabled'], true);
    @endphp

    <style>
        .org-summary-strip {
            display: grid;
            grid-template-columns: repeat(6, minmax(120px, 1fr));
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

        .org-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
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

        .mini-section {
            margin-top: 18px;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }

        .compact-table th,
        .compact-table td {
            vertical-align: top;
            font-size: 14px;
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

        @media (max-width: 1050px) {
            .org-summary-strip {
                grid-template-columns: repeat(3, minmax(120px, 1fr));
            }
        }

        @media (max-width: 650px) {
            .org-summary-strip {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>Edit Organization</h1>
                <p>
                    {{ $organization->name }}
                    @if($organization->parentOrganization)
                        <span class="muted-small">
                            under {{ $organization->parentOrganization->name }}
                        </span>
                    @endif
                </p>

                <div class="org-summary-strip">
                    <div class="summary-pill">
                        <div class="label">Organization ID</div>
                        <div class="value">#{{ $organization->id }}</div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Type</div>
                        <div class="value">{{ $organization->type_label }}</div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Status</div>
                        <div class="value">
                            @if($isActive)
                                <span class="status-check" title="Active">✓</span>
                            @else
                                <span class="status-muted">{{ $organization->status_label }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">{{ $managedLabel }}</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['parent_organization_id' => $organization->id]) }}" title="View managed organizations">
                                {{ $childCount }}
                            </a>
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Sites</div>
                        <div class="value">
                            <a href="{{ route('sites.index', ['organization_id' => $organization->id]) }}" title="View sites for this organization">
                                {{ $siteCount }}
                            </a>
                        </div>
                    </div>

                    <div class="summary-pill">
                        <div class="label">Incidents</div>
                        <div class="value">
                            <a href="{{ route('incidents.index', ['organization_id' => $organization->id]) }}" title="View incidents for this organization">
                                {{ $incidentCount }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="org-actions">
                    <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Back to Organizations</a>
                    <a href="{{ route('sites.create', ['organization_id' => $organization->id]) }}" class="btn btn-secondary">Add Site</a>
                    <a href="{{ route('incidents.create', ['organization_id' => $organization->id]) }}" class="btn btn-secondary">Add Incident</a>

                    @if(\Illuminate\Support\Facades\Route::has('organizations.create'))
                        <a href="{{ route('organizations.create', ['parent_organization_id' => $organization->id]) }}" class="btn btn-secondary">Add Customer / Org</a>
                    @endif
                </div>

                @if($organization->parentOrganization || $userCount)
                    <div class="mini-section">
                        @if($organization->parentOrganization)
                            <div class="muted-small">
                                Parent: {{ $organization->parentOrganization->name }}
                            </div>
                        @endif

                        <div class="muted-small">
                            Users: {{ $userCount }}
                        </div>
                    </div>
                @endif
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Organization Details</h2>

        <form method="POST" action="{{ route('organizations.update', $organization) }}">
            @csrf
            @method('PUT')

            @include('organizations._form', [
                'organization' => $organization,
                'parentOrganizations' => $parentOrganizations,
            ])

            <button type="submit" class="btn">Update Organization</button>
            <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Back to Organizations</a>
        </form>
    </div>

    @if($childCount)
        <div class="card">
            <h2>{{ $managedLabel }}</h2>

            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Sites</th>
                        <th>Incidents</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($organization->childOrganizations as $child)
                        @php
                            $childStatus = strtolower((string) $child->status);
                            $childIsActive = in_array($childStatus, ['active', 'open', 'enabled'], true);
                        @endphp

                        <tr>
                            <td>
                                <a class="cell-link" href="{{ route('organizations.edit', $child) }}">
                                    {{ $child->name }}
                                </a>
                            </td>

                            <td>{{ $child->type_label }}</td>

                            <td>
                                @if($childIsActive)
                                    <span class="status-check" title="Active">✓</span>
                                @else
                                    <span class="status-muted">{{ $child->status_label }}</span>
                                @endif
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('sites.index', ['organization_id' => $child->id]) }}" title="View sites">
                                    {{ $child->sites()->count() }}
                                </a>
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('incidents.index', ['organization_id' => $child->id]) }}" title="View incidents">
                                    {{ $child->incidents()->count() }}
                                </a>
                            </td>

                            <td>
                                <a class="cell-link" href="{{ route('organizations.edit', $child) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
