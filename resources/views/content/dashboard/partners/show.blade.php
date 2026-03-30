@extends('layouts/contentNavbarLayout')

@section('title', 'Partner Details: ' . $partner->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3">
                        @if($partner->image)
                            <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="rounded-circle border shadow-xs" style="object-fit: cover;">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                {{ strtoupper(substr($partner->name ?? 'P', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $partner->name }}</h5>
                        <small class="text-muted"><i class="bx bx-map-pin me-1"></i> {{ $partner->address ?: 'No address specified' }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i> Back to List
                    </a>
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn btn-primary btn-sm shadow-sm px-3">
                            <i class="bx bx-edit-alt me-1"></i> Edit Partner
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Sidebar: Summary & Contact -->
                    <div class="col-md-4 border-end bg-light-soft p-4">
                        <div class="main-image-wrapper mb-4 position-relative">
                            @if($partner->image)
                                <img id="detail-main-image" src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" 
                                    class="img-fluid rounded shadow-sm w-100 border p-1 bg-white" style="max-height: 300px; object-fit: contain;">
                            @else
                                <div class="bg-white d-flex align-items-center justify-content-center rounded border shadow-xs" style="height: 250px;">
                                    <span class="text-muted display-1 opacity-25">{{ strtoupper(substr($partner->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Gallery Preview -->
                        @if($partner->gallery && count($partner->gallery) > 0)
                            <h6 class="fw-bold mb-3 small text-uppercase">Partner Gallery ({{ count($partner->gallery) }})</h6>
                            <div class="gallery-wrapper row g-2 mb-4" style="max-height: 200px; overflow-y: auto; padding: 2px;">
                                @foreach($partner->gallery as $photo)
                                    <div class="col-4">
                                        <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded border shadow-xs gallery-thumb h-100" 
                                            style="height: 70px !important; width: 100%; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('detail-main-image').src=this.src;"
                                            onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=x';">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="contact-info-card p-4 bg-white rounded border shadow-xs mt-3">
                            <h6 class="fw-bold mb-3 small text-uppercase border-bottom pb-2">Business Contact</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="bx bx-envelope text-primary me-3 mt-1 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block uppercase fw-semibold" style="font-size: 0.65rem;">Email Address</small>
                                        <span class="fw-bold text-dark">{{ $partner->email ?: 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex align-items-start">
                                    <i class="bx bx-phone text-success me-3 mt-1 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block uppercase fw-semibold" style="font-size: 0.65rem;">Official Phone</small>
                                        <span class="fw-bold text-dark">{{ $partner->phone ?: 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="mb-0 d-flex align-items-start">
                                    <i class="bx bx-globe text-info me-3 mt-1 fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block uppercase fw-semibold" style="font-size: 0.65rem;">Website</small>
                                        @if($partner->website)
                                            <a href="{{ $partner->website }}" target="_blank" class="fw-bold text-primary">{{ str_replace(['http://', 'https://'], '', $partner->website) }}</a>
                                        @else
                                            <span class="fw-bold text-dark">N/A</span>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Main Content: Detailed Tabs -->
                    <div class="col-md-8 p-4 bg-white">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#nav-intro">
                                        <i class="bx bx-info-circle fs-5 me-1"></i> Introduction
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-hours">
                                        <i class="bx bx-time fs-5 me-1"></i> Opening Hours
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-services">
                                        <i class="bx bx-briefcase fs-5 me-1"></i> Services ({{ $partner->services->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#nav-location">
                                        <i class="bx bx-map fs-5 me-1"></i> Exact Location
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content border-0 p-0 shadow-none bg-transparent">
                                <!-- Introduction Tab -->
                                <div class="tab-pane fade show active" id="nav-intro" role="tabpanel">
                                    <div class="p-4 border rounded shadow-xs bg-light bg-opacity-10 h-100" style="min-height: 400px;">
                                        <h5 class="fw-bold mb-3 d-flex align-items-center">
                                            <span class="badge bg-primary me-2">&nbsp;</span> Company Profile
                                        </h5>
                                        <div class="lh-lg text-dark fs-6">
                                            {!! $partner->description ? nl2br(e($partner->description)) : '<em class="text-muted font-italic">No introduction provided for this partner.</em>' !!}
                                        </div>
                                    </div>
                                </div>

                                <!-- Opening Hours Tab -->
                                <div class="tab-pane fade" id="nav-hours" role="tabpanel">
                                    <div class="p-4 border rounded shadow-xs bg-light bg-opacity-10 h-100" style="min-height: 400px;">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-bold mb-0">Weekly Schedule</h5>
                                            <span class="badge {{ $partner->show_opening_hours ? 'bg-label-success' : 'bg-label-secondary' }}">
                                                {{ $partner->show_opening_hours ? 'Visible to Public' : 'Hidden from Public' }}
                                            </span>
                                        </div>
                                        <div class="row g-3">
                                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                @php $hour = $partner->openingHours->firstWhere('day', $day); @endphp
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-white rounded shadow-xs border-start border-4 {{ ($hour && !$hour->is_closed) ? 'border-primary' : 'border-danger' }}">
                                                        <span class="fw-bold fs-6">{{ $day }}</span>
                                                        @if($hour && !$hour->is_closed)
                                                            <div class="text-end">
                                                                <span class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($hour->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($hour->close_time)->format('H:i') }}</span>
                                                                <br><small class="text-success fw-semibold">Open</small>
                                                            </div>
                                                        @else
                                                            <span class="badge bg-label-danger py-2 px-3">Closed / Nem üzemel</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Services Tab -->
                                <div class="tab-pane fade" id="nav-services" role="tabpanel">
                                    <div class="p-4 border rounded shadow-xs bg-light bg-opacity-10 h-100" style="min-height: 400px;">
                                        <h5 class="fw-bold mb-4">List of Services Provided</h5>
                                        <div class="row g-4">
                                            @forelse($partner->services as $service)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card h-100 border shadow-xs hover-stat transition-2s">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <h6 class="fw-bold mb-0 text-primary">{{ $service->name }}</h6>
                                                                <span class="badge {{ $service->is_active ? 'bg-label-success' : 'bg-label-secondary' }} scale-75">
                                                                    {{ $service->is_active ? 'Active' : 'Private' }}
                                                                </span>
                                                            </div>
                                                            <p class="small text-muted mb-0 lh-sm">{{ $service->description ?: 'No specific details provided for this service.' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-center py-5">
                                                    <i class="bx bx-briefcase-alt text-muted display-4 opacity-50"></i>
                                                    <p class="text-muted mt-3">This partner hasn't listed any specific services yet.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Tab -->
                                <div class="tab-pane fade" id="nav-location" role="tabpanel">
                                    <div class="p-4 border rounded shadow-xs bg-light bg-opacity-10 h-100" style="min-height: 400px;">
                                        <h5 class="fw-bold mb-3">Interactive Map Pin</h5>
                                        <p class="text-muted small"><i class="bx bx-info-circle me-1"></i> Exact GPS Coordinates: {{ $partner->latitude }}, {{ $partner->longitude }}</p>
                                        @if($partner->latitude && $partner->longitude)
                                            <div id="partner-map" class="rounded border shadow-sm" style="height: 350px; width: 100%;"></div>
                                        @else
                                            <div class="bg-white rounded border d-flex align-items-center justify-content-center" style="height: 350px;">
                                                <div class="text-center">
                                                    <i class="bx bx-map-alt text-muted display-3"></i>
                                                    <p class="text-muted mt-2">No coordinates set for this partner.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light border-top text-end p-4">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary me-2">Back to Dashboard</a>
                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                    <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn btn-primary px-4 shadow">
                        <i class="bx bx-edit me-1"></i> Edit Partner Profile
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if($partner->latitude && $partner->longitude)
<script>
    function initPartnerMap() {
        const position = { lat: parseFloat('{{ $partner->latitude }}'), lng: parseFloat('{{ $partner->longitude }}') };
        const map = new google.maps.Map(document.getElementById('partner-map'), {
            center: position,
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false,
        });
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: '{{ $partner->name }}'
        });
    }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initPartnerMap"></script>
@endif

<style>
.bg-light-soft {
    background-color: #f8f9fa;
}
.shadow-xs {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.045);
}
.nav-tabs .nav-link {
    font-size: 0.95rem;
    padding: 1.15rem 1rem;
    color: #6c757d;
    font-weight: 500;
    border: none;
    border-bottom: 3px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #696cff !important;
    background-color: transparent !important;
    border-bottom: 3px solid #696cff !important;
}
.gallery-thumb {
    transition: all 0.2s ease;
}
.gallery-thumb:hover {
    border-color: #696cff !important;
    transform: scale(1.05);
    opacity: 0.85;
}
.transition-2s {
    transition: all 0.2s ease;
}
.hover-stat:hover {
    border-color: #696cff !important;
    transform: translateY(-3px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
}
.avatar-text-large {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e7e7ff;
    color: #696cff;
    font-size: 3rem;
    font-weight: bold;
    border-radius: 50%;
}
</style>
@endsection
