@extends('layouts/blankLayout')

@section('title', 'Error Page')

@section('content')
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <div class="misc-content text-center">
            <div class="misc-avatar mb-4">
                <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" alt="Error" class="img-fluid" width="300">
            </div>
            <h2 class="mb-2 mx-2">Page Not Found ⚠️</h2>
            <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</div>
@endsection
