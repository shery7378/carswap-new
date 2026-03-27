@extends('layouts/contentNavbarLayout')

@section('title', 'Partner Details - ' . $partner->name)

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <!-- Partner Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        @if($partner->image)
                            <img class="img-fluid rounded mb-3 pt-1 shadow-xs" src="{{ asset('storage/' . $partner->image) }}" height="100" width="100" alt="{{ $partner->name }}">
                        @else
                            <div class="avatar-text-large mb-3 shadow-xs">
                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="user-info text-center">
                            <h4 class="mb-2">{{ $partner->name }}</h4>
                            <span class="badge {{ $partner->is_active ? 'bg-label-success' : 'bg-label-danger' }}">{{ $partner->is_active ? 'Active' : 'Inactive' }} Partner</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-around flex-wrap mt-4 py-3 border-top border-bottom">
                    <div class="d-flex align-items-start me-4 mt-3 gap-2">
                        <span class="badge bg-label-primary p-2 rounded"><i class="bx bx-briefcase bx-sm"></i></span>
                        <div>
                            <p class="mb-0 fw-bold">{{ $partner->services->count() }}</p>
                            <small>Services</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mt-3 gap-2">
                        <span class="badge bg-label-info p-2 rounded"><i class="bx bx-time bx-sm"></i></span>
                        <div>
                            <p class="mb-0 fw-bold">{{ $partner->openingHours->where('is_closed', 0)->count() }}</p>
                            <small>Open Days</small>
                        </div>
                    </div>
                </div>
                <h5 class="pb-2 border-bottom mb-4 mt-4 fw-bold">Contact Details</h5>
                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <span class="fw-bold me-2">Email:</span>
                            <span>{{ $partner->email ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold me-2">Phone:</span>
                            <span>{{ $partner->phone ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold me-2">Address:</span>
                            <span>{{ $partner->address ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold me-2">Website:</span>
                            <a href="{{ $partner->website }}" target="_blank" class="text-primary">{{ $partner->website ?? 'N/A' }}</a>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center pt-3 gap-2">
                        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn btn-primary btn-sm me-3">Edit Details</a>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-label-secondary btn-sm">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Opening Hours -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Opening Hours</h5>
                @if($partner->show_opening_hours)
                <ul class="list-group list-group-flush list-group-sm">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        @php $hours = $partner->openingHours->where('day', $day)->first(); @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="fw-medium">{{ $day }}</span>
                            @if($hours && !$hours->is_closed)
                                <span class="badge bg-label-info">{{ \Carbon\Carbon::parse($hours->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($hours->close_time)->format('H:i') }}</span>
                            @else
                                <span class="badge bg-label-danger">Closed</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted text-center py-2">Not publicly displayed.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <!-- Partner Tabs -->
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-services" aria-controls="navs-top-services" aria-selected="true">Our Services</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-gallery" aria-controls="navs-top-gallery" aria-selected="false">Gallery</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-desc" aria-controls="navs-top-desc" aria-selected="false">Description</button>
                </li>
            </ul>
            <div class="tab-content border-top-0 pt-0">
                <!-- Services Tab -->
                <div class="tab-pane fade show active" id="navs-top-services" role="tabpanel">
                    <div class="row g-4 mt-2">
                        @forelse($partner->services as $service)
                            <div class="col-md-6">
                                <div class="card h-100 border bg-light-soft">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-check-double text-success me-2 bx-sm"></i>
                                            <h6 class="mb-0 fw-bold">{{ $service->name }}</h6>
                                        </div>
                                        <p class="small text-muted mb-0">{{ $service->description ?? 'No extra info provided.' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">No services listed yet.</div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Gallery Tab -->
                <div class="tab-pane fade mt-4" id="navs-top-gallery" role="tabpanel">
                    <div class="row g-2">
                        @if($partner->gallery)
                            @foreach($partner->gallery as $img)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-xs" style="height: 150px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center py-4">No gallery images available.</div>
                        @endif
                    </div>
                </div>

                <!-- Description Tab -->
                <div class="tab-pane fade mt-4" id="navs-top-desc" role="tabpanel">
                    <div class="px-2 py-3">
                        {!! nl2br(e($partner->description ?? 'No detailed description available.')) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordinates Info -->
        @if($partner->latitude && $partner->longitude)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bx bx-map-pin me-2 text-danger"></i> Location Coordinates</h5>
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block uppercase fw-semibold mb-1">Latitude</small>
                        <span class="fw-bold">{{ $partner->latitude }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block uppercase fw-semibold mb-1">Longitude</small>
                        <span class="fw-bold">{{ $partner->longitude }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.avatar-text-large {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg, #696cff 0%, #3a3dfb 100%);
    color: #fff;
    font-weight: 700;
    font-size: 40px;
}
.bg-light-soft {
    background-color: #fcfcfd;
}
.list-group-sm .list-group-item {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endsection
