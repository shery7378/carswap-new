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
            <a href="javascript:void(0);" class="btn btn-outline-{{ $plan->color }} flex-grow-1 edit-plan-btn" data-id="{{ $plan->id }}">
              <i class="bx bx-edit-alt small me-1"></i> Edit
            </a>
            <button class="btn btn-label-{{ $plan->is_active ? 'secondary' : 'success' }} p-2 toggle-plan-status" data-id="{{ $plan->id }}" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
              <i class="bx bx-power-off"></i>
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
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Plan Edit Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-label-primary">
                <h5 class="modal-title">Edit Plan: <span id="modalPlanName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPlanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Plan Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Slug (URL Key)</label>
                            <input type="text" name="slug" id="edit_slug" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Price (HUF)</label>
                            <input type="number" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Yearly Price (HUF)</label>
                            <input type="number" name="yearly_price" id="edit_yearly_price" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Billing Cycle</label>
                            <select name="billing_period" id="edit_billing_period" class="form-select">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Active Ads Limit</label>
                            <input type="number" name="active_ads_limit" id="edit_active_limit" class="form-control" placeholder="-1 for unlimited">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Garage Limit</label>
                            <input type="number" name="garage_ads_limit" id="edit_garage_limit" class="form-control" placeholder="-1 for unlimited">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Expand Slots</label>
                            <input type="number" name="expandable_slots" id="edit_expandable_slots" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_popular" id="edit_is_popular" value="1">
                                <label class="form-check-label fw-bold">Popular Badge</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="highlight_ads" id="edit_highlight_ads" value="1">
                                <label class="form-check-label fw-bold">Highlight Ads</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="hd_images" id="edit_hd_images" value="1">
                                <label class="form-check-label fw-bold">HD Images</label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Stripe Monthly ID</label>
                            <input type="text" name="stripe_price_id_monthly" id="edit_stripe_monthly" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Stripe Yearly ID</label>
                            <input type="text" name="stripe_price_id_yearly" id="edit_stripe_yearly" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary shadow-sm px-4">Update Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
const plansData = @json($plans->keyBy('id'));

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

// Edit Plan Modal Logic
$(document).on('click', '.edit-plan-btn', function() {
    const planId = $(this).data('id');
    const plan = plansData[planId];
    
    if (!plan) {
        console.error('Plan not found for ID:', planId);
        return;
    }
    
    const form = $('#editPlanForm');
    
    // Set form action
    form.attr('action', `/app/subscription/plans/${plan.id}/update`);
    
    // Fill fields
    $('#modalPlanName').text(plan.name);
    $('#edit_name').val(plan.name);
    $('#edit_slug').val(plan.slug);
    $('#edit_price').val(plan.price ? parseFloat(plan.price) : 0);
    $('#edit_yearly_price').val(plan.yearly_price ? parseFloat(plan.yearly_price) : '');
    $('#edit_description').val(plan.description);
    $('#edit_billing_period').val(plan.billing_period || 'monthly');
    
    // Limits
    $('#edit_active_limit').val(plan.active_ads_limit);
    $('#edit_garage_limit').val(plan.garage_ads_limit);
    $('#edit_expandable_slots').val(plan.expandable_slots);
    
    // Toggles
    $('#edit_is_popular').prop('checked', !!plan.is_popular);
    $('#edit_highlight_ads').prop('checked', !!plan.highlight_ads);
    $('#edit_hd_images').prop('checked', !!plan.hd_images);
    
    // Stripe IDs
    $('#edit_stripe_monthly').val(plan.stripe_price_id_monthly);
    $('#edit_stripe_yearly').val(plan.stripe_price_id_yearly);
    
    // Show modal
    var myModal = new bootstrap.Modal(document.getElementById('editPlanModal'));
    myModal.show();
});
</script>
@endsection
