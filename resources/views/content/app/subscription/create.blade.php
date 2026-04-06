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
                        <select name="billing_period" id="billing_period" class="form-select">
                            <option value="monthly" {{ (isset($plan) && $plan->billing_period == 'monthly') ? 'selected' : '' }}>Monthly Only</option>
                            <option value="yearly" {{ (isset($plan) && $plan->billing_period == 'yearly') ? 'selected' : '' }}>Yearly Only</option>
                            <option value="both" {{ (isset($plan) && $plan->billing_period == 'both') ? 'selected' : '' }}>Both (Create Two Separate Packages)</option>
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
        {{-- SECTION 2: Package Services & Features      --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="card section-card mb-4" id="package-config-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="section-icon bg-label-success">
                        <i class="bx bx-package"></i>
                    </div>
                    <h6 class="mb-0">Package Configurations</h6>
                </div>
                <div id="mode-badge">
                    <span class="badge bg-label-primary">Single Package Mode</span>
                </div>
            </div>
            <div class="card-body pt-4">
                {{-- Dual Mode Info Alert --}}
                <div id="dual-mode-alert" class="alert alert-label-info d-flex align-items-center mb-4" style="display: none !important;">
                    <i class="bx bx-info-circle me-2 fs-4"></i>
                    <div>
                        <strong class="d-block">Dual Creation Mode Active</strong>
                        You are configuring <span class="text-primary fw-bold">Monthly</span> and <span class="text-info fw-bold">Yearly</span> packages at once. You can set different limits and bullet points for each below.
                    </div>
                </div>

                {{-- Comparison Table for Limits --}}
                <div class="mb-5">
                    <h5 class="fw-bold mb-3"><i class="bx bx-list-check me-1"></i> Service Limits</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 40%">Limit Description</th>
                                    <th id="th-monthly" class="text-center">Monthly Package</th>
                                    <th id="th-yearly" class="text-center text-info" style="display: none;">Yearly Package</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-list-plus me-2 text-primary fs-4"></i>
                                            <div>
                                                <span class="d-block fw-semibold">Active Ads Limit</span>
                                                <small class="text-muted text-nowrap">-1 for unlimited</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="active_ads_limit" class="form-control text-center" value="{{ isset($plan) ? $plan->active_ads_limit : old('active_ads_limit', 5) }}">
                                    </td>
                                    <td class="yearly-only-col" style="display: none;">
                                        <input type="number" name="yearly_active_ads_limit" class="form-control text-center bg-label-info border-info" value="{{ old('yearly_active_ads_limit', 10) }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-car me-2 text-success fs-4"></i>
                                            <div>
                                                <span class="d-block fw-semibold">Garage Ads Limit</span>
                                                <small class="text-muted text-nowrap">-1 for unlimited</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="garage_ads_limit" class="form-control text-center" value="{{ isset($plan) ? $plan->garage_ads_limit : old('garage_ads_limit', 10) }}">
                                    </td>
                                    <td class="yearly-only-col" style="display: none;">
                                        <input type="number" name="yearly_garage_ads_limit" class="form-control text-center bg-label-info border-info" value="{{ old('yearly_garage_ads_limit', 20) }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-expand me-2 text-warning fs-4"></i>
                                            <div>
                                                <span class="d-block fw-semibold">Expandable ad slots</span>
                                                <small class="text-muted">Allow garage expansion?</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="checkbox" name="expandable_slots" class="form-check-input scale-150" value="1" {{ (isset($plan) && $plan->expandable_slots == 1) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="yearly-only-col" style="display: none;">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="checkbox" name="yearly_expandable_slots" class="form-check-input scale-150 border-info" value="1">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-star me-2 text-primary fs-4"></i>
                                            <div>
                                                <span class="d-block fw-semibold">Highlighting Limit</span>
                                                <small class="text-muted">No. of ads per month</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="highlight_ad_count" class="form-control text-center" value="{{ isset($plan) ? $plan->highlight_ad_count : old('highlight_ad_count', 0) }}">
                                    </td>
                                    <td class="yearly-only-col" style="display: none;">
                                        <input type="number" name="yearly_highlight_ad_count" class="form-control text-center bg-label-info border-info" value="{{ old('yearly_highlight_ad_count', 0) }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-camera me-2 text-danger fs-4"></i>
                                            <div>
                                                <span class="d-block fw-semibold">HD Images</span>
                                                <small class="text-muted">Direct Image Limit</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="hd_images" class="form-control text-center" value="{{ isset($plan) ? $plan->hd_images : old('hd_images', 0) }}">
                                    </td>
                                    <td class="yearly-only-col" style="display: none;">
                                        <input type="number" name="yearly_hd_images" class="form-control text-center bg-label-info border-info" value="{{ old('yearly_hd_images', 0) }}">
                                    </td>
                                </tr>

                                {{-- COMPACT HD LOGIC SECTION --}}
                                <tr class="bg-light">
                                    <td colspan="3" class="py-2 text-center small text-muted text-uppercase fw-bold ls-1">
                                        <i class="bx bx-star me-1"></i> Special HD Logic (Dynamic Marketing String)
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ms-3">
                                            <span class="d-block fw-semibold text-danger">HD Marketing Value</span>
                                            <small class="text-muted">e.g. 12 instead of 6 — usable for 2 ads</small>
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <input type="number" name="hd_images_count" class="form-control" placeholder="12" value="{{ isset($plan) ? $plan->hd_images_count : old('hd_images_count', 0) }}">
                                            <span class="input-group-text">Instead of</span>
                                            <input type="number" name="hd_images_normal_count" class="form-control" placeholder="6" value="{{ isset($plan) ? $plan->hd_images_normal_count : old('hd_images_normal_count', 0) }}">
                                            <span class="input-group-text">For</span>
                                            <input type="number" name="hd_images_ad_count" class="form-control" placeholder="2" value="{{ isset($plan) ? $plan->hd_images_ad_count : old('hd_images_ad_count', 0) }}">
                                            <span class="input-group-text">Ads</span>
                                        </div>
                                        <div class="yearly-only-col mt-2 d-flex gap-2" style="display: none;">
                                            <span class="badge bg-label-info">Yearly Yearly Logic:</span>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="yearly_hd_images_count" class="form-control border-info" placeholder="24" value="{{ old('yearly_hd_images_count', 0) }}">
                                                <span class="input-group-text border-info">Instead of</span>
                                                <input type="number" name="yearly_hd_images_normal_count" class="form-control border-info" placeholder="12" value="{{ old('yearly_hd_images_normal_count', 0) }}">
                                                <span class="input-group-text border-info">For</span>
                                                <input type="number" name="yearly_hd_images_ad_count" class="form-control border-info" placeholder="5" value="{{ old('yearly_hd_images_ad_count', 0) }}">
                                                <span class="input-group-text border-info">Ads</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Side-by-Side Features --}}
                <div class="row g-4">
                    <div id="monthly-features-col" class="col-md-12">
                        <div class="p-3 border rounded">
                            <h6 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="bx bx-check-double text-success me-2"></i> Monthly Features
                            </h6>
                            <div id="monthly-features-wrapper">
                                @php
                                    $mFeatures = (isset($plan) && is_array($plan->features)) ? $plan->features : (old('features', ['24/7 Customer Support']));
                                @endphp
                                @foreach($mFeatures as $feature)
                                    <div class="feature-item d-flex gap-2 mb-2">
                                        <input type="text" name="features[]" class="form-control" value="{{ $feature }}" placeholder="e.g. Standard Support">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-feature"><i class="bx bx-trash"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-feature-btn" data-target="monthly-features-wrapper" data-prefix="">
                                <i class="bx bx-plus me-1"></i> Add Feature
                            </button>
                        </div>
                    </div>

                    <div id="yearly-features-col" class="col-md-6" style="display: none;">
                        <div class="p-3 border border-info rounded bg-label-info bg-opacity-10">
                            <h6 class="fw-bold mb-3 d-flex align-items-center text-info">
                                <i class="bx bx-star me-2"></i> Yearly Features (Premium Add-ons)
                            </h6>
                            <div id="yearly-features-wrapper">
                                @php
                                    $yFeatures = old('yearly_features', ['Everything in Monthly', 'VIP Priority Support']);
                                @endphp
                                @foreach($yFeatures as $feature)
                                    <div class="feature-item d-flex gap-2 mb-2">
                                        <input type="text" name="yearly_features[]" class="form-control border-info" value="{{ $feature }}" placeholder="e.g. Premium Support">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-feature"><i class="bx bx-trash"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-info btn-sm mt-2 add-feature-btn" data-target="yearly-features-wrapper" data-prefix="yearly_">
                                <i class="bx bx-plus me-1"></i> Add Yearly Feature
                            </button>
                        </div>
                    </div>
                </div>

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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const billingPeriod = document.getElementById('billing_period');
    const yearlyCols = document.querySelectorAll('.yearly-only-col');
    const thYearly = document.getElementById('th-yearly');
    const thMonthly = document.getElementById('th-monthly');
    const monthlyFeaturesCol = document.getElementById('monthly-features-col');
    const yearlyFeaturesCol = document.getElementById('yearly-features-col');
    const dualModeAlert = document.getElementById('dual-mode-alert');
    const modeBadge = document.getElementById('mode-badge');

    function updateVisibility() {
        if (!billingPeriod) return;
        const val = billingPeriod.value;
        const isBoth = (val === 'both');
        
        // Handle Alerts/Badges
        if (dualModeAlert) {
            dualModeAlert.style.setProperty('display', isBoth ? 'flex' : 'none', 'important');
        }
        
        if (isBoth) {
            // BOTH MODE
            yearlyCols.forEach(col => col.style.display = 'table-cell');
            if (thYearly) thYearly.style.display = 'table-cell';
            if (thMonthly) thMonthly.innerText = 'Monthly Package';
            
            // Features Grid
            if (monthlyFeaturesCol) {
                monthlyFeaturesCol.classList.remove('col-md-12');
                monthlyFeaturesCol.classList.add('col-md-6');
            }
            if (yearlyFeaturesCol) yearlyFeaturesCol.style.display = 'block';
            
            if (modeBadge) modeBadge.innerHTML = '<span class="badge bg-label-success animated pulse infinite">Dual Creation Mode</span>';
        } else {
            // SINGLE MODE
            yearlyCols.forEach(col => col.style.display = 'none');
            if (thYearly) thYearly.style.display = 'none';
            if (yearlyFeaturesCol) yearlyFeaturesCol.style.display = 'none';
            
            if (monthlyFeaturesCol) {
                monthlyFeaturesCol.classList.remove('col-md-6');
                monthlyFeaturesCol.classList.add('col-md-12');
            }

            if (val === 'monthly') {
                if (thMonthly) thMonthly.innerText = 'Service Value';
                if (modeBadge) modeBadge.innerHTML = '<span class="badge bg-label-primary">Single Package (Monthly)</span>';
            } else if (val === 'yearly') {
                if (thMonthly) thMonthly.innerText = 'Service Value (Yearly)';
                if (modeBadge) modeBadge.innerHTML = '<span class="badge bg-label-info">Single Package (Yearly)</span>';
            }
        }
    }

    if (billingPeriod) {
        billingPeriod.addEventListener('change', updateVisibility);
        
        // Also listen for library-specific events (like Select2)
        if (typeof jQuery !== 'undefined') {
            $(billingPeriod).on('change', updateVisibility);
            $(billingPeriod).on('select2:select', updateVisibility);
        }
        
        // Initial check
        updateVisibility();
        
        // Extra safety: check every 500ms for the first 2 seconds in case of async init
        let checks = 0;
        const interval = setInterval(() => {
            updateVisibility();
            if (++checks > 4) clearInterval(interval);
        }, 500);
    }

    // Initialize Summernote for description
    $('textarea[name="description"]').summernote({
        placeholder: 'Enter plan description...',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    // Feature management
    document.addEventListener('click', function(e) {
        // Add Feature
        if (e.target.closest('.add-feature-btn')) {
            const btn = e.target.closest('.add-feature-btn');
            const targetId = btn.getAttribute('data-target');
            const prefix = btn.getAttribute('data-prefix') || '';
            const wrapper = document.getElementById(targetId);

            const div = document.createElement('div');
            div.className = "feature-item d-flex gap-2 mb-2";

            div.innerHTML = `
                <input type="text" name="${prefix}features[]" class="form-control" placeholder="e.g. Priority Support">
                <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                    <i class="bx bx-trash"></i>
                </button>
            `;

            wrapper.appendChild(div);
        }

        // Remove Feature
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });

});
</script>
@endsection
