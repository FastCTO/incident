@extends('layouts.app')

@section('title', 'Incidents - FSV Incident')

@section('content')
    @php
        function sort_link($label, $column, $currentSort, $currentDirection) {
            $nextDirection = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';

            $arrow = '';

            if ($currentSort === $column) {
                $arrow = $currentDirection === 'asc' ? ' ▲' : ' ▼';
            } else {
                $arrow = ' ⇅';
            }

            return '<a class="sort-link" href="' . route('incidents.index', [
                'sort' => $column,
                'direction' => $nextDirection,
            ]) . '">' . e($label . $arrow) . '</a>';
        }
    @endphp

    <div class="card">
        <div class="header-row">
            <div>
                <h1>FSV Incident</h1>
                <p>Track incident reports, review status, and begin evidence workflows.</p>
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
                <h2>Incidents</h2>
                <p style="margin-top: -8px;">
                    Default sort is newest incident first. Click a column header to sort.
                </p>
            </div>

            <form method="POST" action="{{ route('incidents.start') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn">Start New Incident</button>
            </form>
        </div>

        @if($incidents->count())
            <table>
                <thead>
                    <tr>
                        <th>{!! sort_link('Incident #', 'id', $sort, $direction) !!}</th>
                        <th>{!! sort_link('Title', 'title', $sort, $direction) !!}</th>
                        <th>{!! sort_link('Type', 'type', $sort, $direction) !!}</th>
                        <th>{!! sort_link('Status', 'status', $sort, $direction) !!}</th>
                        <th>{!! sort_link('Location', 'location', $sort, $direction) !!}</th>
                        <th>{!! sort_link('Date/Time', 'datetime', $sort, $direction) !!}</th>
                        <th>Evidence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $incident)
                        <tr>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}">
                                    Incident #{{ $incident->id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}">
                                    {{ $incident->title }}
                                </a>
                            </td>
                            <td>{{ $incident->incident_type ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $incident->status)) }}</td>
                            <td>{{ $incident->location_name ?? '-' }}</td>
                            <td>{{ $incident->incident_datetime ? $incident->incident_datetime->format('M j, Y g:i A') : '-' }}</td>
                            <td>{{ $incident->files_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}">View</a>
                                |
                                <a href="{{ route('incidents.edit', $incident) }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $incidents->links() }}
            </div>
        @else
            <div class="empty">
                <h3>No incidents yet</h3>
                <p>Start the first incident to begin building the evidence workflow.</p>

                <form method="POST" action="{{ route('incidents.start') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn">Start New Incident</button>
                </form>
            </div>
        @endif
    </div>
@endsection
