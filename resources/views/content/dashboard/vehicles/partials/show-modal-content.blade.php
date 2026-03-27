<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
            <span class="avatar-initial rounded-circle bg-label-primary">
                {{ strtoupper(substr($vehicle->user?->first_name ?? 'U', 0, 1)) }}
            </span>
        </div>
        <div>
            <h5 class="modal-title mb-0 fw-bold">{{ $vehicle->title }}</h5>
            <small class="text-muted">{{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }} • {{ $vehicle->year }}</small>
        </div>
    </div>
    <div class="ms-auto d-flex align-items-center">
        @if($vehicle->is_featured)
            <span class="badge bg-label-warning me-3"><i class="bx bxs-star me-1"></i> Featured</span>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>
<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar: Image & Quick Stats -->
        <div class="col-md-5 border-end bg-light-soft p-4">
            <div class="main-image-wrapper mb-3 position-relative">
                @if($vehicle->main_image_url)
                    <img id="modal-main-image" src="{{ $vehicle->main_image_url }}" alt="{{ $vehicle->title }}" 
                        class="img-fluid rounded shadow-sm w-100" style="max-height: 350px; object-fit: cover;"
                        onerror="this.src='https://placehold.co/600x400?text=No+Main+Image';">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded border" style="height: 250px;">
                        <span class="text-muted">No Image Available</span>
                    </div>
                @endif
                
                <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow">
                    {{ number_format($vehicle->price ?? 0, 0, ',', ' ') }} {{ $vehicle->currency ?? 'Ft' }}
                </span>
            </div>
            
            @php 
                $gallery = $vehicle->gallery_image_urls;
            @endphp
            
            @if($gallery && is_array($gallery) && count($gallery) > 0)
                <h6 class="fw-bold mb-2 small text-uppercase">Gallery ({{ count($gallery) }} Photos)</h6>
                <div class="gallery-wrapper row g-1 mb-4" style="max-height: 250px; overflow-y: auto;">
                    @foreach($gallery as $imgUrl)
                        <div class="col-3 mb-1">
                            <img src="{{ $imgUrl }}" class="img-fluid rounded border shadow-xs gallery-thumb" 
                                style="height: 55px; width: 100%; object-fit: cover; cursor: pointer;"
                                onclick="document.getElementById('modal-main-image').src=this.src;"
                                onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=x';">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="seller-card p-3 bg-white rounded border shadow-xs">
                <h6 class="fw-bold mb-2 small text-uppercase border-bottom pb-1">Seller Information</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-secondary small">
                            {{ strtoupper(substr($vehicle->user?->first_name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div class="overflow-hidden">
                        <p class="mb-0 fw-bold small text-truncate">{{ $vehicle->user ? $vehicle->user->first_name . ' ' . $vehicle->user->last_name : 'N/A' }}</p>
                        <p class="mb-0 text-muted small text-truncate">{{ $vehicle->user->email ?? '' }}</p>
                    </div>
                </div>
                @if($vehicle->user?->phone)
                    <div class="mt-2 small">
                        <i class="bx bx-phone me-1"></i> {{ $vehicle->user->phone }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content: Tabs & Details -->
        <div class="col-md-7 p-4">
            <div class="nav-align-top">
                <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#modal-stats" aria-selected="true">
                            <i class="bx bx-car me-1"></i> Stats
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#modal-desc" aria-selected="false">
                            <i class="bx bx-text me-1"></i> Description
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#modal-tech" aria-selected="false">
                            <i class="bx bx-wrench me-1"></i> Technical
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#modal-features" aria-selected="false">
                            <i class="bx bx-list-check me-1"></i> Features
                        </button>
                    </li>
                    @if($vehicle->exchange_preferences && count($vehicle->exchange_preferences) > 0)
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#modal-exchange" aria-selected="false">
                            <i class="bx bx-refresh me-1"></i> Exchange
                        </button>
                    </li>
                    @endif
                </ul>
                <div class="tab-content shadow-none border p-0 bg-transparent">
                    <!-- Quick Stats Tab -->
                    <div class="tab-pane fade show active" id="modal-stats" role="tabpanel">
                        <div class="row g-3 p-3">
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-primary border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Mileage</small>
                                    <span class="fw-bold fs-5">{{ number_format($vehicle->mileage ?? 0, 0, ',', ' ') }} km</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-info border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Fuel Type</small>
                                    <span class="fw-bold fs-5 text-truncate d-block">{{ optional($vehicle->fuelType)->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-success border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Transmission</small>
                                    <span class="fw-bold fs-5">{{ optional($vehicle->transmission)->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-warning border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Engine Power</small>
                                    <span class="fw-bold fs-5">{{ $vehicle->performance ?? 'N/A' }} HP</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-secondary border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Status</small>
                                    <span class="badge bg-label-{{ $vehicle->ad_status == 'published' ? 'success' : 'warning' }} fs-6">{{ ucfirst($vehicle->ad_status) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="stat-box p-3 border-start border-danger border-4 rounded bg-white shadow-xs">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.65rem;">Location</small>
                                    <span class="fw-bold text-truncate d-block">{{ $vehicle->location ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Tab -->
                    <div class="tab-pane fade" id="modal-desc" role="tabpanel">
                        <div class="p-4 bg-white border-top-0 rounded-bottom" style="height: 400px; overflow-y: auto;">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Full Description</h6>
                            {!! $vehicle->description ? nl2br(e($vehicle->description)) : '<em class="text-muted">No description provided.</em>' !!}
                        </div>
                    </div>

                    <!-- Technical Tab -->
                    <div class="tab-pane fade" id="modal-tech" role="tabpanel">
                        <div class="p-3 bg-white border-top-0 rounded-bottom" style="height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-striped">
                                <tr><td class="text-muted py-2">VIN Number</td><td class="fw-bold py-2">{{ $vehicle->vin_number ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Engine Number</td><td class="fw-bold py-2">{{ $vehicle->engine_number ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Drive Type</td><td class="fw-bold py-2">{{ optional($vehicle->driveType)->name ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Body Type</td><td class="fw-bold py-2">{{ optional($vehicle->bodyType)->name ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Cylinder Capacity</td><td class="fw-bold py-2">{{ $vehicle->cylinder_capacity ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Exterior Color</td><td class="fw-bold py-2">{{ optional($vehicle->exteriorColor)->name ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Interior Color</td><td class="fw-bold py-2">{{ optional($vehicle->interiorColor)->name ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Technical Expiration</td><td class="fw-bold text-danger py-2">{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->format('Y-m-d') : 'N/A' }}</td></tr>
                                <tr><td class="text-muted py-2">Document Type</td><td class="fw-bold py-2">{{ optional($vehicle->documentType)->name ?: 'N/A' }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <!-- Features Tab -->
                    <div class="tab-pane fade" id="modal-features" role="tabpanel">
                        <div class="p-4 bg-white border-top-0 rounded-bottom" style="height: 400px; overflow-y: auto;">
                            <div class="row">
                                @forelse($vehicle->properties as $prop)
                                    <div class="col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check-circle text-success me-2 fs-5"></i> 
                                            <span class="fw-semibold">{{ $prop->name }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bx bx-list-minus text-muted display-4"></i>
                                        <p class="text-muted mt-2">No specific features listed.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @if($vehicle->exchange_preferences && count($vehicle->exchange_preferences) > 0)
                    <div class="tab-pane fade" id="modal-exchange" role="tabpanel">
                        <div class="p-3 bg-white border-top-0 rounded-bottom" style="height: 400px; overflow-y: auto;">
                            @foreach($vehicle->exchange_preferences as $index => $pref)
                                <div class="card bg-light border-0 mb-3 shadow-sm border-start border-primary border-4">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold mb-3 d-flex justify-content-between">
                                            Preference #{{ $index + 1 }}
                                            <span class="badge bg-label-info">Exchange Requirement</span>
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-6"><span class="text-muted d-block small mb-1">Brand:</span> <strong>{{ \App\Models\Brand::find($pref['brand_id'] ?? 0)?->name ?: 'Any Brand' }}</strong></div>
                                            <div class="col-6"><span class="text-muted d-block small mb-1">Model:</span> <strong>{{ \App\Models\VehicleModel::find($pref['model_id'] ?? 0)?->name ?: 'Any Model' }}</strong></div>
                                            <div class="col-6"><span class="text-muted d-block small mb-1">Fuel Type:</span> <strong>{{ \App\Models\FuelType::find($pref['fuel_type_id'] ?? 0)?->name ?: 'Any Fuel' }}</strong></div>
                                            <div class="col-6"><span class="text-muted d-block small mb-1">Min Year:</span> <strong>{{ $pref['year_from'] ?: 'Any' }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer border-top p-3 bg-light">
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm btn-icon-start shadow">
            <i class="bx bx-edit-alt me-1"></i> Edit Full Details
        </a>
    @endif
    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
</div>

<style>
.bg-light-soft {
    background-color: #f8f9fa;
}
.shadow-xs {
    box-shadow: 0 .125rem .25rem rgba(161, 172, 184, .4);
}
.nav-tabs .nav-link {
    font-size: 0.9rem;
    padding: 0.85rem;
    border: none;
    border-bottom: 3px solid transparent;
    color: #a1adb8;
    font-weight: 500;
}
.nav-tabs .nav-link.active {
    color: #696cff !important;
    background-color: transparent !important;
    border-bottom: 3px solid #696cff;
}
.stat-box {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1) !important;
}
.gallery-thumb {
    transition: all 0.15s ease-in-out;
}
.gallery-thumb:hover {
    border-color: #696cff !important;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.2);
    transform: scale(1.05);
}
.tab-pane {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
