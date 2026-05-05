@extends('layouts.app')

@section('title', 'Incidents - FSV Incident')

@section('content')
    @php
        $sortLink = function ($label, $column) use ($sort, $direction) {
            $nextDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';

            $arrow = $sort === $column
                ? ($direction === 'asc' ? ' ▲' : ' ▼')
                : ' ⇅';

            return route('incidents.index', array_merge(request()->query(), [
                'sort' => $column,
                'direction' => $nextDirection,
            ]));
        };
    @endphp

    <style>
        .incident-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(125px, 1fr));
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

        .cell-link,
        .sort-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .cell-link:hover,
        .sort-link:hover {
            text-decoration: underline;
        }

        .muted-small {
            font-size: 12px;
            color: #6b7280;
        }

        .status-pill {
            display: inline-block;
            border-radius: 999px;
            padding: 2px 8px;
            background: #f3f4f6;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-open {
            background: #dcfce7;
            color: #166534;
        }

        .status-archived {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-icons {
            white-space: nowrap;
        }

        .action-icons a {
            color: #2563eb;
            text-decoration: none;
            font-size: 17px;
            font-weight: 700;
            margin-right: 6px;
        }

        .action-icons a:hover {
            text-decoration: underline;
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

        .incident-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
    </style>

    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident</h1>
                <p>
                    Track incidents, evidence files, archive status, customer/site context,
                    and the beginning of chain-of-custody activity.
                </p>

                <div class="incident-dashboard">
                    <div class="dash-pill">
                        <div class="label">Total</div>
                        <div class="value">
                            <a href="{{ route('incidents.index') }}" title="View all active incidents">
                                {{ $summary['total'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Active</div>
                        <div class="value">
                            <a href="{{ route('incidents.index') }}" title="View active incidents">
                                {{ $summary['active'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Archived</div>
                        <div class="value">
                            <a href="{{ route('incidents.index', ['archived' => 1]) }}" title="View archived incidents">
                                {{ $summary['archived'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">With Evidence</div>
                        <div class="value">
                            {{ $summary['with_evidence'] ?? 0 }}
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Sites</div>
                        <div class="value">
                            <a href="{{ route('sites.index') }}" title="View sites">
                                {{ $summary['sites_with_incidents'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Active Sites</div>
                        <div class="value">
                            {{ $summary['sites_with_active_incidents'] ?? 0 }}
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Organizations</div>
                        <div class="value">
                            <a href="{{ route('organizations.index') }}" title="View organizations">
                                {{ $summary['organizations_with_incidents'] ?? 0 }}
                            </a>
                        </div>
                    </div>

                    <div class="dash-pill">
                        <div class="label">Customers</div>
                        <div class="value">
                            <a href="{{ route('organizations.index', ['type' => 'customer']) }}" title="View customers">
                                {{ $summary['customers_with_incidents'] ?? 0 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <img src="{{ asset('images/fsvi-logo-w-words-412x415.webp') }}" alt="FSV Incident" class="logo">
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="header-row" style="margin-bottom: 20px;">
            <div>
                <h2>{{ $showArchived ? 'Archived Incidents' : 'Active Incidents' }}</h2>
                <p style="margin-top: -8px;">
                    @if($siteId || $organizationId)
                        Filtered incident view. Use the main Incidents link to clear filters.
                    @elseif($showArchived)
                        Archived incidents are hidden from the active list.
                    @else
                        Default sort is newest incident first. Click a column header to sort.
                    @endif
                </p>
            </div>

            <div class="incident-actions">
                @if($showArchived)
                    <a href="{{ route('incidents.index') }}" class="btn btn-secondary">View Active</a>
                @else
                    <a href="{{ route('incidents.index', ['archived' => 1]) }}" class="btn btn-secondary">View Archived</a>

                    <form method="POST" action="{{ route('incidents.start') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn">Start New Incident</button>
                    </form>
                @endif
            </div>
        </div>

        @if($incidents->count())
            <div class="table-wrap">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th>
                                <a class="sort-link" href="{{ $sortLink('Incident #', 'id') }}">Incident</a>
                            </th>
                            <th>Account</th>
                            <th>Site</th>
                            <th>
                                <a class="sort-link" href="{{ $sortLink('Title', 'title') }}">Title</a>
                            </th>
                            <th>
                                <a class="sort-link" href="{{ $sortLink('Type', 'type') }}">Type</a>
                            </th>
                            <th>
                                <a class="sort-link" href="{{ $sortLink('Status', 'status') }}">Status</a>
                            </th>
                            <th>
                                <a class="sort-link" href="{{ $sortLink('Date/Time', 'datetime') }}">Date</a>
                            </th>
                            @if($showArchived)
                                <th>
                                    <a class="sort-link" href="{{ $sortLink('Archived', 'archived') }}">Archived</a>
                                </th>
                            @endif
                            <th>Ev.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($incidents as $incident)
                            @php
                                $status = strtolower((string) $incident->status);
                                $statusClass = $incident->archived_at
                                    ? 'status-archived'
                                    : ($status === 'open' ? 'status-open' : '');

                                $statusLabel = $incident->archived_at
                                    ? 'Archived'
                                    : ucwords(str_replace('_', ' ', $incident->status ?? 'open'));

                                $incidentTitle = $incident->title ?: 'Incident #' . $incident->id;
                            @endphp

                            <tr>
                                <td>
                                    <a class="cell-link" href="{{ route('incidents.show', $incident) }}">
                                        #{{ $incident->id }}
                                    </a>
                                </td>

                                <td>
                                    @if($incident->organization)
                                        <a class="cell-link" href="{{ route('organizations.edit', $incident->organization) }}" title="{{ $incident->organization->name }}">
                                            {{ \Illuminate\Support\Str::limit($incident->organization->name, 18) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($incident->site)
                                        <a class="cell-link" href="{{ route('sites.edit', $incident->site) }}" title="{{ $incident->site->name }}">
                                            {{ \Illuminate\Support\Str::limit($incident->site->name, 18) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <a class="cell-link" href="{{ route('incidents.show', $incident) }}" title="{{ $incidentTitle }}">
                                        {{ \Illuminate\Support\Str::limit($incidentTitle, 32) }}
                                    </a>

                                    @if($incident->location_name)
                                        <div class="muted-small" title="{{ $incident->location_name }}">
                                            {{ \Illuminate\Support\Str::limit($incident->location_name, 28) }}
                                        </div>
                                    @endif
                                </td>

                                <td title="{{ $incident->incident_type ?? '-' }}">
                                    {{ $incident->incident_type ? \Illuminate\Support\Str::limit($incident->incident_type, 14) : '-' }}
                                </td>

                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td title="{{ $incident->incident_datetime ? $incident->incident_datetime->format('M j, Y g:i A') : '-' }}">
                                    {{ $incident->incident_datetime ? $incident->incident_datetime->format('M j, Y') : '-' }}
                                    @if($incident->incident_datetime)
                                        <div class="muted-small">{{ $incident->incident_datetime->format('g:i A') }}</div>
                                    @endif
                                </td>

                                @if($showArchived)
                                    <td title="{{ $incident->archived_at ? $incident->archived_at->format('M j, Y g:i A') : '-' }}">
                                        {{ $incident->archived_at ? $incident->archived_at->format('M j, Y') : '-' }}
                                    </td>
                                @endif

                                <td>
                                    <a class="count-link" href="{{ route('incidents.show', $incident) }}#evidence" title="View evidence">
                                        {{ $incident->files_count ?? 0 }}
                                    </a>
                                </td>

                                <td class="action-icons">
                                    <a href="{{ route('incidents.show', $incident) }}" title="View incident">👁</a>

                                    @if(!$incident->archived_at)
                                        <span class="muted-small">/</span>
                                        <a href="{{ route('incidents.edit', $incident) }}" title="Edit incident">✎</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $incidents->links() }}
            </div>
        @else
            <div class="empty">
                @if($showArchived)
                    <h3>No archived incidents</h3>
                    <p>Archived incidents will appear here.</p>
                @else
                    <h3>No active incidents yet</h3>
                    <p>Start the first incident to begin building the evidence workflow.</p>

                    <form method="POST" action="{{ route('incidents.start') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn">Start New Incident</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection
