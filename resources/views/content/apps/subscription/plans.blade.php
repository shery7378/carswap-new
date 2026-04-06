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
    @foreach($plans as $plan)
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card pricing-card h-100 border-{{ $plan->color }}">
        <div class="pricing-header text-center">
          @if($plan->is_popular)
          <span class="badge bg-{{ $plan->color }} popular-badge rounded-pill shadow-sm">Popular Choice</span>
          @endif
          <div class="avatar avatar-md mx-auto mb-3">
             <span class="avatar-initial rounded-circle bg-label-{{ $plan->color }}">
                <i class="bx {{ $plan->slug === 'free' ? 'bx-gift' : ($plan->slug === 'partner-package' ? 'bx-buildings' : ($plan->slug === 'several-cars' ? 'bx-car' : 'bx-crown')) }} fs-3"></i>
             </span>
          </div>
          <h4 class="fw-bold mb-0">{{ $plan->name }}</h4>
          <p class="text-muted small mt-1 mb-0">{{ $plan->description }}</p>
          <div class="price-box">
            <span class="h2 fw-extrabold text-{{ $plan->color }} mb-0">{{ number_format($plan->price, 0) }}</span>
            <span class="ms-1 text-muted fw-medium">HUF</span>
            <span class="ms-1 text-muted small">/{{ $plan->billing_period }}</span>
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="feature-list px-4 mt-3">
            {{-- Service Limits (Auto-generated from columns) --}}
            @if($plan->active_ads_limit != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>{{ $plan->active_ads_limit == -1 ? 'Unlimited' : $plan->active_ads_limit }} Active Ads</span>
              </div>
            @endif

            @if($plan->garage_ads_limit != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>{{ $plan->garage_ads_limit == -1 ? 'Unlimited' : $plan->garage_ads_limit }} Garage Spaces</span>
              </div>
            @endif

            @if($plan->expandable_slots > 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>{{ $plan->expandable_slots }} Expandable Slots</span>
              </div>
            @endif

            @if($plan->hd_images != 0)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>{{ $plan->hd_images == -1 ? 'Unlimited' : $plan->hd_images }} HD Images</span>
              </div>
            @endif

            @if($plan->highlight_ads)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>Highlight Ads Included</span>
              </div>
            @endif

            {{-- Manual Features Loop --}}
            @php
              $features = is_string($plan->features) ? json_decode($plan->features, true) : $plan->features;
            @endphp
            @if(is_array($features))
              @foreach($features as $feature)
              <div class="feature-item">
                <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
                <span>{{ $feature }}</span>
              </div>
              @endforeach
            @endif
          </div>
        </div>

        <div class="plan-actions px-4 pb-4 mt-auto">
          @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-subscriptions', 'admin-guard'))
          <div class="d-flex gap-2">
            <a href="{{ route('app-subscription-plan-edit', $plan->id) }}" class="btn btn-outline-{{ $plan->color }} flex-grow-1">
              <i class="bx bx-edit-alt small me-1"></i> Edit
            </a>
            <button class="btn btn-label-{{ $plan->is_active ? 'secondary' : 'success' }} p-2 toggle-plan-status" data-id="{{ $plan->id }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
              <i class="bx bx-power-off"></i>
            </button>
            <button class="btn btn-label-danger p-2 delete-plan" data-id="{{ $plan->id }}" title="Delete Package">
              <i class="bx bx-trash"></i>
            </button>
          </div>
          @endif
          <div class="mt-3 text-center">
            @if($plan->is_active)
              <span class="badge bg-label-success small"><i class="bx bxs-circle me-1" style="font-size: 6px;"></i> Live Package</span>
            @else
              <span class="badge bg-label-secondary small">Disabled</span>
            @endif
          </div>
          <div class="mt-2 text-center">
            <small class="text-muted d-block" style="font-size: 0.7rem;">
              <i class="bx bx-calendar me-1"></i>Created: {{ $plan->created_at->format('M d, Y') }}
            </small>
            @if($plan->updated_at && $plan->updated_at->ne($plan->created_at))
            <small class="text-muted d-block" style="font-size: 0.7rem;">
              <i class="bx bx-edit me-1"></i>Updated: {{ $plan->updated_at->format('M d, Y h:i A') }}
            </small>
            @endif
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

