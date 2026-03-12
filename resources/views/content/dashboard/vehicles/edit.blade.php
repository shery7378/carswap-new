@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Vehicle: ' . $vehicle->title)

@section('page-style')
    <style>
        .listing-manager .nav-link {
            border-radius: 0;
            text-align: left;
            padding: 1rem;
            background: #f8f9fa;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }

        .listing-manager .nav-link.active {
            background: #007bff;
            color: #fff;
        }

        .listing-manager .nav-link i {
            margin-right: 10px;
        }

        .listing-manager .tab-content {
            border: 1px solid #dee2e6;
            border-left: none;
            padding: 2rem;
        }
    </style>
@endsection

@section('content')
    <div class="card listing-manager">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Edit Vehicle: {{ $vehicle->title }}</h5>
        </div>
        <div class="row g-0">
            <div class="col-md-3 border-end">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#content-options" type="button"><i
                            class="bx bx-car"></i> Options</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-price" type="button"><i
                            class="bx bx-dollar"></i> Price</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-features" type="button"><i
                            class="bx bx-check-square"></i> Extra Features</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-images" type="button"><i
                            class="bx bx-image"></i> Images & Videos</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-location" type="button"><i
                            class="bx bx-map"></i> Location</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-featured" type="button"><i
                            class="bx bx-bookmark-star"></i> Mark as Featured</button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#content-details" type="button"><i
                            class="bx bx-list-ul"></i> Other Details</button>
                </div>
            </div>
            <div class="col-md-9">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">Validation Errors:</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="tab-content">
                        <!-- Options Section -->
                        <div class="tab-pane fade show active" id="content-options">
                            <h4 class="mb-4">Options</h4>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" value="{{ $vehicle->title }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand</label>
                                    <select class="form-select" name="brand_id" id="brand_select">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ $vehicle->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Model</label>
                                    <select class="form-select" name="model_id" id="model_select">
                                        <option value="">Select Model</option>
                                        @foreach($models as $model)
                                            <option value="{{ $model->id }}" {{ $vehicle->model_id == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fuel Type</label>
                                    <select class="form-select" name="fuel_type_id">
                                        <option value="">Select Fuel Type</option>
                                        @foreach($fuelTypes as $fuel)
                                            <option value="{{ $fuel->id }}" {{ $vehicle->fuel_type_id == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Transmission</label>
                                    <select class="form-select" name="transmission_id">
                                        <option value="">Select Transmission</option>
                                        @foreach($transmissions as $trans)
                                            <option value="{{ $trans->id }}" {{ $vehicle->transmission_id == $trans->id ? 'selected' : '' }}>{{ $trans->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Drive Type</label>
                                    <select class="form-select" name="drive_type_id">
                                        <option value="">Select Drive Type</option>
                                        @foreach($driveTypes as $drive)
                                            <option value="{{ $drive->id }}" {{ $vehicle->drive_type_id == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Body Type (Design)</label>
                                    <select class="form-select" name="body_type_id">
                                        <option value="">Select Body Type</option>
                                        @foreach($bodyTypes as $body)
                                            <option value="{{ $body->id }}" {{ $vehicle->body_type_id == $body->id ? 'selected' : '' }}>{{ $body->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Year</label>
                                    <input type="number" class="form-control" name="year" value="{{ $vehicle->year }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mileage (km)</label>
                                    <input type="number" class="form-control" name="mileage"
                                        value="{{ $vehicle->mileage }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Exterior Color</label>
                                    <select class="form-select" name="exterior_color_id">
                                        <option value="">Select Exterior Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $vehicle->exterior_color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Interior Color</label>
                                    <select class="form-select" name="interior_color_id">
                                        <option value="">Select Interior Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $vehicle->interior_color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="tab-pane fade" id="content-price">
                            <h4 class="mb-4">Price</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Regular Price</label>
                                    <input type="number" class="form-control" name="price" value="{{ $vehicle->price }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Regular Price Label</label>
                                    <input type="text" class="form-control" name="regular_price_label"
                                        value="{{ $vehicle->regular_price_label }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Regular Price Description</label>
                                    <textarea class="form-control" name="regular_price_description"
                                        rows="2">{{ $vehicle->regular_price_description }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price</label>
                                    <input type="number" class="form-control" name="sale_price"
                                        value="{{ $vehicle->sale_price }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price Label</label>
                                    <input type="text" class="form-control" name="sale_price_label"
                                        value="{{ $vehicle->sale_price_label }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Instant Savings Label</label>
                                    <input type="text" class="form-control" name="instant_savings_label"
                                        value="{{ $vehicle->instant_savings_label }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" class="form-control" name="currency"
                                        value="{{ $vehicle->currency }}">
                                </div>
                            </div>
                        </div>

                        <!-- Extra Features -->
                        <div class="tab-pane fade" id="content-features">
                            <h4 class="mb-4">Extra Features</h4>
                            <div class="row">
                                @php $vehiclePropertyIds = $vehicle->properties->pluck('id')->toArray(); @endphp
                                @foreach($properties as $prop)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="properties[]"
                                                value="{{ $prop->id }}" id="prop_{{ $prop->id }}" {{ in_array($prop->id, $vehiclePropertyIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="prop_{{ $prop->id }}">{{ $prop->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div class="tab-pane fade" id="content-images">
                            <h4 class="mb-4">Images & Videos</h4>
                            <div class="mb-4">
                                <label class="form-label">Main Image</label>
                                @if($vehicle->main_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $vehicle->main_image) }}" alt="Main" width="120"
                                            class="rounded">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="main_image">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Gallery Images (Upload to add more)</label>
                                @php
                                    $gallery = $vehicle->gallery_images;
                                    if (is_string($gallery)) {
                                        $gallery = json_decode($gallery, true);
                                    }
                                @endphp
                                @if($gallery && is_array($gallery))
                                    <div class="row mb-2">
                                        @foreach($gallery as $img)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Gallery"
                                                    class="img-fluid rounded border">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="gallery_images[]" multiple>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Video URL</label>
                                <input type="text" class="form-control" name="video_url" value="{{ $vehicle->video_url }}">
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="tab-pane fade" id="content-location">
                            <h4 class="mb-4">Location</h4>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ $vehicle->address }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-control" name="latitude"
                                        value="{{ $vehicle->latitude }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-control" name="longitude"
                                        value="{{ $vehicle->longitude }}">
                                </div>
                            </div>
                        </div>

                        <!-- Featured Section -->
                        <div class="tab-pane fade" id="content-featured">
                            <h4 class="mb-4">Mark as Featured</h4>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                                    {{ $vehicle->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">Featured Vehicle</label>
                            </div>
                        </div>

                        <!-- Other Details -->
                        <div class="tab-pane fade" id="content-details">
                            <h4 class="mb-4">Other Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle Status</label>
                                    <select class="form-select" name="vehicle_status_id">
                                        <option value="">Select Status</option>
                                        @foreach($vehicleStatuses as $status)
                                            <option value="{{ $status->id }}" {{ $vehicle->vehicle_status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sales Method</label>
                                    <select class="form-select" name="sales_method_id">
                                        <option value="">Select Sales Method</option>
                                        @foreach($salesMethods as $method)
                                            <option value="{{ $method->id }}" {{ $vehicle->sales_method_id == $method->id ? 'selected' : '' }}>{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cylinder Capacity</label>
                                    <input type="text" class="form-control" name="cylinder_capacity"
                                        value="{{ $vehicle->cylinder_capacity }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Performance</label>
                                    <input type="text" class="form-control" name="performance"
                                        value="{{ $vehicle->performance }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">VIN Number</label>
                                    <input type="text" class="form-control" name="vin_number"
                                        value="{{ $vehicle->vin_number }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Engine Number</label>
                                    <input type="text" class="form-control" name="engine_number"
                                        value="{{ $vehicle->engine_number }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Document Type</label>
                                    <select class="form-select" name="document_type_id">
                                        <option value="">Select Document Type</option>
                                        @foreach($documentTypes as $doc)
                                            <option value="{{ $doc->id }}" {{ $vehicle->document_type_id == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description"
                                        rows="4">{{ $vehicle->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top text-end p-3">
                        <button type="submit" class="btn btn-primary">Update Vehicle</button>
                        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
            document.addEventListener('DOMContentLoaded', fu nctio               n () {
                const brandSelect = document.getElementById('brand_select');
                const modelSelect = document.getElementById('model_select');

                if (!brandSelect || !modelSelect) {
                    console.error('Selects not found');
                    return;
                }

                brandSelect.addEventListener('change', function () {
                    const brandId = this.value;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">Select Model</option>';
                        return;
                    }

                    modelSelect.innerHTML = '<option value="">Loading...</option>';

                    fetch(`/api/brands/${brandId}/models`)
                        .then(response => response.json())
                        .then(data => {
                            modelSelect.innerHTML = '<option value="">Select Model</option>';
                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(model => {
                                    const option = document.createElement('option');
                                    option.value = model.id;
                                    option.textContent = model.name;
                                    modelSelect.appendChild(option);
                                });
                            } else {
                                modelSelect.innerHTML = '<option value="">No models found</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading models:', error);
                            modelSelect.innerHTML = '<option value="">Error loading models</option>';
                        });
                });
            });
        </script>
@endsection