@extends('layouts/blankLayout')

@section('title', 'Not Authorized - 403')

<style>
.misc-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    min-height: 100vh;
}
</style>

@section('content')
<!-- Not Authorized Error -->
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2 text-primary" style="line-height: 6rem;font-size: 6rem; font-weight: 800; background: linear-gradient(90deg, #696cff, #9ba0fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">403</h1>
        <h4 class="mb-2 mx-2">Access Denied! 🔐</h4>
        <p class="mb-6 mx-2 text-muted">You do not have the required permissions to view this page or perform this action.</p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg mt-2 shadow-sm">
            <i class="bx bx-home-alt me-2"></i> Return to Dashboard
        </a>
    </div>
</div>
<!-- /Not Authorized Error -->
@endsection
