<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
            @if($partner->image)
                <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="rounded-circle border" style="object-fit: cover; width: 45px; height: 45px;">
            @else
                <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ strtoupper(substr($partner->name ?? 'P', 0, 1)) }}
                </span>
            @endif
        </div>
        <div>
            <h5 class="modal-title fw-bold mb-0">{{ $partner->name }}</h5>
            <small class="text-muted"><i class="bx bx-map-pin me-1"></i> {{ $partner->address ?: 'No address' }}</small>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar Summary -->
        <div class="col-md-4 border-end bg-light p-4">
            <div class="text-center mb-4">
                @if($partner->image)
                    <img src="{{ asset('storage/' . $partner->image) }}" class="img-fluid rounded shadow-sm border p-1 bg-white mb-3" style="max-height: 200px; object-fit: contain;">
                @endif
                
                <div class="contact-info small text-start">
                    <h6 class="fw-bold mb-2 small text-uppercase text-muted border-bottom pb-1">Quick Contact</h6>
                    <p class="mb-1"><i class="bx bx-envelope text-primary me-2"></i> {{ $partner->email ?: 'N/A' }}</p>
                    <p class="mb-1"><i class="bx bx-phone text-success me-2"></i> {{ $partner->phone ?: 'N/A' }}</p>
                    @if($partner->website)
                        <p class="mb-0"><i class="bx bx-globe text-info me-2"></i> <a href="{{ $partner->website }}" target="_blank">Website</a></p>
                    @endif
                </div>
            </div>

            <!-- Services count stat -->
            <div class="badge bg-label-primary w-100 py-2 mt-2">
                <i class="bx bx-briefcase me-1"></i> {{ $partner->services->count() }} Services Offered
            </div>
        </div>

        <!-- Main Tabs -->
        <div class="col-md-8">
            <div class="nav-align-top h-100">
                <ul class="nav nav-tabs nav-fill rounded-0" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active py-3" role="tab" data-bs-toggle="tab" data-bs-target="#modal-nav-intro">
                            Intro
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3" role="tab" data-bs-toggle="tab" data-bs-target="#modal-nav-hours">
                            Hours
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3" role="tab" data-bs-toggle="tab" data-bs-target="#modal-nav-location">
                            Map
                        </button>
                    </li>
                </ul>
                <div class="tab-content border-0 shadow-none bg-transparent p-4">
                    <!-- Intro -->
                    <div class="tab-pane fade show active" id="modal-nav-intro" role="tabpanel">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">About Company</h6>
                        <div class="lh-base text-dark small" style="max-height: 300px; overflow-y: auto;">
                            {!! $partner->description ? nl2br(e($partner->description)) : '<em>No introduction provided.</em>' !!}
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="tab-pane fade" id="modal-nav-hours" role="tabpanel">
                        <div class="row g-2">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                @php $hour = $partner->openingHours->firstWhere('day', $day); @endphp
                                <div class="col-6">
                                    <div class="p-2 border rounded small bg-white d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">{{ $day }}</span>
                                        @if($hour && !$hour->is_closed)
                                            <span class="text-primary">{{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}</span>
                                        @else
                                            <span class="text-danger small">Closed</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="tab-pane fade" id="modal-nav-location" role="tabpanel">
                        @if($partner->latitude && $partner->longitude)
                            <div id="modal-partner-map" class="rounded border mb-2" style="height: 250px; width: 100%;"></div>
                            <small class="text-muted"><i class="bx bx-info-circle me-1"></i> Coordinates: {{ $partner->latitude }}, {{ $partner->longitude }}</small>
                            <script>
                                (function() {
                                    const position = { lat: parseFloat('{{ $partner->latitude }}'), lng: parseFloat('{{ $partner->longitude }}') };
                                    const mapElement = document.getElementById('modal-partner-map');
                                    if (typeof google !== 'undefined' && mapElement) {
                                        const map = new google.maps.Map(mapElement, {
                                            center: position,
                                            zoom: 14,
                                            mapTypeControl: false,
                                            streetViewControl: false,
                                        });
                                        new google.maps.Marker({ position: position, map: map });
                                    }
                                })();
                            </script>
                        @else
                            <div class="text-center py-5">
                                <i class="bx bx-map-alt display-3 text-muted opacity-25"></i>
                                <p class="text-muted mt-2">No coordinates set.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-top">
    <a href="{{ route('admin.partners.show', $partner->id) }}" class="btn btn-outline-primary btn-sm me-auto">
        <i class="bx bx-expand-alt me-1"></i> Open Full Page
    </a>
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
    @if(auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn btn-primary btn-sm px-3">
            <i class="bx bx-edit-alt me-1"></i> Edit
        </a>
    @endif
</div>
