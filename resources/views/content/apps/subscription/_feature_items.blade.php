{{-- Service Limits from Plan --}}
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

@if($plan->hd_images != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->hd_images == -1 ? 'Unlimited' : $plan->hd_images }} HD Images</span>
    </div>
@endif

@if($plan->expandable_slots > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->expandable_slots }} Expandable Slots</span>
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
