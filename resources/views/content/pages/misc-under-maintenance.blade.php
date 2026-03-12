@extends('layouts/blankLayout')

@section('title', 'Under Maintenance')

@section('content')
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <div class="misc-content text-center">
            <div class="misc-avatar mb-4">
                <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance-light.png') }}" alt="Maintenance" class="img-fluid" width="300">
            </div>
            <h2 class="mb-2 mx-2">Under Maintenance 🛠️</h2>
            <p class="mb-4 mx-2">Sorry for the inconvenience but we're performing some maintenance at the moment. We'll be back online shortly!</p>
            <div class="mb-4">
                <h6 class="mb-2">Estimated Time:</h6>
                <div class="d-flex justify-content-center gap-2">
                    <div class="bg-light rounded p-2">
                        <h4 class="mb-0">02</h4>
                        <small>Hours</small>
                    </div>
                    <div class="bg-light rounded p-2">
                        <h4 class="mb-0">45</h4>
                        <small>Minutes</small>
                    </div>
                    <div class="bg-light rounded p-2">
                        <h4 class="mb-0">30</h4>
                        <small>Seconds</small>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-muted">In the meantime, you can:</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="mailto:support@example.com" class="btn btn-outline-primary">
                        <i class="bx bx-envelope me-1"></i> Contact Support
                    </a>
                    <a href="#" class="btn btn-primary">
                        <i class="bx bx-refresh me-1"></i> Check Status
                    </a>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-muted small">Follow us on social media for updates:</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="bx bxl-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-info btn-sm">
                        <i class="bx bxl-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-danger btn-sm">
                        <i class="bx bxl-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-sm">
                        <i class="bx bxl-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
