@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Partner')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Partner: {{ $partner->name }}</h5>
                <small class="text-muted float-end">Partner Details</small>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                        </ul>
                        <div class="tab-content">
                            <!-- Basic Info Tab -->
                            <div class="tab-pane fade show active" id="navs-top-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="name">Partner Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partner->name) }}" required />
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $partner->email) }}" />
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $partner->phone) }}" />
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="website">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $partner->website) }}" />
                                        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $partner->address) }}" />
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $partner->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="image">Partner Logo</label>
                                    @if($partner->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $partner->image) }}" alt="Logo" width="100" class="rounded">
                                    </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" />
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $partner->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Is Active</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Opening Hours Tab -->
                            <div class="tab-pane fade" id="navs-top-hours" role="tabpanel">
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
                                    <div class="col-md-2 text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="checkbox" name="opening_hours[{{ $day }}][is_closed]" id="closed_{{ $day }}" {{ ($hour && $hour->is_closed) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="closed_{{ $day }}">Closed</label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Services Tab -->
                            <div class="tab-pane fade" id="navs-top-services" role="tabpanel">
                                <div id="services-container">
                                    @foreach($partner->services as $index => $service)
                                    <div class="service-item border p-3 mb-3 rounded">
                                        <input type="hidden" name="services[{{ $index }}][id]" value="{{ $service->id }}">
                                        <div class="row">
                                            <div class="col-md-10 mb-2">
                                                <label class="form-label">Service Name</label>
                                                <input type="text" name="services[{{ $index }}][name]" class="form-control" value="{{ $service->name }}">
                                            </div>
                                            <div class="col-md-2 mb-2 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="services[{{ $index }}][is_active]" id="service_active_{{ $index }}" {{ $service->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="service_active_{{ $index }}">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Description</label>
                                            <textarea name="services[{{ $index }}][description]" class="form-control" rows="2">{{ $service->description }}</textarea>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-service">Add Another Service</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Partner</button>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let serviceIndex = {{ $partner->services->count() }};
    const container = document.getElementById('services-container');
    const addButton = document.getElementById('add-service');

    addButton.addEventListener('click', function() {
        const html = `
            <div class="service-item border p-3 mb-3 rounded">
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <label class="form-label">Service Name</label>
                        <input type="text" name="services[${serviceIndex}][name]" class="form-control">
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
