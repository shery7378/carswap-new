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
      <h3 class="fw-bold mb-1">Subscription Tiers</h3>
      <p class="text-muted mb-0">Manage your business packages and customer value levels</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('create-subscriptions', 'admin-guard'))
    <a href="{{ route('app-subscription-create') }}" class="btn btn-primary d-flex align-items-center">
      <i class="bx bx-plus me-1"></i> New Package
    </a>
    @endif
  </div>

  <div class="row">
    @foreach($plans as $slug => $group)
    @php
      $monthly = $group->where('billing_period', 'monthly')->first();
      $yearly = $group->where('billing_period', 'yearly')->first();
      $main = $monthly ?? $yearly; // The primary display plan
    @endphp
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card pricing-card h-100 border-{{ $main->color }}">
        <div class="pricing-header text-center">
          @if($main->is_popular)
          <span class="badge bg-{{ $main->color }} popular-badge rounded-pill shadow-sm">Popular Choice</span>
          @endif
          <div class="avatar avatar-md mx-auto mb-3">
             <span class="avatar-initial rounded-circle bg-label-{{ $main->color }}">
                <i class="bx {{ $main->slug === 'free' ? 'bx-gift' : ($main->slug === 'partner-package' ? 'bx-buildings' : ($main->slug === 'several-cars' ? 'bx-car' : 'bx-crown')) }} fs-3"></i>
             </span>
          </div>
          <h4 class="fw-bold mb-0">{{ $main->name }}</h4>
          <p class="text-muted small mt-1 mb-0">{{ $main->description }}</p>
          
          {{-- Dual Pricing Display --}}
          <div class="d-flex justify-content-center gap-4 mt-3">
             @if($monthly)
             <div class="text-center">
                <div class="price-box mb-0">
                  <span class="h3 fw-extrabold text-primary mb-0">{{ number_format($monthly->price, 0) }}</span>
                </div>
                <small class="text-muted">HUF / Monthly</small>
             </div>
             @endif
             
             @if($yearly)
             <div class="text-center border-start ps-4">
                <div class="price-box mb-0">
                   <span class="h3 fw-extrabold text-info mb-0">{{ number_format($yearly->price, 0) }}</span>
                </div>
                <small class="text-muted">HUF / Yearly</small>
             </div>
             @endif
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="feature-list px-4 mt-3">
            {{-- Service Limits from Main Plan --}}
            @if($main->active_ads_limit != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $main->color }}"></i>
                <span>{{ $main->active_ads_limit == -1 ? 'Unlimited' : $main->active_ads_limit }} Active Ads</span>
              </div>
            @endif

            @if($main->garage_ads_limit != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $main->color }}"></i>
                <span>{{ $main->garage_ads_limit == -1 ? 'Unlimited' : $main->garage_ads_limit }} Garage Spaces</span>
              </div>
            @endif

            @if($main->hd_images != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $main->color }}"></i>
                <span>{{ $main->hd_images == -1 ? 'Unlimited' : $main->hd_images }} HD Images</span>
              </div>
            @endif

            {{-- Manual Features Loop --}}
            @php
              $features = is_string($main->features) ? json_decode($main->features, true) : $main->features;
            @endphp
            @if(is_array($features))
              @foreach($features as $feature)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $main->color }}"></i>
                <span>{{ $feature }}</span>
              </div>
              @endforeach
            @endif
          </div>
        </div>

        <div class="plan-actions px-4 pb-4 mt-auto">
          @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-subscriptions', 'admin-guard'))
          <div class="d-grid gap-2">
            @if($monthly)
            <div class="d-flex gap-2 align-items-center">
              <a href="{{ route('app-subscription-plan-edit', $monthly->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                Edit Monthly
              </a>
              <button class="btn btn-sm btn-label-{{ $monthly->is_active ? 'secondary' : 'success' }} toggle-plan-status" data-id="{{ $monthly->id }}">
                <i class="bx bx-power-off"></i>
              </button>
            </div>
            @endif

            @if($yearly)
            <div class="d-flex gap-2 align-items-center">
              <a href="{{ route('app-subscription-plan-edit', $yearly->id) }}" class="btn btn-sm btn-outline-info flex-grow-1">
                Edit Yearly
              </a>
              <button class="btn btn-sm btn-label-{{ $yearly->is_active ? 'secondary' : 'success' }} toggle-plan-status" data-id="{{ $yearly->id }}">
                <i class="bx bx-power-off"></i>
              </button>
            </div>
            @endif

            <button class="btn btn-sm btn-label-danger w-100 mt-1 delete-plan" data-id="{{ $main->id }}">
               <i class="bx bx-trash me-1"></i> Delete Package Card
            </button>
          </div>
          @endif
          
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

