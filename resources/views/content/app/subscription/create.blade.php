@extends('layouts/contentNavbarLayout')

@section('title', isset($plan) ? 'Edit Subscription' : 'Create Subscription')

@section('page-style')
<style>
.section-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
}
.section-card .card-header {
    background: linear-gradient(135deg, #f8f9ff 0%, #f1f3f9 100%);
    border-bottom: 2px solid #e9ecef;
    padding: 1rem 1.5rem;
}
.section-card .card-header h6 {
    font-weight: 700;
    letter-spacing: 0.3px;
    color: #566a7f;
    text-transform: uppercase;
    font-size: 0.8rem;
}
.section-card .card-header .section-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.service-toggle-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    transition: all 0.2s ease;
    background: #fafbfc;
}
.service-toggle-card:hover {
    border-color: #696cff;
    background: #f8f8ff;
}
.service-toggle-card .form-check-input:checked ~ .service-label {
    color: #696cff;
}
.timestamp-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 500;
}
</style>
@endsection

@section('content')

<form method="POST" action="{{ isset($plan) ? route('app-subscription-plan-update', $plan->id) : route('app-subscription-store') }}">
@csrf
@if(isset($plan))
    @method('PUT')
@endif

<div class="row">

    {{-- LEFT SIDE --}}
    <div class="col-lg-8">

        {{-- Page Heading --}}
        <div class="mb-4">
            <h4 class="fw-bold">{{ isset($plan) ? 'Edit Subscription' : 'Create Subscription' }}</h4>
            <p class="text-muted mb-0">{{ isset($plan) ? 'Update the subscription plan details' : 'Set up a new subscription plan for your customers' }}</p>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SECTION 1: General Information              --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="card section-card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <div class="section-icon bg-label-primary">
                    <i class="bx bx-info-circle"></i>
                </div>
                <h6 class="mb-0">General Information</h6>
            </div>
            <div class="card-body pt-4">

                {{-- Plan Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Plan Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="title"
                           class="form-control form-control-lg"
                           placeholder="e.g. Starter, Professional, Enterprise"
                           value="{{ isset($plan) ? $plan->name : old('title') }}"
                           required>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="2"
                              placeholder="Short description of this plan">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
                </div>

                <hr class="my-3">

                {{-- Pricing --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Monthly Price (HUF)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-money"></i></span>
                            <input type="number" name="monthly_price" class="form-control"
                                   value="{{ isset($plan) ? $plan->price : old('monthly_price', 0) }}"
                                   step="0.01">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Yearly Price (HUF)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-money"></i></span>
                            <input type="number" name="yearly_price" class="form-control"
                                   value="{{ isset($plan) ? $plan->yearly_price : old('yearly_price', 0) }}"
                                   step="0.01">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Billing Cycle</label>
                        <select name="billing_period" class="form-select">
                            <option value="monthly" {{ (isset($plan) && $plan->billing_period == 'yearly') ? '' : 'selected' }}>Monthly</option>
                            <option value="yearly" {{ (isset($plan) && $plan->billing_period == 'yearly') ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Theme Color</label>
                        <select name="color" class="form-select">
                            @php
                                $colors = ['primary' => 'Primary (Blue)', 'success' => 'Success (Green)', 'warning' => 'Warning (Gold)', 'info' => 'Info (Cyan)', 'secondary' => 'Secondary (Grey)'];
                                $currentColor = isset($plan) ? $plan->color : 'primary';
                            @endphp
                            @foreach($colors as $value => $label)
                                <option value="{{ $value }}" {{ $currentColor == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Stripe Integration --}}
                <label class="form-label fw-semibold text-muted small">
                    <i class="bx bxl-stripe me-1"></i> Stripe Integration (Optional)
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="stripe_price_id_monthly" class="form-control form-control-sm"
                               placeholder="Stripe Monthly Price ID"
                               value="{{ isset($plan) ? $plan->stripe_price_id_monthly : old('stripe_price_id_monthly') }}">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="stripe_price_id_yearly" class="form-control form-control-sm"
                               placeholder="Stripe Yearly Price ID"
                               value="{{ isset($plan) ? $plan->stripe_price_id_yearly : old('stripe_price_id_yearly') }}">
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SECTION 2: Package Services                 --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="card section-card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <div class="section-icon bg-label-success">
                    <i class="bx bx-package"></i>
                </div>
                <h6 class="mb-0">Package Services</h6>
            </div>
            <div class="card-body pt-4">

                {{-- Service Limits --}}
                <label class="form-label fw-semibold mb-3">Service Limits</label>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="service-toggle-card text-center">
                            <i class="bx bx-list-plus text-primary fs-3 mb-2"></i>
                            <label class="form-label fw-semibold d-block">Active Ads Limit</label>
                            <input type="number" name="active_ads_limit" class="form-control text-center"
                                   value="{{ isset($plan) ? $plan->active_ads_limit : old('active_ads_limit', 5) }}"
                                   placeholder="-1 = unlimited">
                            <small class="text-muted">-1 for unlimited</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-toggle-card text-center">
                            <i class="bx bx-car text-success fs-3 mb-2"></i>
                            <label class="form-label fw-semibold d-block">Garage Ads Limit</label>
                            <input type="number" name="garage_ads_limit" class="form-control text-center"
                                   value="{{ isset($plan) ? $plan->garage_ads_limit : old('garage_ads_limit', 10) }}"
                                   placeholder="-1 = unlimited">
                            <small class="text-muted">-1 for unlimited</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-toggle-card text-center">
                            <i class="bx bx-expand text-warning fs-3 mb-2"></i>
                            <label class="form-label fw-semibold d-block">Expandable Slots</label>
                            <input type="number" name="expandable_slots" class="form-control text-center"
                                   value="{{ isset($plan) ? $plan->expandable_slots : old('expandable_slots', 0) }}">
                        </div>
                    </div>
                </div>



                {{-- Plan Features (text list) --}}
                <label class="form-label fw-semibold mb-2">
                    <i class="bx bx-check-double text-success me-1"></i> Plan Features (Display List)
                </label>
                <p class="text-muted small mb-3">These features will be displayed as bullet points on the pricing card.</p>

                <div id="features-wrapper">
                    @if(isset($plan) && is_array($plan->features))
                        @foreach($plan->features as $feature)
                        <div class="feature-item d-flex gap-2 mb-2">
                            <input type="text" name="features[]" class="form-control" value="{{ $feature }}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="feature-item d-flex gap-2 mb-2">
                            <input type="text" name="features[]" class="form-control" placeholder="e.g. 24/7 Customer Support">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-feature">
                    <i class="bx bx-plus me-1"></i> Add Feature
                </button>

            </div>
        </div>

    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-lg-4">

        {{-- Publish --}}
        <div class="card section-card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <div class="section-icon bg-label-warning">
                    <i class="bx bx-send"></i>
                </div>
                <h6 class="mb-0">Publish</h6>
            </div>
            <div class="card-body">

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bx bx-check-circle me-1"></i>
                    {{ isset($plan) ? 'Update Plan' : 'Publish Plan' }}
                </button>

                <a href="{{ route('app-subscription-plans') }}" class="btn btn-outline-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Back to Plans
                </a>

            </div>
        </div>

        {{-- Timestamps --}}
        <div class="card section-card mb-4">
            <div class="card-header d-flex align-items-center gap-3">
                <div class="section-icon bg-label-info">
                    <i class="bx bx-time-five"></i>
                </div>
                <h6 class="mb-0">Timestamps</h6>
            </div>
            <div class="card-body">
                @if(isset($plan))
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Created At</small>
                        <span class="timestamp-badge bg-label-success">
                            <i class="bx bx-calendar"></i>
                            {{ $plan->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Last Updated</small>
                        <span class="timestamp-badge bg-label-info">
                            <i class="bx bx-edit"></i>
                            {{ $plan->updated_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                @else
                    <div class="text-center py-2">
                        <i class="bx bx-time-five text-muted fs-1 d-block mb-2"></i>
                        <small class="text-muted">Timestamps will appear after creation</small>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

</form>

@endsection


@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const addBtn = document.getElementById('add-feature');
    const wrapper = document.getElementById('features-wrapper');

    addBtn.addEventListener('click', function () {

        const div = document.createElement('div');
        div.className = "feature-item d-flex gap-2 mb-2";

        div.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="e.g. Priority Support">
            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                <i class="bx bx-trash"></i>
            </button>
        `;

        wrapper.appendChild(div);
    });

    wrapper.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });

});
</script>
@endsection
