@extends('layouts/contentNavbarLayout')

@section('title', 'Add Partner')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Partner</h5>
                <small class="text-muted float-end">Partner Details</small>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
                    @csrf

                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" id="tab-info" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-info" aria-controls="navs-top-info" aria-selected="true">Basic Info</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" id="tab-hours" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-hours" aria-controls="navs-top-hours" aria-selected="false">Opening Hours</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" id="tab-services" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-services" aria-controls="navs-top-services" aria-selected="false">Services</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" id="tab-gallery" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-gallery" aria-controls="navs-top-gallery" aria-selected="false">Gallery</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane fade show active" id="navs-top-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="name">Partner Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Partner Name" required />
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="partner@example.com" />
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890" />
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="website">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website') }}" placeholder="https://example.com" />
                                        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Enter Address" />
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Partner Description">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="image">Partner Logo</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewLogo(this)" />
                                    <div id="logo-preview" class="mt-2 d-none">
                                        <img id="logo-img" src="" alt="Logo Preview" class="rounded border" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Is Active</label>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="button" class="btn btn-primary" onclick="switchTab('tab-hours')">Next Step</button>
                                </div>
                            </div>

                            <!-- Opening Hours Tab -->
                            <div class="tab-pane fade" id="navs-top-hours" role="tabpanel">
                                @foreach($days as $day)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-2">
                                        <strong>{{ $day }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="time" name="opening_hours[{{ $day }}][open]" class="form-control" placeholder="Open">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="time" name="opening_hours[{{ $day }}][close]" class="form-control" placeholder="Close">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="opening_hours[{{ $day }}][is_closed]" id="closed_{{ $day }}">
                                            <label class="form-check-label" for="closed_{{ $day }}">Closed</label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="switchTab('tab-info')">Previous</button>
                                    <button type="button" class="btn btn-primary" onclick="switchTab('tab-services')">Next Step</button>
                                </div>
                            </div>

                            <!-- Services Tab -->
                            <div class="tab-pane fade" id="navs-top-services" role="tabpanel">
                                <div id="services-container">
                                    <!-- Dynamic services will be added here -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-service">Add Service</button>
                                
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="switchTab('tab-hours')">Previous</button>
                                    <button type="button" class="btn btn-primary" onclick="switchTab('tab-gallery')">Next Step</button>
                                </div>
                            </div>

                            <!-- Gallery Tab -->
                            <div class="tab-pane fade" id="navs-top-gallery" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">Gallery Photos</label>
                                    <div id="gallery-container" class="row g-3 mb-3">
                                        <!-- Dynamic image slots will appear here -->
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="btn-add-gallery">
                                        <i class="bx bx-plus me-1"></i> Add More Photos
                                    </button>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="switchTab('tab-services')">Previous</button>
                                    <button type="submit" class="btn btn-primary">Create Partner</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabId) {
    var tabEl = document.getElementById(tabId);
    var tab = new bootstrap.Tab(tabEl);
    tab.show();
}

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
    // Gallery Logic
    const galleryContainer = document.getElementById('gallery-container');
    const addGalleryBtn = document.getElementById('btn-add-gallery');

    function addGallerySlot() {
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-6 mb-3 position-relative';
        col.innerHTML = `
            <div class="card border rounded overflow-hidden">
                <div class="preview-area text-center bg-light d-flex align-items-center justify-content-center" style="height: 120px; border-bottom: 1px dashed #ddd;">
                    <i class="bx bx-image text-muted" style="font-size: 2rem;"></i>
                </div>
                <div class="p-2">
                    <input type="file" name="gallery[]" class="form-control form-control-sm" onchange="handleGalPreview(this)">
                </div>
                <button type="button" class="btn btn-danger btn-sm p-1 position-absolute top-0 end-0 m-2" onclick="this.closest('.col-md-3').remove()" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        `;
        galleryContainer.appendChild(col);
    }

    // Add first gallery slot by default
    addGallerySlot();

    addGalleryBtn.addEventListener('click', function() {
        addGallerySlot();
    });

    // Service Logic
    let serviceIndex = 0;
    const servicesContainer = document.getElementById('services-container');
    const addServiceBtn = document.getElementById('add-service');

    addServiceBtn.addEventListener('click', function() {
        const html = `
            <div class="service-item border p-3 mb-3 rounded">
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="services[${serviceIndex}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[${serviceIndex}][is_active]" id="service_active_${serviceIndex}" checked>
                            <label class="form-check-label" for="service_active_${serviceIndex}">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="services[${serviceIndex}][description]" class="form-control" rows="2"></textarea>
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-service">Remove</button>
            </div>
        `;
        servicesContainer.insertAdjacentHTML('beforeend', html);
        serviceIndex++;
    });

    servicesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service')) {
            e.target.closest('.service-item').remove();
        }
    });
});
</script>
@endsection
