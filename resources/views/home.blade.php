@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Guardian Cloud Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('Welcome to Guardian Cloud!') }}</p>
                    <p>{{ __('Select an action to get started:') }}</p>

                    <ul>
                        <li><a href="{{ route('rooms.index') }}">View Rooms</a></li>
                        <li><a href="{{ route('emergency.report') }}">Report Emergency</a></li>
                        <li><a href="{{ route('invite.form') }}">Send Invite</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

