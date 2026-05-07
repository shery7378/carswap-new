@extends('layouts/contentNavbarLayout')

@section('title', __('Payment Settings'))

@section('content')
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      
      @if(session('success'))
          <div class="alert alert-success alert-dismissible mb-4 py-2" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <div class="row">
        <!-- Settings Form -->
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header border-bottom">
              <h5 class="card-title mb-0">{{ __('Payment Gateways & Credentials') }}</h5>
            </div>
            <div class="card-body mt-4">
              <form action="{{ route('app-ecommerce-settings-payment-store') }}" method="POST">
                @csrf
                
                <!-- Stripe Information -->
                <div class="mb-4">
                  <h6 class="mb-3 d-flex align-items-center">
                    <i class="bx bxl-stripe text-primary fs-3 me-2"></i> {{ __('Stripe Credentials') }}
                  </h6>
                  <p class="text-muted small mb-3">{{ __('These keys are required to process credit card transactions and subscriptions via Stripe.') }}</p>
                  
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label class="form-label">{{ __('Stripe Public Key / Publishable Key') }}</label>
                      <input type="text" name="stripe_public_key" class="form-control" value="{{ $settings['stripe_public_key'] ?? '' }}" placeholder="pk_test_...">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label class="form-label">{{ __('Stripe Secret Key') }}</label>
                      <input type="password" name="stripe_secret_key" class="form-control" value="{{ $settings['stripe_secret_key'] ?? '' }}" placeholder="sk_test_...">
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> {{ __('Save Changes') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Settings Sidebar -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header bg-light">
              <h5 class="card-title mb-0">{{ __('Help & Info') }}</h5>
            </div>
            <div class="card-body mt-3">
              <p class="small text-muted mb-3">
                {{ __('Ensure you copy the Live keys for production, and the Test keys while in development mode.') }}
              </p>
              <ul class="list-unstyled mb-0 small text-muted">
                <li class="mb-2"><i class="bx bx-check text-success me-1"></i> {{ __('Check Stripe Dashboard for API keys.') }}</li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
