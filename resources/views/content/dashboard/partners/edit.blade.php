@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Partner')

@section('content')
<div class="row">
    <div class="col-xl-9 col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Partner: {{ $partner->name }}</h5>
                <a href="{{ route('admin.partners.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" id="partnerForm">
                    @csrf
                    @method('PUT')

                    <!-- Company Info Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Basic Information</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="name">Company Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partner->name) }}" required />
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="description">Introduction *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $partner->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Services</h6>
                        <div id="services-container">
                            @foreach($partner->services as $index => $service)
                            <div class="service-item border p-3 mb-3 rounded bg-light">
                                <input type="hidden" name="services[{{ $index }}][id]" value="{{ $service->id }}">
                                <div class="row">
                                    <div class="col-md-9 mb-2">
                                        <label class="form-label">Service Title</label>
                                        <input type="text" name="services[{{ $index }}][name]" class="form-control bg-white" value="{{ old("services.$index.name", $service->name) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-2 d-flex align-items-end justify-content-between">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[{{ $index }}][is_active]" id="service_active_{{ $index }}" {{ $service->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_active_{{ $index }}">Published</label>
                                        </div>
                                        <button type="button" class="btn btn-icon btn-sm btn-outline-danger remove-service mb-2"><i class="bx bx-trash"></i></button>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <textarea name="services[{{ $index }}][description]" class="form-control bg-white" rows="2">{{ old("services.$index.description", $service->description) }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-service">
                            <i class="bx bx-plus me-1"></i>Add More Service
                        </button>
                    </div>

                    <!-- Opening Hours Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h6 class="fw-bold mb-0">Opening Hours</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_opening_hours" name="show_opening_hours" value="1" {{ $partner->show_opening_hours ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_opening_hours">Opening hours WILL BE DISPLAYED</label>
                            </div>
                        </div>
                        
                        <div id="opening-hours-wrapper" style="{{ $partner->show_opening_hours ? '' : 'opacity: 0.5; pointer-events: none;' }}">
                            @foreach($days as $day)
                            @php
                                $hour = $partner->openingHours->firstWhere('day', $day);
                            @endphp
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-2">
                                    <strong>{{ $day }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <input type="time" name="opening_hours[{{ $day }}][open]" class="form-control" value="{{ $hour ? $hour->open_time : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="time" name="opening_hours[{{ $day }}][close]" class="form-control" value="{{ $hour ? $hour->close_time : '' }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check pt-1">
                                        <input class="form-check-input" type="checkbox" name="opening_hours[{{ $day }}][is_closed]" id="closed_{{ $day }}" {{ ($hour && $hour->is_closed) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="closed_{{ $day }}">Closed</label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contact & Address Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Contact & Location</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">Phone Number *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $partner->phone) }}" required />
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $partner->email) }}" required />
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="address">Featured address / Cím *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-map-pin"></i></span>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $partner->address) }}" placeholder="Search for address or enter manually..." required />
                                </div>
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $partner->latitude) }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $partner->longitude) }}">
                                <div id="map" class="mt-2 rounded border" style="width: 100%; height: 300px; display: none;"></div>
                                <small class="text-muted mt-1 d-block"><i class="bx bx-info-circle me-1"></i>Search an address to set the exact location on the map.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Media (Logo & Gallery)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image (Logo)</label>
                                @if($partner->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $partner->image) }}" alt="Current Logo" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewLogo(this)" />
                                <div id="logo-preview" class="mt-2 d-none">
                                    <img id="logo-img" src="" alt="New Logo Preview" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Images Gallery</label>
                            
                            @if($partner->gallery && count($partner->gallery) > 0)
                            <div class="row g-2 mb-3">
                                @foreach($partner->gallery as $photo)
                                <div class="col-md-2 col-sm-4">
                                    <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded border" style="height: 100px; width: 100%; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div id="gallery-container" class="row g-3 mb-3">
                                <!-- New gallery slots added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-gallery">
                                <i class="bx bx-image-add me-1"></i> Add More Photos
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <div class="form-check form-switch pt-1">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $partner->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Partner is Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary px-5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Panel -->
    <div class="col-xl-3 col-lg-4">
        <div class="card mb-4 bg-light shadow-none border-0">
            <div class="card-body text-center">
                @if($partner->image)
                    <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" class="rounded-circle mb-3 border p-1" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="avatar avatar-xl mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($partner->name, 0, 1) }}</span>
                    </div>
                @endif
                <h5 class="mb-1">{{ $partner->name }}</h5>
                <p class="small text-muted">{{ $partner->email }}</p>
                <hr>
                <div class="d-flex justify-content-around">
                    <div>
                        <h6 class="mb-0">{{ count($partner->gallery ?? []) }}</h6>
                        <small>Photos</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $partner->services->count() }}</h6>
                        <small>Services</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3">SEO Preview</h6>
                <div class="bg-white p-2 rounded border small">
                    <div class="text-primary fw-bold" id="seo-title-preview">{{ $partner->name }} | CARSWAP</div>
                    <div class="text-success" style="font-size: 0.75rem;">https://carswap-backend.hexafume.com/p/{{ $partner->slug }}</div>
                    <div class="text-muted" id="seo-desc-preview" style="font-size: 0.75rem;">{{ Str::limit($partner->description, 160) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('show_opening_hours').addEventListener('change', function() {
    const wrapper = document.getElementById('opening-hours-wrapper');
    if (this.checked) {
        wrapper.style.opacity = '1';
        wrapper.style.pointerEvents = 'auto';
    } else {
        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';
    }
});

function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    const img = document.getElementById('logo-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('d-none');
    }
}

function handleGalPreview(input) {
    const card = input.closest('.card');
    const previewArea = card.querySelector('.preview-area');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewArea.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 120px; object-fit: cover;">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const galleryContainer = document.getElementById('gallery-container');
    const addGalleryBtn = document.getElementById('btn-add-gallery');

    addGalleryBtn.addEventListener('click', function() {
        const col = document.createElement('div');
        col.className = 'col-md-4 col-sm-6 mb-3 position-relative';
        col.innerHTML = `
            <div class="card border rounded overflow-hidden">
                <div class="preview-area text-center bg-light d-flex align-items-center justify-content-center" style="height: 120px; border-bottom: 1px dashed #ddd;">
                    <i class="bx bx-image text-muted" style="font-size: 2rem;"></i>
                </div>
                <div class="p-2">
                    <input type="file" name="gallery[]" class="form-control form-control-sm" onchange="handleGalPreview(this)">
                </div>
                <button type="button" class="btn btn-danger btn-sm p-1 position-absolute top-0 end-0 m-2" onclick="this.closest('.col-md-4').remove()" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        `;
        galleryContainer.appendChild(col);
    });

    let serviceIndex = {{ $partner->services->count() }};
    const servicesContainer = document.getElementById('services-container');
    const addServiceBtn = document.getElementById('add-service');

    addServiceBtn.addEventListener('click', function() {
        const html = `
            <div class="service-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-9 mb-2">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="services[${serviceIndex}][name]" class="form-control bg-white" placeholder="Service Name" required>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end justify-content-between">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[${serviceIndex}][is_active]" id="service_active_${serviceIndex}" checked>
                            <label class="form-check-label" for="service_active_${serviceIndex}">Published</label>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-outline-danger remove-service mb-2"><i class="bx bx-trash"></i></button>
                    </div>
                </div>
                <div class="mb-0">
                    <textarea name="services[${serviceIndex}][description]" class="form-control bg-white" rows="2" placeholder="Description"></textarea>
                </div>
            </div>
        `;
        servicesContainer.insertAdjacentHTML('beforeend', html);
        serviceIndex++;
    });

    servicesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            e.target.closest('.service-item').remove();
        }
    });

    // SEO Preview
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const seoTitle = document.getElementById('seo-title-preview');
    const seoDesc = document.getElementById('seo-desc-preview');

    nameInput.addEventListener('input', function() {
        seoTitle.textContent = (this.value || 'Partner Name') + ' | CARSWAP';
    });

    descInput.addEventListener('input', function() {
        seoDesc.textContent = this.value.substring(0, 160) || 'Write an introduction...';
    });

    // Form Submission Geocoding Fallback
    const form = document.getElementById('partnerForm');
    form.addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        const address = document.getElementById('address').value;

        if (address && (!lat || !lng)) {
            e.preventDefault();
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('latitude').value = results[0].geometry.location.lat();
                    document.getElementById('longitude').value = results[0].geometry.location.lng();
                    form.submit();
                } else {
                    Swal.fire({
                        title: 'Coordinates Missing',
                        text: 'We couldn\'t find the exact location for this address. Please select a valid address from the dropdown.',
                        icon: 'warning'
                    });
                }
            });
        }
    });
});

