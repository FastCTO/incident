@extends('layouts.app')

@section('content')
<div class="container">
    <h1>School Management Dashboard</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $school->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Security Status:</strong></p>
            <form method="POST" action="{{ route('school.management.update') }}">
                @csrf
                @method('PUT')
                <select name="status" class="form-select">
                    <option value="Normal" {{ $school->status == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Emergency" {{ $school->status == 'Emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="Active Shooter" {{ $school->status == 'Active Shooter' ? 'selected' : '' }}>Active Shooter</option>
                    <option value="Lockdown" {{ $school->status == 'Lockdown' ? 'selected' : '' }}>Lockdown</option>
                </select>
                <button type="submit" class="btn btn-primary mt-3">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection

