{{-- Service Limits --}}
<label class="form-label fw-semibold mb-3">Service Limits</label>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="service-toggle-card text-center">
            <i class="bx bx-list-plus text-primary fs-3 mb-2"></i>
            <label class="form-label fw-semibold d-block">Active Ads Limit</label>
            <input type="number" name="{{ $prefix }}active_ads_limit" class="form-control text-center"
                   value="{{ isset($plan) ? ($type == 'yearly' ? $plan->active_ads_limit : $plan->active_ads_limit) : old($prefix . 'active_ads_limit', 5) }}"
                   placeholder="-1 = unlimited">
            <small class="text-muted">-1 for unlimited</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="service-toggle-card text-center">
            <i class="bx bx-car text-success fs-3 mb-2"></i>
            <label class="form-label fw-semibold d-block">Garage Ads Limit</label>
            <input type="number" name="{{ $prefix }}garage_ads_limit" class="form-control text-center"
                   value="{{ isset($plan) ? ($type == 'yearly' ? $plan->garage_ads_limit : $plan->garage_ads_limit) : old($prefix . 'garage_ads_limit', 10) }}"
                   placeholder="-1 = unlimited">
            <small class="text-muted">-1 for unlimited</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="service-toggle-card text-center">
            <i class="bx bx-expand text-warning fs-3 mb-2"></i>
            <label class="form-label fw-semibold d-block">Expandable Slots</label>
            <input type="number" name="{{ $prefix }}expandable_slots" class="form-control text-center"
                   value="{{ isset($plan) ? ($type == 'yearly' ? $plan->expandable_slots : $plan->expandable_slots) : old($prefix . 'expandable_slots', 0) }}">
        </div>
    </div>
</div>

{{-- Plan Features (text list) --}}
<label class="form-label fw-semibold mb-2">
    <i class="bx bx-check-double {{ $type == 'yearly' ? 'text-primary' : 'text-success' }} me-1"></i> Plan Features (Display List)
</label>
<p class="text-muted small mb-3">These features will be displayed as bullet points on the pricing card.</p>

<div id="{{ $type }}-features-wrapper">
    @php
        $features = [];
        if(isset($plan) && is_array($plan->features)) {
            $features = $plan->features;
        } elseif(old($prefix . 'features')) {
            $features = old($prefix . 'features');
        }
    @endphp

    @if(count($features) > 0)
        @foreach($features as $feature)
        <div class="feature-item d-flex gap-2 mb-2">
            <input type="text" name="{{ $prefix }}features[]" class="form-control" value="{{ $feature }}">
            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                <i class="bx bx-trash"></i>
            </button>
        </div>
        @endforeach
    @else
        <div class="feature-item d-flex gap-2 mb-2">
            <input type="text" name="{{ $prefix }}features[]" class="form-control" placeholder="e.g. 24/7 Customer Support">
            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    @endif
</div>

<button type="button" class="btn btn-outline-primary btn-sm mt-2 add-feature-btn" 
        data-target="{{ $type }}-features-wrapper" 
        data-prefix="{{ $prefix }}">
    <i class="bx bx-plus me-1"></i> Add Feature
</button>
