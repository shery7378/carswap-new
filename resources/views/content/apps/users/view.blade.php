@extends('layouts/contentNavbarLayout')

@section('title', 'User Details - ' . $user->first_name . ' ' . $user->last_name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Reuse the Premium Partial Content for Consistent High-Fidelity UI -->
                @include('content.apps.users.partials.show-modal-content', [
                    'user' => $user,
                    'totalPostings' => $totalPostings,
                    'totalFavorites' => $totalFavorites,
                    'activePlan' => $activePlan,
                    'recentVehicles' => $recentVehicles
                ])
            </div>
        </div>
    </div>
</div>

<style>
/* Ensure the full page view doesn't have modal-specific constraints */
.user-details-modal {
    border-radius: 0;
}
.modal-body {
    max-height: none !important;
    overflow-y: visible !important;
}
.premium-footer {
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
}
</style>
@endsection
