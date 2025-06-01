@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📹 Video Access Logs</h1>

    @if(count($logs))
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Video ID</th>
                    <th>Hash</th>
                    <th>Viewer Name</th>
                    <th>Outbound Phone</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log['videoId'] }}</td>
                    <td>{{ $log['additionalData']['hash'] ?? 'N/A' }}</td>
                    <td>{{ $log['viewerId'] }}</td>
                    <td>{{ $log['outboundPhone'] ?? 'N/A' }}</td>
                    <td>{{ $log['ipAddress'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($log['timestamp'])->toDayDateTimeString() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning">
            No logs found.
        </div>
    @endif
</div>
@endsection

