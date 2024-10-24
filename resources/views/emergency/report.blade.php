@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report Emergency</h1>
    <form action="{{ route('emergency.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

