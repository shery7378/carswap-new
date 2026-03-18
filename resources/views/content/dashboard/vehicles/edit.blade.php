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
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $vehicle->title) }}"
                                        required>
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
                                    <label class="form-label">Body Type (Design)</label>
                                    <select class="form-select" name="body_type_id">
                                        <option value="">Select Body Type</option>
                                        @foreach($bodyTypes as $body)
                                            <option value="{{ $body->id }}" {{ $vehicle->body_type_id == $body->id ? 'selected' : '' }}>{{ $body->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                    <label class="form-label">Year</label>
                                    <input type="number" class="form-control" name="year" value="{{ old('year', $vehicle->year) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mileage (km)</label>
                                    <input type="number" class="form-control" name="mileage"
                                        value="{{ old('mileage', $vehicle->mileage) }}">
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
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="tab-pane fade" id="content-price">
                            <h4 class="mb-4">Price</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Regular Price</label>
                                    <input type="number" class="form-control" name="price" value="{{ old('price', $vehicle->price) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" class="form-control" name="currency"
                                        value="{{ old('currency', $vehicle->currency) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Regular Price Label</label>
                                    <input type="text" class="form-control" name="regular_price_label"
                                        value="{{ old('regular_price_label', $vehicle->regular_price_label) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sale Price</label>
                                    <input type="number" class="form-control" name="sale_price"
                                        value="{{ old('sale_price', $vehicle->sale_price) }}">
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
                                            class="rounded border">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="main_image">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Gallery Images (Upload to add more)</label>
                                @php
                                    $gallery = $vehicle->gallery_images;
                                    if (is_string($gallery)) $gallery = json_decode($gallery, true);
                                @endphp
                                @if($gallery && is_array($gallery))
                                    <div class="row mb-2 g-2">
                                        @foreach($gallery as $img)
                                            <div class="col-md-3 mb-2">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Gallery"
                                                    class="img-fluid rounded border shadow-sm" style="height: 100px; width: 100%; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="gallery_images[]" multiple>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Video URL</label>
                                <input type="text" class="form-control" name="video_url" value="{{ old('video_url', $vehicle->video_url) }}">
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="tab-pane fade" id="content-location">
                            <h4 class="mb-4">Location</h4>
                            <div class="mb-3">
                                <label class="form-label">City/Location</label>
                                <input type="text" class="form-control" name="location" id="location" value="{{ old('location', $vehicle->location) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-map-pin"></i></span>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address', $vehicle->address) }}" placeholder="Search for address..." required>
                                </div>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $vehicle->latitude) }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $vehicle->longitude) }}">
                                <div id="map" class="mt-2 rounded border" style="width: 100%; height: 300px; {{ $vehicle->latitude ? '' : 'display: none;' }}"></div>
                                <small class="text-muted mt-1 d-block"><i class="bx bx-info-circle me-1"></i>Search an address to update the location on the map.</small>
                            </div>
                        </div>

                        <!-- Other Details -->
                        <div class="tab-pane fade" id="content-details">
                            <h4 class="mb-4">Other Details</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Technical Expiration Date</label>
                                    <input type="date" class="form-control" name="technical_expiration" value="{{ $vehicle->technical_expiration ? $vehicle->technical_expiration->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle History Report</label>
                                    <input type="text" class="form-control" name="history_report" value="{{ old('history_report', $vehicle->history_report) }}">
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
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chassis Number (VIN)</label>
                                    <input type="text" class="form-control" name="vin_number"
                                        value="{{ old('vin_number', $vehicle->vin_number) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cylinder Capacity</label>
                                    <input type="text" class="form-control" name="cylinder_capacity"
                                        value="{{ old('cylinder_capacity', $vehicle->cylinder_capacity) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Performance (Power)</label>
                                    <input type="text" class="form-control" name="performance"
                                        value="{{ old('performance', $vehicle->performance) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Engine Number</label>
                                    <input type="text" class="form-control" name="engine_number"
                                        value="{{ old('engine_number', $vehicle->engine_number) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Exterior Color</label>
                                    <select class="form-select" name="exterior_color_id">
                                        <option value="">Select Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $vehicle->exterior_color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Interior Color</label>
                                    <select class="form-select" name="interior_color_id">
                                        <option value="">Select Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $vehicle->interior_color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ad Status</label>
                                    <select class="form-select" name="ad_status">
                                        <option value="published" {{ $vehicle->ad_status == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft" {{ $vehicle->ad_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ $vehicle->ad_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ $vehicle->ad_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                                            {{ $vehicle->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFeatured">Mark as Featured</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description"
                                        rows="4">{{ old('description', $vehicle->description) }}</textarea>
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
        document.addEventListener('DOMContentLoaded', function () {
            const brandSelect = document.getElementById('brand_select');
            const modelSelect = document.getElementById('model_select');

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
                        }
                });
        });
    });

    // Google Maps & Places Autocomplete Logic
    function initAutocomplete() {
        const addressInput = document.getElementById('address');
        const locationInput = document.getElementById('location');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const mapDiv = document.getElementById('map');

        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            fields: ["geometry", "formatted_address", "address_components"],
        });

        let map = null;
        let marker = null;

        // Function to create/update map and marker
        function updateMap(lat, lng) {
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
        }

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

            // Try to extract city from address components if location is empty
            if (place.address_components) {
                for (const component of place.address_components) {
                    if (component.types.includes('locality')) {
                        locationInput.value = component.long_name;
                        break;
                    }
                }
            }

            updateMap(lat, lng);
        });

        // Handle initial map load for existing data
        if (latInput.value && lngInput.value) {
            const initialLat = parseFloat(latInput.value);
            const initialLng = parseFloat(lngInput.value);
            if (!isNaN(initialLat) && !isNaN(initialLng)) {
                updateMap(initialLat, initialLng);
            }
        }
    }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initAutocomplete"></script>
@endsection