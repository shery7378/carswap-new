@extends('layouts/contentNavbarLayout')

@section('title', 'Add Partner')

@section('content')
<div class="row">
    <div class="col-xl-9 col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Partner Details</h5>
                <small class="text-muted float-end">Enter partner data as requested</small>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
                    @csrf

                    <!-- Company Info Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Basic Information</h6>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="name">Company Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Company Name" required />
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="description">Introduction *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Write a short introduction..." required>{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Services</h6>
                        <div id="services-container">
                            <!-- Dynamic services added here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-service">
                            <i class="bx bx-plus me-1"></i>Add Service
                        </button>
                    </div>

                    <!-- Opening Hours Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h6 class="fw-bold mb-0">Opening Hours</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_opening_hours" name="show_opening_hours" value="1" checked>
                                <label class="form-check-label" for="show_opening_hours">Opening hours WILL BE DISPLAYED</label>
                            </div>
                        </div>
                        
                        <div id="opening-hours-wrapper">
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
                                <div class="col-md-2">
                                    <div class="form-check pt-1">
                                        <input class="form-check-input" type="checkbox" name="opening_hours[{{ $day }}][is_closed]" id="closed_{{ $day }}">
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
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 890" required />
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="email">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="info@company.com" required />
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="address">Featured address / Cím *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-map-pin"></i></span>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Search for address or enter manually..." required />
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bx bx-info-circle me-1"></i>Google Maps integration for address lookup</small>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Media (Logo & Gallery)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Featured Image *</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewLogo(this)" required />
                                <div id="logo-preview" class="mt-2 d-none">
                                    <img id="logo-img" src="" alt="Logo Preview" class="rounded border" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Images Gallery</label>
                            <div id="gallery-container" class="row g-3 mb-3">
                                <!-- Gallery slots will be added here -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-gallery">
                                <i class="bx bx-image-add me-1"></i> Add More Photos
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <div class="form-check form-switch pt-1">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Partner is Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary px-5">Publish Partner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info Panel (Optional, for better UX) -->
    <div class="col-xl-3 col-lg-4">
        <div class="card mb-4 bg-light border-0 shadow-none">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Form Guidance</h6>
                <p class="small text-muted mb-3">
                    Fields marked with <span class="text-danger">*</span> are required for the partner to be published.
                </p>
                <div class="alert alert-info py-2 px-3 small border-0 mb-0">
                    <i class="bx bx-bulb me-2"></i>
                    All partner data will be visible on the public frontend once activated.
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">SEO Preview</h6>
                <div class="bg-white p-2 rounded border small">
                    <div class="text-primary fw-bold" id="seo-title-preview">Partner Name | CARSWAP</div>
                    <div class="text-success" style="font-size: 0.75rem;">https://carswap-backend.hexafume.com/...</div>
                    <div class="text-muted" id="seo-desc-preview" style="font-size: 0.75rem;">Write an introduction to see SEO description preview...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle opening hours visibility logic (UI only for now)
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
    // Gallery Logic
    const galleryContainer = document.getElementById('gallery-container');
    const addGalleryBtn = document.getElementById('btn-add-gallery');

    function addGallerySlot() {
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
    }

    // Add first 2 physical slots by default to encourage gallery use
    addGallerySlot();
    addGallerySlot();

    addGalleryBtn.addEventListener('click', function() {
        addGallerySlot();
    });

    // Service Logic
    let serviceIndex = 0;
    const servicesContainer = document.getElementById('services-container');
    const addServiceBtn = document.getElementById('add-service');

    function createServiceItem() {
        const html = `
            <div class="service-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-9 mb-2">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="services[${serviceIndex}][name]" class="form-control bg-white" placeholder="e.g. Car Rental, Engine Repair" required>
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
                    <textarea name="services[${serviceIndex}][description]" class="form-control bg-white" rows="2" placeholder="Brief service description..."></textarea>
                </div>
            </div>
        `;
        servicesContainer.insertAdjacentHTML('beforeend', html);
        serviceIndex++;
    }

    // Add one service slot by default
    createServiceItem();

    addServiceBtn.addEventListener('click', function() {
        createServiceItem();
    });

    servicesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-service')) {
            e.target.closest('.service-item').remove();
        }
    });

    // SEO Preview Logic
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const seoTitle = document.getElementById('seo-title-preview');
    const seoDesc = document.getElementById('seo-desc-preview');

    nameInput.addEventListener('input', function() {
        seoTitle.textContent = (this.value || 'Partner Name') + ' | CARSWAP';
    });

    descInput.addEventListener('input', function() {
        seoDesc.textContent = this.value.substring(0, 160) || 'Write an introduction to see SEO description preview...';
    });
});
</script>
@endsection
