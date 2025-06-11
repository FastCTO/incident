@extends('layouts.app')

@section('content')
<div class="container">

    {{-- School Info: Centered on Mobile --}}
    <div class="row justify-content-center text-center mb-4">
        <div class="col-12 col-md-4 mb-3">
            <img src="{{ asset('images/school.jpg') }}" alt="School" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
        </div>
        <div class="col-12 col-md-8 align-self-center">
            <h1>{{ $school->name }}</h1>
            <p class="mb-0">{{ $school->address }}</p>
        </div>
    </div>

    {{-- Summary Cards - Responsive Stack --}}
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">

        {{-- Status Box (Colored per status) --}}
        @php
            $statusColors = [
                'Normal' => 'bg-success text-white',
                'Lockdown' => 'bg-warning text-dark',
                'Tornado Shelter' => 'bg-secondary text-white',
                'Evacuation' => 'bg-info text-dark',
                'Active Shooter' => 'bg-danger text-white',
            ];
            $cardColor = $statusColors[$school->status] ?? 'bg-light';
        @endphp
        <div class="col">
            <div class="card text-center shadow-sm {{ $cardColor }}">
                <div class="card-body">
                    <div class="mb-2 fs-1">🛡️</div>
                    <h5 class="card-title">Current Status</h5>
                    <p class="fs-5">{{ $school->status }}</p>
                    <a href="{{ route('school.management') }}" class="btn btn-sm btn-outline-light">
                        Manage School Status
                    </a>
                </div>
            </div>
        </div>

        {{-- Report Emergency --}}
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1 text-danger">🚨</div>
                    <h5 class="card-title">Emergency</h5>
                    <p class="card-text">Report a new emergency incident</p>
                    <a href="{{ route('emergency.report') }}" class="btn btn-danger">Report Emergency</a>
                </div>
            </div>
        </div>

        {{-- Go to My Room --}}
        @if ($userRoom)
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">🏫</div>
                    <h5 class="card-title">Your Room</h5>
                    <p class="card-text">Quick access to your room dashboard</p>
                    <a href="{{ route('rooms.show', $userRoom->id) }}" class="btn btn-primary">Go to My Room</a>
                </div>
            </div>
        </div>
        @endif

        {{-- People Inside --}}
        <div class="col">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="mb-2 fs-1">👥</div>
                    <h5 class="card-title">People Inside</h5>
                    <p class="fs-4">{{ $peopleInBuilding }}</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

