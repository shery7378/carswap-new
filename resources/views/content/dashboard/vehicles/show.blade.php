@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicle Details: ' . $vehicle->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            {{ strtoupper(substr($vehicle->user?->first_name ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $vehicle->title }}</h5>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <small class="text-muted"><i class="bx bx-purchase-tag-alt me-1"></i> {{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }}</small>
                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-4 h-px-4 mx-1"></span>
                            <small class="text-muted"><i class="bx bx-calendar me-1"></i> {{ $vehicle->year }}</small>
                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-4 h-px-4 mx-1"></span>
                            <small class="text-primary fw-bold"><i class="bx bx-user me-1 text-primary"></i> Posted by: {{ $vehicle->user ? $vehicle->user->first_name . ' ' . $vehicle->user->last_name : 'System' }}</small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Back to List
                    </a>
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                        <button type="button" 
                            class="btn btn-icon btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn shadow-none" 
                            data-id="{{ $vehicle->id }}" 
                            data-bs-toggle="tooltip" 
                            title="{{ $vehicle->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                            <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                        </button>
                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="bx bx-edit-alt me-1"></i> Edit Vehicle
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Sidebar: Image & Quick Stats -->
                    <div class="col-md-5 border-end bg-light-soft p-4">
                        <div class="main-image-wrapper mb-3 position-relative">
                            @if($vehicle->main_image_url)
                                <img id="detail-main-image" src="{{ $vehicle->main_image_url }}" alt="{{ $vehicle->title }}" 
                                    class="img-fluid rounded shadow-sm w-100" style="max-height: 450px; object-fit: cover;"
                                    onerror="this.src='https://placehold.co/800x600?text=No+Main+Image';">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded border" style="height: 300px;">
                                    <span class="text-muted">No Image Available</span>
                                </div>
                            @endif
                            
                            <span class="position-absolute top-0 end-0 m-3 badge bg-primary shadow-sm fs-5 py-2 px-3">
                                {{ number_format($vehicle->price ?? 0, 0, ',', ' ') }} {{ $vehicle->currency ?? 'Ft' }}
                            </span>
                        </div>
                        
                        @php 
                            $gallery = $vehicle->gallery_image_urls;
                        @endphp
                        
                        @if($gallery && is_array($gallery) && count($gallery) > 0)
                            <h6 class="fw-bold mb-3 small text-uppercase">Gallery ({{ count($gallery) }} Photos)</h6>
                            <div class="gallery-wrapper row g-2 mb-4" style="max-height: 250px; overflow-y: auto; padding: 2px;">
                                @foreach($gallery as $imgUrl)
                                    <div class="col-3">
                                        <img src="{{ $imgUrl }}" class="img-fluid rounded border shadow-xs gallery-thumb" 
                                            style="height: 65px; width: 100%; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('detail-main-image').src=this.src;"
                                            onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=x';">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="seller-card p-4 bg-white rounded border shadow-xs mt-3">
                            <h6 class="fw-bold mb-3 small text-uppercase border-bottom pb-2 font-secondary">Seller Information</h6>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg me-3">
                                    <span class="avatar-initial rounded-circle bg-label-info">
                                        {{ strtoupper(substr($vehicle->user?->first_name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="mb-1 fw-bold fs-5 text-truncate">{{ $vehicle->user ? $vehicle->user->first_name . ' ' . $vehicle->user->last_name : 'N/A' }}</p>
                                    <p class="mb-0 text-muted text-truncate">{{ $vehicle->user->email ?? '' }}</p>
                                </div>
                            </div>
                            @if($vehicle->user?->phone)
                                <hr class="my-3">
                                <div class="small d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Phone:</span>
                                    <span class="fw-bold">{{ $vehicle->user->phone }}</span>
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="mailto:{{ $vehicle->user->email ?? '' }}" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bx bx-envelope me-1"></i> Contact Seller
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content: Tabs & Details -->
                    <div class="col-md-7 p-4 bg-white">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#nav-stats">
                                        <i class="bx bx-car fs-5 me-1"></i> Overview
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-desc">
                                        <i class="bx bx-text fs-5 me-1"></i> Full Description
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-tech">
                                        <i class="bx bx-wrench fs-5 me-1"></i> Technical
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-features">
                                        <i class="bx bx-list-check fs-5 me-1"></i> Properties
                                    </button>
                                </li>
                                @if($vehicle->exchange_preferences && count($vehicle->exchange_preferences) > 0)
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-exchange">
                                        <i class="bx bx-refresh fs-5 me-1"></i> Trade-In
                                    </button>
                                </li>
                                @endif
                            </ul>
                            <div class="tab-content border p-0 shadow-none bg-transparent">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="nav-stats" role="tabpanel">
                                    <div class="row g-4 p-4">
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-primary border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Mileage</small>
                                                <span class="fs-4 fw-bold">{{ number_format($vehicle->mileage ?? 0, 0, ',', ' ') }} <small class="fw-normal">km</small></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-info border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Fuel Type</small>
                                                <span class="fs-4 fw-bold text-truncate d-block">{{ optional($vehicle->fuelType)->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-success border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Transmission</small>
                                                <span class="fs-4 fw-bold">{{ optional($vehicle->transmission)->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-warning border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Power</small>
                                                <span class="fs-4 fw-bold">{{ $vehicle->performance ?? 'N/A' }} <small class="fw-normal">HP</small></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-secondary border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Status</small>
                                                <span class="badge bg-label-{{ $vehicle->ad_status == 'published' ? 'success' : 'warning' }} fs-6 mt-1">{{ ucfirst($vehicle->ad_status) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="stat-card p-3 border-start border-danger border-5 rounded bg-light shadow-xs h-100">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Location</small>
                                                <span class="fw-bold d-block text-truncate fs-5">{{ $vehicle->location ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 pb-4">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2">Quick Overview</h6>
                                        <div class="bg-light p-3 rounded border text-muted">
                                            This {{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }} is a {{ $vehicle->year }} model with {{ $vehicle->mileage }} km. 
                                            Currently listed as <strong>{{ $vehicle->ad_status }}</strong> in {{ $vehicle->location ?: 'Not specified' }}.
                                        </div>
                                    </div>
                                </div>

                                <!-- Description Tab -->
                                <div class="tab-pane fade" id="nav-desc" role="tabpanel">
                                    <div class="p-4 bg-white border-top-0 rounded-bottom" style="min-height: 480px;">
                                        <h6 class="fw-bold mb-3 border-bottom pb-2">Full Listing Description</h6>
                                        <div class="lh-base" style="font-size: 1.05rem;">
                                            {!! $vehicle->description ? nl2br(e($vehicle->description)) : '<em class="text-muted">No description provided.</em>' !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Technical Tab -->
                                <div class="tab-pane fade" id="nav-tech" role="tabpanel">
                                    <div class="p-4 bg-white border-top-0 rounded-bottom" style="min-height: 480px;">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="border-0">Technical Specification</th>
                                                    <th class="border-0">Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td class="text-muted py-3">VIN Number (Alvázszám)</td><td class="fw-bold py-3">{{ $vehicle->vin_number ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Engine Number</td><td class="fw-bold py-3">{{ $vehicle->engine_number ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Drive Type (Hajtás)</td><td class="fw-bold py-3">{{ optional($vehicle->driveType)->name ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Body Type (Design)</td><td class="fw-bold py-3">{{ optional($vehicle->bodyType)->name ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Cylinder Capacity</td><td class="fw-bold py-3">{{ $vehicle->cylinder_capacity ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Exterior Color</td><td class="fw-bold py-3">{{ optional($vehicle->exteriorColor)->name ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Interior Color</td><td class="fw-bold py-3">{{ optional($vehicle->interiorColor)->name ?: 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Technical Expiration</td><td class="fw-bold text-danger py-3">{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->format('Y-m-d') : 'N/A' }}</td></tr>
                                                <tr><td class="text-muted py-3">Document Type</td><td class="fw-bold py-3">{{ optional($vehicle->documentType)->name ?: 'N/A' }}</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Features Tab -->
                                <div class="tab-pane fade" id="nav-features" role="tabpanel">
                                    <div class="p-4 bg-white border-top-0 rounded-bottom" style="min-height: 480px;">
                                        <h6 class="fw-bold mb-4">Vehicle Properties & Extra Features</h6>
                                        <div class="row">
                                            @forelse($vehicle->properties as $prop)
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center p-2 border rounded hover-shadow-xs transition-2s">
                                                        <i class="bx bx-check-circle text-success me-3 fs-3"></i> 
                                                        <span class="fw-bold">{{ $prop->name }}</span>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center py-5">
                                                    <i class="bx bx-list-minus text-muted display-1"></i>
                                                    <p class="text-muted mt-3">No specific features listed for this vehicle.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Exchange Tab -->
                                @if($vehicle->exchange_preferences && count($vehicle->exchange_preferences) > 0)
                                <div class="tab-pane fade" id="nav-exchange" role="tabpanel">
                                    <div class="p-4 bg-white border-top-0 rounded-bottom" style="min-height: 480px;">
                                        <h6 class="fw-bold mb-4">Trade-In / Exchange Preferences</h6>
                                        <div class="row">
                                            @foreach($vehicle->exchange_preferences as $index => $pref)
                                                <div class="col-md-12 mb-4">
                                                    <div class="card bg-light border-0 shadow-xs border-start border-primary border-5">
                                                        <div class="card-body p-4">
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <h5 class="fw-bold mb-0">Preference Group #{{ $index + 1 }}</h5>
                                                                <span class="badge bg-primary">Preferred Swap</span>
                                                            </div>
                                                            <div class="row g-4">
                                                                <div class="col-md-3">
                                                                    <small class="text-muted text-uppercase d-block mb-2 fw-bold">Brand</small>
                                                                    <div class="fs-6 fw-bold text-dark">{{ \App\Models\Brand::find($pref['brand_id'] ?? 0)?->name ?: 'Any Brand' }}</div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <small class="text-muted text-uppercase d-block mb-2 fw-bold">Model</small>
                                                                    <div class="fs-6 fw-bold text-dark">{{ \App\Models\VehicleModel::find($pref['model_id'] ?? 0)?->name ?: 'Any Model' }}</div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <small class="text-muted text-uppercase d-block mb-2 fw-bold">Fuel Type</small>
                                                                    <div class="fs-6 fw-bold text-dark">{{ \App\Models\FuelType::find($pref['fuel_type_id'] ?? 0)?->name ?: 'Any Fuel' }}</div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <small class="text-muted text-uppercase d-block mb-2 fw-bold">Min Year</small>
                                                                    <div class="fs-6 fw-bold text-dark">{{ $pref['year_from'] ?: 'Any' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light border-top text-end p-4">
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary me-2">
                    Back to Vehicles
                </a>
                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary shadow">
                        <i class="bx bx-edit-alt me-1"></i> Edit Vehicle Information
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-soft {
    background-color: #f8f9fa;
}
.shadow-xs {
    box-shadow: 0 .125rem .25rem rgba(105, 108, 255, .05);
}
.nav-tabs .nav-link {
    font-size: 0.95rem;
    padding: 1rem 1.25rem;
    font-weight: 500;
    color: #6c757d;
    border: none;
    border-bottom: 4px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #696cff !important;
    background-color: transparent !important;
    border-bottom: 4px solid #696cff !important;
}
.nav-tabs .nav-link:hover {
    color: #696cff;
    border-bottom: 4px solid rgba(105, 108, 255, 0.2);
}
.stat-card {
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(105, 108, 255, 0.1) !important;
}
.gallery-thumb {
    transition: all 0.2s ease-in-out;
}
.gallery-thumb:hover {
    border-color: #696cff !important;
    box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.15) !important;
    transform: scale(1.08);
    z-index: 10;
}
.transition-2s {
    transition: all 0.2s ease;
}
.hover-shadow-xs:hover {
    box-shadow: 0 .125rem .25rem rgba(105, 108, 255, .1);
    border-color: #696cff !important;
}
</style>
@section('page-script')
<script>
    $(document).ready(function() {
        // ✅ FEATURED TOGGLE
        $(document).on('click', '.featured-toggle-btn', function() {
            const button = $(this);
            const id = button.data('id');
            const icon = button.find('i');

            $.ajax({
                url: `{{ url('/app/vehicles') }}/${id}/toggle-featured`,
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        if (response.is_featured) {
                            button.removeClass('btn-label-secondary').addClass('btn-label-warning');
                            icon.removeClass('bx-star').addClass('bxs-star');
                            button.attr('data-bs-original-title', 'Remove from Featured');
                        } else {
                            button.removeClass('btn-label-warning').addClass('btn-label-secondary');
                            icon.removeClass('bxs-star').addClass('bx-star');
                            button.attr('data-bs-original-title', 'Mark as Featured');
                        }
                        
                        // Update Bootstrap tooltip
                        var tooltip = bootstrap.Tooltip.getInstance(button[0]);
                        if (tooltip) {
                            tooltip.hide();
                            button.attr('title', response.is_featured ? 'Remove from Featured' : 'Mark as Featured');
                            tooltip = new bootstrap.Tooltip(button[0]);
                        }
                        
                        toastr.success(response.message, 'Updated');
                    }
                },
                error: function() {
                    toastr.error('Could not update featured status.', 'Error');
                }
            });
        });
    });
</script>
@endsection
@endsection
