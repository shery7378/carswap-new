<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
             <span class="avatar-initial rounded-circle bg-label-primary border shadow-xs">
                {{ strtoupper(substr($vehicle->user?->first_name ?: ($vehicle->user?->last_name ?: ($vehicle->user?->email ?: 'U')), 0, 1)) }}
            </span>
        </div>
        <div>
            <h5 class="modal-title fw-bold mb-0 text-dark">{{ $vehicle->title }}</h5>
            <small class="text-muted small d-block">
                <i class="bx bx-map-pin me-1 text-primary"></i> {{ $vehicle->location ?: ($vehicle->address ?: 'No location specified') }}
                <span class="mx-1 text-light">|</span> 
                <i class="bx bx-calendar-event me-1"></i> {{ $vehicle->year }}
            </small>
        </div>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">

        @if($vehicle->is_featured)
            <span class="badge bg-label-warning d-none d-sm-inline-flex align-items-center"><i class="bx bxs-star me-1"></i> Featured</span>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar Summary -->
        <div class="col-md-5 border-end bg-light p-4">
            <div class="text-center mb-4 position-relative">
                <div class="main-img-container rounded border shadow-sm p-1 bg-white mb-2 overflow-hidden position-relative">
                    @if($vehicle->main_image_url)
                        <img id="modal-vehicle-main-image" src="{{ $vehicle->main_image_url }}" class="img-fluid rounded" style="max-height: 280px; width: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/800x600?text=No+Main+Image';">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow-lg fs-6 py-2 px-3">
                            {{ number_format($vehicle->price ?? 0, 0, ',', ' ') }} {{ $vehicle->currency ?? 'Ft' }}
                        </span>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                            <i class="bx bx-image text-muted display-4"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Gallery Thumbs -->
                @php $gallery = $vehicle->gallery_image_urls; @endphp
                @if($gallery && count($gallery) > 0)
                    <div class="gallery-scroller d-flex gap-2 overflow-auto pb-2 scroll-styling" style="max-width: 100%;">
                        @foreach($gallery as $img)
                            <img src="{{ $img }}" class="rounded border shadow-xs flex-shrink-0" style="width: 50px; height: 50px; cursor: pointer; object-fit: cover;" onclick="document.getElementById('modal-vehicle-main-image').src=this.src;" onmouseover="this.classList.add('border-primary')" onmouseout="this.classList.remove('border-primary')">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="info-sidebar-section mb-4">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted border-bottom pb-1">Listing Overview</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-label-info px-3 py-2"><i class="bx bx-tachometer me-1"></i> {{ number_format($vehicle->mileage) }} km</span>
                    <span class="badge bg-label-success px-3 py-2"><i class="bx bx-cog me-1"></i> {{ optional($vehicle->transmission)->name ?: 'N/A' }}</span>
                    <span class="badge bg-label-secondary px-3 py-2"><i class="bx bx-gas-pump me-1"></i> {{ optional($vehicle->fuelType)->name ?: 'N/A' }}</span>
                </div>
            </div>

            <div class="seller-card p-3 bg-white rounded border shadow-xs border-top border-3 border-info">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted border-bottom pb-1">Seller Details</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-info shadow-xs border">{{ strtoupper(substr($vehicle->user?->first_name ?: ($vehicle->user?->last_name ?: ($vehicle->user?->email ?: 'U')), 0, 1)) }}</span>
                    </div>
                    <div class="text-truncate">
                        <p class="mb-0 fw-bold text-dark small text-truncate">
                            @if($vehicle->user)
                                {{ trim($vehicle->user->first_name . ' ' . $vehicle->user->last_name) ?: 'Internal User' }}
                            @else
                                Guest User
                            @endif
                        </p>
                        <p class="mb-0 text-muted smaller text-truncate">{{ $vehicle->user->email ?? 'No email set' }}</p>
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
                            Vehicle Info
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-tech">
                            Technical
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-props">
                            Features ({{ $vehicle->properties->count() }})
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
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Mileage</small>
                                    <span class="fw-bold fs-5">{{ number_format($vehicle->mileage ?? 0, 0, ',', ' ') }} <small class="fw-normal">km</small></span>
                                </div>
                            </div>
                            <!-- Fuel Type -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-info border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Fuel Type</small>
                                    <span class="fw-bold fs-5 text-truncate d-block">{{ optional($vehicle->fuelType)->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <!-- Transmission -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-success border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Transmission</small>
                                    <span class="fw-bold fs-6 d-block lh-sm overflow-hidden" style="max-height: 44px;">{{ optional($vehicle->transmission)->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <!-- Power -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-warning border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Power</small>
                                    <span class="fw-bold fs-5">{{ $vehicle->performance ?? 'N/A' }} <small class="fw-normal">HP</small></span>
                                </div>
                            </div>
                            <!-- Status -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-secondary border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Status</small>
                                    <span class="badge bg-label-{{ $vehicle->ad_status == 'published' ? 'success' : 'warning' }} mt-1">{{ ucfirst($vehicle->ad_status) }}</span>
                                </div>
                            </div>
                            <!-- Location -->
                            <div class="col-6 col-sm-4">
                                <div class="p-3 border-start border-danger border-4 rounded bg-white shadow-xs h-100">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Location</small>
                                    <span class="fw-bold fs-6 d-block text-truncate">{{ $vehicle->location ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                                <i class="bx bx-info-circle me-2"></i> Quick Overview
                            </h6>
                            <div class="bg-light p-3 rounded border text-muted small lh-base">
                                This {{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }} is a {{ $vehicle->year }} model with {{ number_format($vehicle->mileage) }} km. 
                                Currently listed as <strong>{{ $vehicle->ad_status }}</strong> in {{ $vehicle->location ?: 'Not specified' }}.
                            </div>
                        </div>

                        <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                            <i class="bx bx-text me-2"></i> Description
                        </h6>
                        <div class="lh-base text-dark bg-light p-3 rounded border" style="font-size: 0.9rem;">
                            {!! $vehicle->description ? nl2br(e(Str::limit($vehicle->description, 500))) : '<em>No detailed description provided for this vehicle.</em>' !!}
                        </div>
                    </div>

                    <!-- Tech -->
                    <div class="tab-pane fade" id="v-modal-tech" role="tabpanel">
                        <table class="table table-sm table-hover border">
                            <thead class="table-light">
                                <tr><th class="py-2 px-3 small uppercase">Property</th><th class="py-2 px-3 small uppercase">Value</th></tr>
                            </thead>
                            <tbody>
                                <tr><td class="text-muted px-3 py-2">VIN / Alvázszám</td><td class="fw-bold px-3 py-2">{{ $vehicle->vin_number ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">Engine Code</td><td class="fw-bold px-3 py-2">{{ $vehicle->engine_number ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">Cylinder Capacity</td><td class="fw-bold px-3 py-2">{{ $vehicle->cylinder_capacity ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">Main Color</td><td class="fw-bold px-3 py-2">{{ optional($vehicle->exteriorColor)->name ?: 'N/A' }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">Tech Validation</td><td class="fw-bold text-danger px-3 py-2">{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->format('Y-m-d') : 'N/A' }}</td></tr>
                                <tr><td class="text-muted px-3 py-2">Location</td><td class="fw-bold px-3 py-2">{{ $vehicle->location ?: 'N/A' }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Props -->
                    <div class="tab-pane fade" id="v-modal-props" role="tabpanel">
                        <div class="row g-2">
                            @forelse($vehicle->properties as $prop)
                                <div class="col-6">
                                    <div class="d-flex align-items-center p-2 border rounded bg-white shadow-xs transition-hover">
                                        <i class="bx bxs-check-shield text-success me-2 fs-5"></i> 
                                        <span class="fw-semibold text-dark">{{ $prop->name }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bx bx-list-check display-3 text-muted opacity-25"></i>
                                    <p class="text-muted mt-2">No extra features listed.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-top bg-white p-3">
    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="btn btn-outline-primary btn-sm me-auto px-3">
        <i class="bx bx-expand-alt me-1"></i> Full Detailed Page
    </a>
    <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Close</button>
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm px-4 shadow">
            <i class="bx bx-edit-alt me-1"></i> Edit Vehicle
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
</style>
