@extends('layouts/contentNavbarLayout')

@section('title', 'Subscription Plans')

@section('page-style')
<style>
.pricing-card {
  transition: all 0.3s ease-in-out;
  border: none;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  background: #fff;
}
.pricing-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.pricing-header {
  padding: 2.5rem 1.5rem;
  position: relative;
  background: #f8f9fa;
  border-bottom: 5px solid transparent;
}
.border-primary .pricing-header { border-bottom-color: #696cff; }
.border-success .pricing-header { border-bottom-color: #71dd37; }
.border-warning .pricing-header { border-bottom-color: #ffab00; }
.border-info .pricing-header { border-bottom-color: #03c3ec; }
.border-secondary .pricing-header { border-bottom-color: #8592a3; }

.price-box {
  display: flex;
  align-items: baseline;
  justify-content: center;
  margin-top: 1rem;
}
.feature-list {
  padding: 1.5rem;
  text-align: left;
}
.feature-item {
  margin-bottom: 0.8rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}
.feature-item i {
  font-size: 1.25rem;
  margin-right: 0.75rem;
}
.plan-actions {
  padding: 0 1.5rem 2rem;
}
.popular-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 0.7rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <h3 class="fw-bold mb-1">{{ __('Subscription Tiers') }}</h3>
      <p class="text-muted mb-0">{{ __('Manage your business packages and customer value levels') }}</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('create-subscriptions', 'admin-guard'))
    <a href="{{ route('app-subscription-create') }}" class="btn btn-primary d-flex align-items-center">
      <i class="bx bx-plus me-1"></i> {{ __('New Package') }}
    </a>
    @endif
  </div>

  <div class="row">
    @foreach($plans as $slug => $group)
    @php
      $monthly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['month', 'monthly']))->first();
      $yearly = $group->filter(fn($p) => in_array(strtolower($p->billing_period), ['year', 'yearly']))->first();
      $main = $monthly ?: ($yearly ?: $group->first()); 
    @endphp
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card pricing-card h-100 border-{{ $main->color }}">
        <div class="pricing-header text-center">
          @if($main->is_popular)
          <span class="badge bg-{{ $main->color }} popular-badge rounded-pill shadow-sm">{{ __('Popular Choice') }}</span>
          @endif
          <div class="avatar avatar-md mx-auto mb-3">
             <span class="avatar-initial rounded-circle bg-label-{{ $main->color }}">
                <i class="bx {{ $main->slug === 'free' ? 'bx-gift' : ($main->slug === 'partner-package' ? 'bx-buildings' : ($main->slug === 'several-cars' ? 'bx-car' : 'bx-crown')) }} fs-3"></i>
             </span>
          </div>
          <h4 class="fw-bold mb-0">{{ $main->name }}</h4>
          <p class="text-muted small mt-1 mb-0">{{ $main->description }}</p>
          
          {{-- Pricing Display --}}
          <div class="d-flex justify-content-center gap-4 mt-3">
             @if($monthly || $yearly)
                @if($monthly)
                <div class="text-center">
                    <div class="price-box mb-0">
                      <span class="h3 fw-extrabold text-primary mb-0">{{ number_format($monthly->price, 0) }}</span>
                    </div>
                    <small class="text-muted">{{ __('HUF / Monthly') }}</small>
                </div>
                @endif
                
                @if($yearly)
                <div class="text-center border-start ps-4">
                    <div class="price-box mb-0">
                      <span class="h3 fw-extrabold text-info mb-0">{{ number_format($yearly->price, 0) }}</span>
                    </div>
                    <small class="text-muted">{{ __('HUF / Yearly') }}</small>
                </div>
                @endif
             @else
                {{-- Fallback for legacy "both" or other periods --}}
                <div class="text-center">
                    <div class="price-box mb-0">
                      <span class="h3 fw-extrabold text-primary mb-0">{{ number_format($main->price, 0) }}</span>
                    </div>
                    <small class="text-muted">HUF / {{ ucfirst($main->billing_period) }}</small>
                </div>
             @endif
          </div>
        </div>
        
        <div class="card-body p-0">
          {{-- Feature Tabs --}}
          @if($monthly && $yearly)
          <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active py-2" data-bs-toggle="tab" data-bs-target="#monthly-features-{{ $slug }}">
                {{ __('Monthly Profile') }}
              </button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link py-2" data-bs-toggle="tab" data-bs-target="#yearly-features-{{ $slug }}">
                {{ __('Yearly Profile') }}
              </button>
            </li>
          </ul>
          @endif

          <div class="tab-content border-0 p-0">
            {{-- Monthly Tab Content --}}
            <div class="tab-pane fade show active" id="monthly-features-{{ $slug }}" role="tabpanel">
              <div class="feature-list px-4 mt-3" style="max-height: 250px; overflow-y: auto;">
                 @include('content.apps.subscription._feature_items', ['plan' => $monthly ?? $main])
              </div>
            </div>

            {{-- Yearly Tab Content (if available) --}}
            @if($yearly)
            <div class="tab-pane fade" id="yearly-features-{{ $slug }}" role="tabpanel">
               <div class="feature-list px-4 mt-3" style="max-height: 250px; overflow-y: auto;">
                 @include('content.apps.subscription._feature_items', ['plan' => $yearly])
              </div>
            </div>
            @endif
          </div>
        </div>

        <div class="plan-actions px-4 pb-4 mt-auto border-top pt-3 bg-light">
          <div class="d-grid gap-2">
            {{-- Loop through all plans in this group to ensure everything has an Edit button --}}
            @foreach($group as $plan)
            <div class="d-flex gap-2 align-items-center mb-2">
              <span class="badge bg-label-{{ $plan->is_active ? 'success' : 'secondary' }} flex-grow-0 me-1" style="min-width: 70px;">
                 {{ $plan->is_active ? __('LIVE') : __('OFF') }}
              </span>
              <a href="{{ route('app-subscription-plan-edit', $plan->id) }}" class="btn btn-outline-{{ $plan->billing_period == 'yearly' ? 'info' : 'primary' }} flex-grow-1">
                {{ __('Edit') }} {{ __(ucfirst($plan->billing_period)) }}
              </a>
              <button class="btn btn-label-{{ $plan->is_active ? 'secondary' : 'success' }} toggle-plan-status" data-id="{{ $plan->id }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                <i class="bx bx-power-off"></i>
              </button>
            </div>
            @endforeach

            <button class="btn btn-label-danger w-100 mt-2 delete-plan" data-id="{{ $main->id }}">
               <i class="bx bx-trash me-1"></i> {{ __('Delete Entire Card') }}
            </button>
          </div>
          
          <div class="mt-3 text-center border-top pt-2">
            <small class="text-muted d-block" style="font-size: 0.65rem;">
              <i class="bx bx-calendar me-1"></i>Card ID Prefix: {{ $slug }}
            </small>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

@endsection

@section('page-script')
<script>
$(document).on('click', '.toggle-plan-status', function() {
    const planId = $(this).data('id');
    const button = $(this);
    
    button.prop('disabled', true);
    
    $.ajax({
        url: `/app/subscription/plans/${planId}/status`,
        method: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong while updating the plan status'
            });
            button.prop('disabled', false);
        }
    });
});
$(document).on('click', '.delete-plan', function() {
    const planId = $(this).data('id');
    const button = $(this);
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the subscription plan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff3e1d',
        cancelButtonColor: '#8592a3',
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-danger me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            button.prop('disabled', true);
            $.ajax({
                url: `/app/subscription/plans/${planId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete the plan.'
                    });
                    button.prop('disabled', false);
                }
            });
        }
    });
});
</script>
@endsection

