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
                <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-info" aria-controls="navs-top-info" aria-selected="true">Basic Info</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-hours" aria-controls="navs-top-hours" aria-selected="false">Opening Hours</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-services" aria-controls="navs-top-services" aria-selected="false">Services</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-gallery" aria-controls="navs-top-gallery" aria-selected="false">Gallery</button>
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
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" />
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label" for="is_active">Is Active</label>
                                    </div>
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
                            </div>

                            <!-- Services Tab -->
                            <div class="tab-pane fade" id="navs-top-services" role="tabpanel">
                                <div id="services-container">
                                    <!-- Dynamic services will be added here -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-service">Add Service</button>
                            </div>
                            <!-- Gallery Tab -->
                            <div class="tab-pane fade" id="navs-top-gallery" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label" for="gallery">Gallery Photos</label>
                                    <input type="file" class="form-control @error('gallery.*') is-invalid @enderror" id="gallery" name="gallery[]" multiple />
                                    <small class="text-muted">You can select multiple images.</small>
                                    @error('gallery.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Partner</button>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = 0;
    const container = document.getElementById('services-container');
    const addButton = document.getElementById('add-service');

    addButton.addEventListener('click', function() {
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
        container.insertAdjacentHTML('beforeend', html);
        serviceIndex++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-service')) {
            e.target.closest('.service-item').remove();
        }
    });
});
</script>
@endsection
