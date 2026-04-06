{{-- 1. Active Ads --}}
@if($plan->active_ads_limit != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->active_ads_limit == -1 ? 'Unlimited' : $plan->active_ads_limit }} Active Ads</span>
    </div>
@endif

{{-- 2. Garage Slots --}}
@if($plan->garage_ads_limit != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->garage_ads_limit == -1 ? 'Unlimited' : $plan->garage_ads_limit }} garage slots</span>
    </div>
@endif

{{-- 3. Expandable Slots --}}
@if($plan->expandable_slots > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>Expandable active ad slots</span>
    </div>
@endif

{{-- 4. Dynamic HD Logic (Priority) --}}
@if($plan->hd_images_count > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>HD images, {{ $plan->hd_images_count }} instead of {{ $plan->hd_images_normal_count }} — usable for {{ $plan->hd_images_ad_count }} {{ Str::plural('ad', $plan->hd_images_ad_count) }} per month</span>
    </div>
@elseif($plan->hd_images != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->hd_images == -1 ? 'Unlimited' : $plan->hd_images }} HD Images</span>
    </div>
@endif

{{-- 5. Highlighting Logic --}}
@if($plan->highlight_ad_count > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>Highlighting — usable for {{ $plan->highlight_ad_count }} {{ Str::plural('ad', $plan->highlight_ad_count) }} per month</span>
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
