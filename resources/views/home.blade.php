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
                    <p>{{ __("You don't have a room assigned. Please contact your administrator.") }}</p>

                    <div class="text-center">
                        <a href="{{ route('emergency.report') }}" class="btn btn-danger">Report Emergency</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

