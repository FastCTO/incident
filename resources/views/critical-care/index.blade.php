@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Critical Care - Emergency First Aid</h1>
    <p>These instructional videos can help you respond to life-threatening injuries in an emergency.</p>
    
    <div class="row">
        @foreach($videos as $video)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $video['title'] }}</h5>
                        <iframe width="100%" height="315" src="{{ $video['url'] }}" frameborder="0" allowfullscreen></iframe>
                        <p class="card-text">{{ $video['description'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

