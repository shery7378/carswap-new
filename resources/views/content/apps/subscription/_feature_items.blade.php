{{-- 1. Active Ads --}}
@if($plan->active_ads_limit != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->active_ads_limit == -1 ? __('Unlimited') : $plan->active_ads_limit }} {{ __('Active Ads') }}</span>
    </div>
@endif

{{-- 2. Garage Slots --}}
@if($plan->garage_ads_limit != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->garage_ads_limit == -1 ? __('Unlimited') : $plan->garage_ads_limit }} {{ __('garage slots') }}</span>
    </div>
@endif

{{-- 3. Expandable Slots --}}
@if($plan->expandable_slots > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ __('Expandable active ad slots') }}</span>
    </div>
@endif

{{-- 4. Dynamic HD Logic (Priority) --}}
@if($plan->hd_images_count > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ __('HD images, :count instead of :normal — usable for :adcount :ad per month', ['count' => $plan->hd_images_count, 'normal' => $plan->hd_images_normal_count, 'adcount' => $plan->hd_images_ad_count, 'ad' => __($plan->hd_images_ad_count > 1 ? 'ads' : 'ad')]) }}</span>
    </div>
@elseif($plan->hd_images != 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ $plan->hd_images == -1 ? __('Unlimited') : $plan->hd_images }} {{ __('HD Images') }}</span>
    </div>
@endif

{{-- 5. Highlighting Logic --}}
@if($plan->highlight_ad_count > 0)
    <div class="feature-item">
    <i class="bx bx-check-circle text-{{ $plan->color }}"></i>
    <span>{{ __('Highlighting — usable for :adcount :ad per month', ['adcount' => $plan->highlight_ad_count, 'ad' => __($plan->highlight_ad_count > 1 ? 'ads' : 'ad')]) }}</span>
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
