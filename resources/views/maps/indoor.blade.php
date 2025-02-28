@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Indoor Map - New Hope Academy</h1>
    <p><a href="{{ route('maps.index') }}" class="btn btn-secondary">Back to Outdoor Map</a></p>

    <!-- Indoor Map -->
    <div id="indoor-map">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" 
             xmlns:xlink="http://www.w3.org/1999/xlink" 
             viewBox="0 0 1200 1200" style="width: 100%; height: 600px;">
            <image width="2282" height="1182" xlink:href="{{ asset('images/map.png') }}"></image> 
            <a xlink:href="/rooms/1">
                <rect x="136" y="204" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
            <a xlink:href="/rooms/2">
                <rect x="312" y="456" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
            <a xlink:href="/rooms/20">
                <rect x="523" y="70" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
            <a xlink:href="/rooms/24">
                <rect x="385" y="207" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
            <a xlink:href="/rooms/21">
                <rect x="643" y="203" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
            <a xlink:href="/rooms/30">
                <rect x="976" y="202" fill="#fff" opacity="0" width="100" height="100"></rect>
            </a>
        </svg>
    </div>
</div>
@endsection

