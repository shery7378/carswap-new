@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vehicles List</h5>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('admin.vehicles.index') }}" method="GET" class="me-3">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </form>
                        @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-vehicles', 'admin-guard'))
                        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">Add New Vehicle</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="vehicles-table">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Vehicle</th>
                                    <th>User</th>
                                    <th>Year</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            @if($vehicle->main_image)
                                                <a href="{{ route('admin.vehicles.show', $vehicle->id) }}">
                                                    <img src="{{ asset('storage/' . $vehicle->main_image) }}"
                                                        alt="{{ $vehicle->title }}" width="50" class="rounded shadow-xs">
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="text-body fw-bold">
                                                {{ $vehicle->title }}
                                            </a><br>
                                            <small class="text-muted">{{ optional($vehicle->brand)->name }}
                                                {{ optional($vehicle->model)->name }}</small>
                                        </td>
                                        <td>
                                            @if($vehicle->user)
                                                {{ $vehicle->user->first_name }} {{ $vehicle->user->last_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $vehicle->year }}</td>
                                        <td>{{ number_format($vehicle->price, 0, ',', ' ') }} Ft</td>
                                        <td>
                                            <small>
                                                Fuel: {{ optional($vehicle->fuelType)->name }}<br>
                                                Trans: {{ optional($vehicle->transmission)->name }}<br>
                                                Mileage: {{ $vehicle->mileage }} km
                                            </small>
                                        </td>
                                        <td>
                                            @if ($vehicle->is_featured)
                                                <span class="badge bg-label-warning me-1">Featured</span>
                                            @endif

                                            @php
                                                $statusClass = match ($vehicle->ad_status) {
                                                    'published' => 'bg-label-success',
                                                    'pending' => 'bg-label-warning',
                                                    'rejected' => 'bg-label-danger',
                                                    'draft' => 'bg-label-secondary',
                                                    default => 'bg-label-primary',
                                                };
                                            @endphp
                                            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm {{ str_replace('bg-label', 'btn-outline', $statusClass) }} dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ ucfirst($vehicle->ad_status) }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="published">
                                                            <button type="submit" class="dropdown-item">Approve /
                                                                Publish</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="rejected">
                                                            <button type="submit" class="dropdown-item text-danger">Reject
                                                                / Hide</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="pending">
                                                            <button type="submit" class="dropdown-item">Set to
                                                                Pending</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($vehicle->ad_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.vehicles.show', $vehicle->id) }}">
                                                        <i class="bx bx-show me-1 text-primary"></i> View Details
                                                    </a>
                                                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.vehicles.edit', $vehicle->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    @endif
                                                    
                                                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard'))
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="bx bx-trash me-1"></i> Delete</button>
                                                    </form>
                                                    @endif

                                                    @if(!(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard')))
                                                        <span class="dropdown-item disabled">No permissions</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No vehicles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="mt-3">
                        {{ $vehicles->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Vehicle Details Modal -->
<div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" id="vehicleModalContent">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading vehicle details...</p>
            </div>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#vehicles-table').DataTable({
            "order": [[ 1, "asc" ]],
            "pageLength": 10,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            "language": {
                "search": "",
                "searchPlaceholder": "Search Vehicles...",
                "paginate": {
                    "next": '<i class="bx bx-chevron-right"></i>',
                    "previous": '<i class="bx bx-chevron-left"></i>'
                }
            }
        });

        // Handle Status Filter Change
        $('select[name="status"]').on('change', function() {
            this.form.submit();
        });

        // Handle Details View Click (Modal Trigger)
        $(document).on('click', '.view-vehicle-details', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const modalContent = $('#vehicleModalContent');
            const modalElement = document.getElementById('vehicleDetailsModal');
            let bootstrapModal = bootstrap.Modal.getInstance(modalElement);
            if (!bootstrapModal) {
                bootstrapModal = new bootstrap.Modal(modalElement);
            }
            
            // Reset modal content to loader
            modalContent.html(`
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading vehicle details...</p>
                </div>
            `);
            
            bootstrapModal.show();

            console.log("Fetching vehicle info for ID:", id);
            
            // Fetch Data
            $.ajax({
                url: `/app/vehicles/${id}`,
                method: 'GET',
                success: function(response) {
                    console.log("Success! Updating modal content.");
                    modalContent.html(response);
                },
                error: function(xhr) {
                    console.error("Error fetching vehicle details:", xhr);
                    const errorMsg = xhr.responseJSON?.message || 'Failed to load vehicle details. Please try again.';
                    modalContent.html(`
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title text-danger"><i class="bx bx-error me-1"></i> Data Load Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-5">
                            <i class="bx bx-error-circle fs-1 text-danger"></i>
                            <h5 class="mt-3">Request Failed</h5>
                            <p class="text-muted">${errorMsg}</p>
                            <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-dismiss="modal">Close</button>
                        </div>
                    `);
                }
            });
        });
    });
</script>
<style>
.dataTables_filter input {
    border-radius: 0.5rem;
    padding: 0.375rem 0.75rem;
    border: 1px solid #d9dee3;
    margin-bottom: 1rem;
    margin-left: 0.5rem;
    width: 250px;
    outline: none;
}
.dataTables_filter input:focus {
    border-color: #696cff;
}
.dataTables_paginate .pagination {
    justify-content: flex-end !important;
}
.dataTables_length {
    margin-bottom: 1rem;
}
.dataTables_length label {
    display: flex;
    align-items: center;
    font-weight: 400;
}
.dataTables_length select {
    border-radius: 0.5rem;
    padding: 0.375rem 2rem 0.375rem 0.75rem;
    border: 1px solid #d9dee3;
    margin: 0 0.5rem;
    outline: none;
    -webkit-appearance: auto;
    -moz-appearance: auto;
    appearance: auto;
}
.dataTables_length select:focus {
    border-color: #696cff;
}
</style>
@endsection