// Google Maps & Places Autocomplete Logic
function initAutocomplete() {
    const addressInput = document.getElementById('address');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const mapDiv = document.getElementById('map');

    const autocomplete = new google.maps.places.Autocomplete(addressInput, {
        fields: ["geometry", "formatted_address"],
    });

    let map = null;
    let marker = null;

    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();

        if (!place.geometry) {
            console.log("No details available for input: '" + place.name + "'");
            return;
        }

        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();

        // Set inputs
        addressInput.value = place.formatted_address;
        latInput.value = lat;
        lngInput.value = lng;

        // Display and update map
        mapDiv.style.display = 'block';
        const position = { lat: lat, lng: lng };
        
        if (!map) {
            map = new google.maps.Map(mapDiv, {
                center: position,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
            });
            marker = new google.maps.Marker({
                map: map,
                position: position,
                draggable: true 
            });

            marker.addListener('dragend', function() {
                const newPos = marker.getPosition();
                latInput.value = newPos.lat();
                lngInput.value = newPos.lng();
            });
        } else {
            map.setCenter(position);
            marker.setPosition(position);
        }
    });

    // Handle initial map load if editing and coordinates exist
    if (latInput.value && lngInput.value) {
        mapDiv.style.display = 'block';
        const initialPos = { lat: parseFloat(latInput.value), lng: parseFloat(lngInput.value) };
        map = new google.maps.Map(mapDiv, {
            center: initialPos,
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false,
        });
        marker = new google.maps.Marker({
            map: map,
            position: initialPos,
            draggable: true
        });
        marker.addListener('dragend', function() {
            const newPos = marker.getPosition();
            latInput.value = newPos.lat();
            lngInput.value = newPos.lng();
        });
    }
}
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initAutocomplete"></script>
@endsection
