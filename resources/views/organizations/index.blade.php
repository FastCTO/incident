@extends('layouts.app')

@section('title', 'Organizations - FSV Incident')

@section('content')
    <style>
        .org-dashboard {
            display: grid;
            grid-template-columns: repeat(3, minmax(150px, 1fr));
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

        @media (max-width: 800px) {
            .org-dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>Organizations</h1>
                <p>
                    Organizations are account-level records: FSV, channel partners, master accounts,
                    and customer accounts. Sites are physical customer locations managed separately.
                </p>

                <div class="org-dashboard">
                    <div class="dash-pill">
                        <div class="label">Organizations</div>
                        <div class="value">
                            <a href="{{ route('organizations.index') }}" title="View high-level organizations">
                                {{ $summary['total'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Channel / Master</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['type' => 'channel_partner']) }}" title="View channel partners and master accounts">
                                {{ $summary['channel_partners'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Customers</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['type' => 'customer']) }}" title="View customer accounts">
                                {{ $summary['customers'] ?? 0 }}
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
                <h2>Organizations</h2>
                <ul style="margin-top: -8px; padding-left: 20px; color: #374151;">
                    <li>Showing high-level organizations by default.</li>
                    <li>Click Customers to view customer accounts.</li>
                    <li>Customer sites are managed in the Sites area.</li>
                </ul>
            </div>

            <a href="{{ route('organizations.create') }}" class="btn">Add Organization</a>
        </div>

        @if($organizations->count())
            <table class="compact-table">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Customer Accounts</th>
                        <th>Sites</th>
                        <th>Inc.</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($organizations as $organization)
                        @php
                            $status = strtolower((string) $organization->status);
                            $isActive = in_array($status, ['active', 'open', 'enabled'], true);
                        @endphp

                        <tr>
                            <td>
                                <a class="cell-link" href="{{ route('organizations.edit', $organization) }}" title="Open organization">
                                    {{ $organization->name }}
                                </a>
                            </td>

                            <td>{{ $organization->type_label }}</td>

                            <td>
                                @if($isActive)
                                    <span class="status-check" title="Active">✓</span>
                                @else
                                    <span class="status-muted">{{ $organization->status_label }}</span>
                                @endif
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('organizations.index', ['parent_organization_id' => $organization->id]) }}" title="View customer accounts under this organization">
                                    {{ $organization->customer_accounts_count ?? 0 }}
                                </a>
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('sites.index', ['organization_id' => $organization->id]) }}" title="View sites tied to this organization/customer structure">
                                    {{ $organization->rollup_sites_count ?? 0 }}
                                </a>
                            </td>

                            <td>
                                <a class="count-link" href="{{ route('incidents.index', ['organization_id' => $organization->id]) }}" title="View incidents tied to this organization/customer structure">
                                    {{ $organization->rollup_incidents_count ?? 0 }}
                                </a>
                            </td>

                            <td class="action-icons">
                                <a href="{{ route('organizations.edit', $organization) }}" title="View organization">👁</a>
                                <span class="muted-small">/</span>
                                <a href="{{ route('organizations.edit', $organization) }}" title="Edit organization">✎</a>

                                @if(
                                    ($organization->child_organizations_count ?? 0) === 0 &&
                                    ($organization->users_count ?? 0) === 0 &&
                                    ($organization->sites_count ?? 0) === 0 &&
                                    ($organization->incidents_count ?? 0) === 0
                                )
                                    <span class="muted-small">/</span>
                                    <form method="POST" action="{{ route('organizations.destroy', $organization) }}" onsubmit="return confirm('Delete this organization?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete organization">×</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $organizations->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No organizations found</h3>
                <p>Add the first organization to begin building the channel/customer hierarchy.</p>
                <a href="{{ route('organizations.create') }}" class="btn">Add Organization</a>
            </div>
        @endif
    </div>
@endsection
