<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
             <span class="avatar-initial rounded-circle bg-label-primary">
                {{ strtoupper(substr($vehicle->user?->first_name ?? 'U', 0, 1)) }}
            </span>
        </div>
        <div>
            <h5 class="modal-title fw-bold mb-0">{{ $vehicle->title }}</h5>
            <small class="text-muted">{{ optional($vehicle->brand)->name }} {{ optional($vehicle->model)->name }} • {{ $vehicle->year }}</small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar Summary -->
        <div class="col-md-5 border-end bg-light p-4">
            <div class="text-center mb-4 position-relative">
                @if($vehicle->main_image_url)
                    <img id="modal-vehicle-main-image" src="{{ $vehicle->main_image_url }}" class="img-fluid rounded shadow-sm border p-1 bg-white mb-3" style="max-height: 250px; width: 100%; object-fit: cover;">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow-sm">{{ number_format($vehicle->price ?? 0, 0, ',', ' ') }} {{ $vehicle->currency ?? 'Ft' }}</span>
                @endif
                
                <!-- Gallery Thumbs -->
                @php $gallery = $vehicle->gallery_image_urls; @endphp
                @if($gallery && count($gallery) > 0)
                    <div class="row g-1 overflow-auto flex-nowrap pb-2" style="max-width: 100%;">
                        @foreach($gallery as $img)
                            <div class="col-3">
                                <img src="{{ $img }}" class="img-fluid rounded border" style="height: 40px; cursor: pointer; object-fit: cover;" onclick="document.getElementById('modal-vehicle-main-image').src=this.src;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="seller-info small p-3 bg-white rounded border">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted border-bottom pb-1">{{ __('Seller') }}</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-info">{{ substr($vehicle->user?->first_name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div class="text-truncate">
                        <p class="mb-0 fw-bold">{{ $vehicle->user ? $vehicle->user->first_name . ' ' . $vehicle->user->last_name : __('N/A') }}</p>
                        <p class="mb-0 text-muted smaller text-truncate">{{ $vehicle->user->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabs -->
        <div class="col-md-7">
            <div class="nav-align-top h-100">
                <ul class="nav nav-tabs nav-fill rounded-0" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active py-3" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-overview">
                            {{ __('Overview') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-tech">
                            {{ __('Specs') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3" role="tab" data-bs-toggle="tab" data-bs-target="#v-modal-props">
                            {{ __('Features') }}
                        </button>
                    </li>
                </ul>
                <div class="tab-content border-0 shadow-none bg-transparent p-4" style="max-height: 400px; overflow-y: auto;">
                    <!-- Overview -->
                    <div class="tab-pane fade show active" id="v-modal-overview" role="tabpanel">
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block small">{{ __('Mileage') }}</small>
                                    <span class="fw-bold">{{ number_format($vehicle->mileage ?? 0, 0, ',', ' ') }} km</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block small">{{ __('Fuel') }}</small>
                                    <span class="fw-bold">{{ optional($vehicle->fuelType)->name ?? __('N/A') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block small">{{ __('Trans.') }}</small>
                                    <span class="fw-bold">{{ optional($vehicle->transmission)->name ?? __('N/A') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light text-center">
                                    <small class="text-muted d-block small">{{ __('Power') }}</small>
                                    <span class="fw-bold">{{ $vehicle->performance ?? '0' }} HP</span>
                                </div>
                            </div>
                        </div>
                        <h6 class="fw-bold text-uppercase small text-muted mb-2">{{ __('Description') }}</h6>
                        <div class="lh-base small text-dark">
                            {!! $vehicle->description ? Str::limit(e($vehicle->description), 300) : '<em>' . __('No description.') . '</em>' !!}
                        </div>
                    </div>

                    <!-- Tech -->
                    <div class="tab-pane fade" id="v-modal-tech" role="tabpanel">
                        <table class="table table-sm table-striped small">
                            <tbody>
                                <tr><td class="text-muted border-0">{{ __('VIN') }}</td><td class="fw-bold border-0">{{ $vehicle->vin_number ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted">{{ __('Drive') }}</td><td class="fw-bold">{{ optional($vehicle->driveType)->name ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted">{{ __('Body') }}</td><td class="fw-bold">{{ optional($vehicle->bodyType)->name ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted">{{ __('Capacity') }}</td><td class="fw-bold">{{ $vehicle->cylinder_capacity ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted">{{ __('Ext. Color') }}</td><td class="fw-bold">{{ optional($vehicle->exteriorColor)->name ?: __('N/A') }}</td></tr>
                                <tr><td class="text-muted">{{ __('Expiration') }}</td><td class="fw-bold">{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->format('Y-m-d') : __('N/A') }}</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Props -->
                    <div class="tab-pane fade" id="v-modal-props" role="tabpanel">
                        <div class="row g-2">
                            @forelse($vehicle->properties as $prop)
                                <div class="col-6">
                                    <div class="d-flex align-items-center p-1 border rounded small">
                                        <i class="bx bx-check text-success me-2"></i> <span>{{ $prop->name }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 text-muted small">{{ __('No properties listed.') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-top">
    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="btn btn-outline-primary btn-sm me-auto">
        <i class="bx bx-expand-alt me-1"></i> {{ __('Full View') }}
    </a>
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
    @if(auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-primary btn-sm px-3">
            <i class="bx bx-edit-alt me-1"></i> {{ __('Edit') }}
        </a>
    @endif
</div>
