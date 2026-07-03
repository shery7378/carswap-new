<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
             <span class="avatar-initial rounded-circle bg-label-primary border shadow-xs">
                {{ strtoupper(substr($vehicle->user?->first_name ?: ($vehicle->user?->last_name ?: ($vehicle->user?->email ?: 'U')), 0, 1)) }}
            </span>
        </div>
        <div class="pe-3 overflow-hidden">
            <div class="d-flex align-items-center gap-2">
                <h5 class="modal-title fw-bold mb-0 text-dark text-truncate">{{ $vehicle->title }}</h5>
                <button type="button" 
                    class="btn btn-icon btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn" 
                    data-id="{{ $vehicle->id }}" 
                    data-bs-toggle="tooltip" 
                    title="{{ $vehicle->is_featured ? __('Remove from Featured') : __('Mark as Featured') }}">
                    <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                </button>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-x-2 small mt-1">
                <span class="text-muted"><i class="bx bx-map-pin me-1 text-primary"></i>{{ $vehicle->location ?: __('N/A') }}</span>
                <span class="badge badge-center rounded-pill bg-label-secondary w-px-2 h-px-2 mx-1"></span>
                <span class="text-muted"><i class="bx bx-calendar me-1"></i>{{ $vehicle->year }}</span>
                <span class="badge badge-center rounded-pill bg-label-secondary w-px-2 h-px-2 mx-1"></span>
                <span class="text-primary fw-bold"><i class="bx bx-user me-1 text-primary"></i>{{ $vehicle->user ? $vehicle->user->first_name . ' ' . $vehicle->user->last_name : __('System') }}</span>
            </div>
        </div>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar Summary -->
        <div class="col-md-5 border-end bg-light p-4">
            <div class="text-center mb-4 position-relative">
                <div class="main-img-container rounded border shadow-sm p-1 bg-white mb-2 overflow-hidden position-relative">
                    @php
                    $mainImageSrc = null;
                    if ($vehicle->main_image) {
                        $mainImageSrc = preg_match('/^https?:\/\//', $vehicle->main_image)
                            ? $vehicle->main_image
                            : asset('storage/' . $vehicle->main_image);
                    }
                    @endphp
                    @if($mainImageSrc)
                        <img id="modal-vehicle-main-image" src="{{ $mainImageSrc }}" class="img-fluid rounded" style="max-height: 280px; width: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/800x600?text=No+Main+Image';">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow-lg fs-6 py-2 px-3">
                            @formatCurrency($vehicle->price)
                        </span>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                            <i class="bx bx-image text-muted display-4"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Gallery Thumbs -->
                @php
                    $allImages = [];
                    if ($mainImageSrc) {
                        $allImages[] = $mainImageSrc;
                    }
                    $galleryImages = $vehicle->gallery_images;
                    if (is_string($galleryImages)) {
                        $galleryImages = json_decode($galleryImages, true);
                    }
                    if (is_array($galleryImages)) {
                        foreach ($galleryImages as $path) {
                            $allImages[] = preg_match('/^https?:\/\//', $path) ? $path : asset('storage/' . $path);
                        }
                    }
                @endphp
                @if(count($allImages) > 0)
                    <div class="gallery-scroller d-flex flex-wrap gap-2 pb-2 mt-2" style="max-width: 100%;">
                        @foreach($allImages as $img)
                            <img src="{{ $img }}" class="rounded border shadow-xs flex-shrink-0" style="width: 65px; height: 65px; cursor: pointer; object-fit: cover; transition: border-color 0.2s;" onclick="document.getElementById('modal-vehicle-main-image').src=this.src;" onmouseover="this.classList.add('border-primary', 'border-2')" onmouseout="this.classList.remove('border-primary', 'border-2')">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="info-sidebar-section mb-4">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted border-bottom pb-1">{{ __('Listing Overview') }}</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-label-info px-3 py-2"><i class="bx bx-tachometer me-1"></i> {{ number_format($vehicle->mileage) }} km</span>
                    <span class="badge bg-label-success px-3 py-2"><i class="bx bx-cog me-1"></i> {{ optional($vehicle->transmission)->name ?: __('N/A') }}</span>
                    <span class="badge bg-label-secondary px-3 py-2"><i class="bx bx-gas-pump me-1"></i> {{ optional($vehicle->fuelType)->name ?: __('N/A') }}</span>
                </div>
            </div>

            <div class="seller-card p-3 bg-white rounded border shadow-xs border-top border-3 border-info">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted border-bottom pb-1">{{ __('Seller Details') }}</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-info shadow-xs border">{{ strtoupper(substr($vehicle->user?->first_name ?: ($vehicle->user?->last_name ?: ($vehicle->user?->email ?: 'U')), 0, 1)) }}</span>
                    </div>
                    <div class="text-truncate">
                        <p class="mb-0 fw-bold text-dark small text-truncate">
                            @if($vehicle->user)
                                {{ trim($vehicle->user->first_name . ' ' . $vehicle->user->last_name) ?: __('Internal User') }}
                            @else
                                {{ __('Guest User') }}
                            @endif
                        </p>
                        <p class="mb-0 text-muted smaller text-truncate">{{ $vehicle->user->email ?? __('No email set') }}</p>
                    </div>
                </div>
                @if($vehicle->user?->phone)
                    <div class="mt-2 small d-flex align-items-center">
                        <i class="bx bx-phone text-success me-2"></i> <span class="fw-semibold text-dark">{{ $vehicle->user->phone }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Tabs -->
        <div class="col-md-7">
            <div class="nav-align-top h-100">
                <ul class="nav nav-tabs nav-fill rounded-0 border-bottom" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-overview">
                            {{ __('Vehicle Info') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-tech">
                            {{ __('Technical') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-props">
                            {{ __('Features') }} ({{ $vehicle->properties->count() }})
                        </button>
                    </li>
                </ul>
                <div class="tab-content border-0 shadow-none bg-transparent p-4 custom-scrollbar" style="max-height: 480px; overflow-y: auto;">
                    <!-- Overview -->
                    <div class="tab-pane fade show active" id="v-modal-overview" role="tabpanel">
                        <div class="row g-3 mb-4">
                            <!-- Mileage -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-primary border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Mileage') }}</small>
                                    <span class="fw-bold fs-5">{{ number_format($vehicle->mileage ?? 0, 0, ',', ' ') }} <small class="fw-normal">km</small></span>
                                </div>
                            </div>
                            <!-- Fuel Type -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-info border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Fuel Type') }}</small>
                                    <span class="fw-bold fs-5 text-truncate d-block">{{ optional($vehicle->fuelType)->name ?? __('N/A') }}</span>
                                </div>
                            </div>
                            <!-- Transmission -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-success border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Transmission') }}</small>
                                    <span class="fw-bold fs-6 d-block lh-sm overflow-hidden" style="max-height: 44px;">{{ optional($vehicle->transmission)->name ?? __('N/A') }}</span>
                                </div>
                            </div>
                            <!-- Power -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-warning border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Power') }}</small>
                                    <span class="fw-bold fs-5">{{ $vehicle->performance ?? __('N/A') }} <small class="fw-normal">kW</small></span>
                                </div>
                            </div>
                            <!-- Status -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-secondary border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Status') }}</small>
                                    <span class="badge bg-label-{{ $vehicle->ad_status == 'published' ? 'success' : 'warning' }} mt-1">{{ __(ucfirst($vehicle->ad_status)) }}</span>
                                </div>
                            </div>
                            <!-- Location -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-danger border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">{{ __('Location') }}</small>
                                    <span class="fw-bold fs-6 d-block text-truncate">{{ $vehicle->location ?: __('N/A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                                <i class="bx bx-info-circle me-2"></i> {{ __('Quick Overview') }}
                            </h6>
                            <div class="bg-light p-3 rounded border text-muted small lh-base">
                                {{ __('This') }} {{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }} {{ __('is a') }} {{ $vehicle->year }} {{ __('model with') }} {{ number_format($vehicle->mileage) }} km. 
                                {{ __('Currently listed as') }} <strong>{{ __($vehicle->ad_status) }}</strong> {{ __('in') }} {{ $vehicle->location ?: __('Not specified') }}.
                            </div>
                        </div>

                        <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                            <i class="bx bx-text me-2"></i> {{ __('Description') }}
                        </h6>
                        <div class="lh-base text-dark bg-light p-3 rounded border" style="font-size: 0.9rem;">
                            {!! $vehicle->description ? nl2br(e(Str::limit($vehicle->description, 500))) : '<em>' . __('No detailed description provided for this vehicle.') . '</em>' !!}
                        </div>
                    </div>

                    <!-- Tech -->
                    <div class="tab-pane fade" id="v-modal-tech" role="tabpanel">
                        <table class="table table-sm table-hover border">
                            <thead class="table-light">
                                <tr><th class="py-2 px-3 small uppercase">{{ __('Property') }}</th><th class="py-2 px-3 small uppercase">{{ __('Value') }}</th></tr>
                            </thead>
                            <tbody>
                                <tr><td class="text-muted px-3 py-2">VIN / Alvázszám</td><td class="fw-bold px-3 py-2">{{ $vehicle->vin_number ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Cylinder Capacity') }}</td><td class="fw-bold px-3 py-2">{{ $vehicle->cylinder_capacity ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Battery Capacity') }}</td><td class="fw-bold px-3 py-2">{{ $vehicle->battery_capacity ? $vehicle->battery_capacity . ' kWh' : __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Range') }}</td><td class="fw-bold px-3 py-2">{{ $vehicle->range ? $vehicle->range . ' km' : __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Main Color') }}</td><td class="fw-bold px-3 py-2">{{ optional($vehicle->exteriorColor)->name ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Tech Validation') }}</td><td class="fw-bold text-danger px-3 py-2">{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->formatDate() : __('N/A') }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">{{ __('Location') }}</td><td class="fw-bold px-3 py-2">{{ $vehicle->location ?: __('N/A') }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Props -->
                    <div class="tab-pane fade" id="v-modal-props" role="tabpanel">
                        @if($vehicle->properties && $vehicle->properties->count() > 0)
                            @php
                                $grouped = $vehicle->properties->groupBy('property_category_id');
                                $leftCol = collect();
                                $rightCol = collect();
                                $index = 0;
                                foreach($grouped as $categoryId => $categoryProps) {
                                    if ($index % 2 == 0) {
                                        $leftCol->put($categoryId, $categoryProps);
                                    } else {
                                        $rightCol->put($categoryId, $categoryProps);
                                    }
                                    $index++;
                                }
                            @endphp
                            <div class="row">
                                <!-- Left Column Accordion -->
                                <div class="col-md-6">
                                    <div class="accordion" id="accordionModalFeaturesLeft">
                                        @foreach($leftCol as $categoryId => $categoryProps)
                                            @php
                                                $category = $categoryProps->first()->category;
                                                $categoryName = $category ? $category->name : __('Other / Uncategorized');
                                                $accordionId = 'modal_feature_category_' . ($categoryId ?? 'other');
                                            @endphp
                                            <div class="card accordion-item border-0 mb-3 shadow-xs rounded overflow-hidden">
                                                <h2 class="accordion-header" id="heading_{{ $accordionId }}">
                                                    <button class="accordion-button collapsed fw-bold bg-light py-3 d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $accordionId }}" aria-expanded="false" aria-controls="collapse_{{ $accordionId }}">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-category me-2 text-primary"></i> 
                                                            <span>{{ $categoryName }}</span>
                                                        </div>
                                                        <span class="badge bg-label-primary rounded-pill ms-3">{{ $categoryProps->count() }}</span>
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $accordionId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $accordionId }}" data-bs-parent="#accordionModalFeaturesLeft">
                                                    <div class="accordion-body bg-white border-top pt-4 pb-2">
                                                        <div class="row">
                                                            @foreach($categoryProps as $prop)
                                                                <div class="col-sm-6 mb-3">
                                                                    <div class="d-flex align-items-start">
                                                                        <i class="bx bxs-check-shield text-success me-2 fs-5 mt-1 flex-shrink-0"></i> 
                                                                        <span class="text-dark">{{ $prop->name }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Right Column Accordion -->
                                <div class="col-md-6">
                                    <div class="accordion" id="accordionModalFeaturesRight">
                                        @foreach($rightCol as $categoryId => $categoryProps)
                                            @php
                                                $category = $categoryProps->first()->category;
                                                $categoryName = $category ? $category->name : __('Other / Uncategorized');
                                                $accordionId = 'modal_feature_category_' . ($categoryId ?? 'other');
                                            @endphp
                                            <div class="card accordion-item border-0 mb-3 shadow-xs rounded overflow-hidden">
                                                <h2 class="accordion-header" id="heading_{{ $accordionId }}">
                                                    <button class="accordion-button collapsed fw-bold bg-light py-3 d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $accordionId }}" aria-expanded="false" aria-controls="collapse_{{ $accordionId }}">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-category me-2 text-primary"></i> 
                                                            <span>{{ $categoryName }}</span>
                                                        </div>
                                                        <span class="badge bg-label-primary rounded-pill ms-3">{{ $categoryProps->count() }}</span>
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $accordionId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $accordionId }}" data-bs-parent="#accordionModalFeaturesRight">
                                                    <div class="accordion-body bg-white border-top pt-4 pb-2">
                                                        <div class="row">
                                                            @foreach($categoryProps as $prop)
                                                                <div class="col-sm-6 mb-3">
                                                                    <div class="d-flex align-items-start">
                                                                        <i class="bx bxs-check-shield text-success me-2 fs-5 mt-1 flex-shrink-0"></i> 
                                                                        <span class="text-dark">{{ $prop->name }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row g-2">
                                <div class="col-12 text-center py-5">
                                    <i class="bx bx-list-check display-3 text-muted opacity-25"></i>
                                    <p class="text-muted mt-2">{{ __('No extra features listed.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-top bg-white p-3">
    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="btn btn-outline-primary btn-sm me-auto px-3">
        <i class="bx bx-expand-alt me-1"></i> {{ __('View') }}
    </a>
    <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm px-4 shadow">
            <i class="bx bx-edit-alt me-1"></i> {{ __('Edit') }}
        </a>
    @endif
</div>

<style>
.scroll-styling::-webkit-scrollbar { height: 4px; }
.scroll-styling::-webkit-scrollbar-thumb { background: #696cff; border-radius: 10px; }
.scroll-styling::-webkit-scrollbar-track { background: #f1f1f1; }

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ced4da; }

.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.smaller { font-size: 0.75rem; }
.transition-hover { transition: all 0.2s ease; }
.transition-hover:hover { transform: translateY(-2px); border-color: #696cff !important; box-shadow: 0 4px 8px rgba(105, 108, 255, 0.1) !important; }

.nav-tabs .nav-link { color: #8592a3; border-bottom: 3px solid transparent !important; }
.nav-tabs .nav-link.active { color: #696cff !important; border-bottom: 3px solid #696cff !important; background: transparent !important; }

@media (max-width: 767.98px) {
    .modal-body .row.g-0 {
        flex-direction: column;
    }

    .modal-body .col-md-5,
    .modal-body .col-md-7 {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
    }

    .modal-body .col-md-5 {
        border-right: 0 !important;
        border-bottom: 1px solid var(--bs-border-color, #d9dee3);
        padding: 1rem !important;
    }

    .modal-body .tab-content.custom-scrollbar {
        max-height: none !important;
        overflow-y: visible !important;
        padding: 1rem !important;
    }

    .modal-body .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .modal-body .nav-tabs .nav-item {
        flex: 1 0 auto;
    }

    .modal-header {
        align-items: flex-start;
    }

    .modal-header .d-flex.align-items-center {
        min-width: 0;
        flex: 1 1 auto;
    }

    .modal-footer {
        gap: 0.5rem;
        justify-content: stretch;
    }

    .modal-footer .btn,
    .modal-footer a.btn {
        flex: 1 1 auto;
        margin: 0 !important;
    }
}
</style>
