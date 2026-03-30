@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- ✅ HEADER FIXED -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Vehicles List</h5>

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <!-- Status Filter -->
                        <form action="{{ route('admin.vehicles.index') }}" method="GET">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </form>

                        @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-vehicles', 'admin-guard'))
                            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">
                                Add New Vehicle
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="vehicles-table">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Vehicle</th>
                                    <th>User</th>
                                    <th>Year</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th class="text-center">Featured</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($vehicles as $vehicle)
                                    <tr data-id="{{ $vehicle->id }}">
                                        <td>
                                            @if($vehicle->main_image)
                                                <img src="{{ asset('storage/' . $vehicle->main_image) }}" width="50"
                                                    class="rounded">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>

                                        <td>
                                            <strong>{{ $vehicle->title }}</strong><br>
                                            <small class="text-muted">
                                                {{ optional($vehicle->brand)->name }}
                                                {{ optional($vehicle->model)->name }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $vehicle->user->first_name ?? 'N/A' }}
                                            {{ $vehicle->user->last_name ?? '' }}
                                        </td>

                                        <td>{{ $vehicle->year }}</td>

                                        <td>{{ number_format($vehicle->price) }} Ft</td>

                                        <td>
                                            <small>
                                                Fuel: {{ optional($vehicle->fuelType)->name }} <br>
                                                Trans: {{ optional($vehicle->transmission)->name }} <br>
                                                Mileage: {{ $vehicle->mileage }} km
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <button type="button" 
                                                class="btn btn-icon btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn" 
                                                data-id="{{ $vehicle->id }}" 
                                                data-bs-toggle="tooltip" 
                                                title="{{ $vehicle->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                                            </button>
                                        </td>

                                        <td>
                                            @php
                                                $statusClass = match ($vehicle->ad_status) {
                                                    'published' => 'success',
                                                    'pending' => 'warning',
                                                    'rejected' => 'danger',
                                                    'draft' => 'secondary',
                                                    default => 'primary',
                                                };
                                            @endphp

                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ ucfirst($vehicle->ad_status) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" 
                                                   class="btn btn-icon btn-sm btn-label-secondary border-0 shadow-none"
                                                   data-bs-toggle="tooltip" title="View Details">
                                                    <i class="bx bx-show"></i>
                                                </a>

                                                @if(auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" 
                                                       class="btn btn-icon btn-sm btn-label-info border-0 shadow-none"
                                                       data-bs-toggle="tooltip" title="Edit Vehicle">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                @endif

                                                @if(auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard'))
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" 
                                                          method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-confirmation"
                                                            data-bs-toggle="tooltip" title="Delete Vehicle">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No vehicles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Details Modal -->
    <div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3" id="v-modal-loader-content">
                <div class="modal-body text-center py-5">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted fw-semibold">Acquiring vehicle specifications...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            $('#vehicles-table').DataTable({
                order: [[1, "asc"]],
                pageLength: 25,
                dom:
                    "<'row align-items-center mb-3'<'col-md-6 d-flex align-items-center'l><'col-md-6 d-flex justify-content-end'f>>" +
                    "t" +
                    "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",
                language: {
                    search: "",
                    searchPlaceholder: "Quick Search Vehicles...",
                    paginate: {
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>'
                    }
                },
                columnDefs: [
                    { "orderable": false, "targets": [0, 6, 7, 8] } // Non-sortable: Thumb, Featured, Status, Actions
                ]
            });

            // ✅ FEATURED TOGGLE
            $(document).on('click', '.featured-toggle-btn', function() {
                const button = $(this);
                const id = button.data('id');
                const icon = button.find('i');

                $.ajax({
                    url: `{{ url('/app/vehicles') }}/${id}/toggle-featured`,
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            // Sync all buttons for this vehicle (list + modal)
                            $(`.featured-toggle-btn[data-id="${id}"]`).each(function() {
                                const targetBtn = $(this);
                                const targetIcon = targetBtn.find('i');
                                if (response.is_featured) {
                                    targetBtn.removeClass('btn-label-secondary').addClass('btn-label-warning');
                                    targetIcon.removeClass('bx-star').addClass('bxs-star');
                                    targetBtn.attr('data-bs-original-title', 'Remove from Featured');
                                } else {
                                    targetBtn.removeClass('btn-label-warning').addClass('btn-label-secondary');
                                    targetIcon.removeClass('bxs-star').addClass('bx-star');
                                    targetBtn.attr('data-bs-original-title', 'Mark as Featured');
                                }
                            });
                            toastr.success(response.message, 'Updated');
                        }
                    },
                    error: function() {
                        toastr.error('Could not update featured status.', 'Error');
                    }
                });
            });

            // ✅ MODAL TRIGGER ON ROW CLICK
            $(document).on('click', '#vehicles-table tbody tr', function (e) {
                // IGNORE DROPDOWNS OR ACTIONS
                if ($(e.target).closest('.dropdown-menu, .dropdown-toggle, .btn-close, form, a, button').length) return;

                const vehicleId = $(this).data('id');
                if (!vehicleId) return;

                const modal = new bootstrap.Modal(document.getElementById('vehicleDetailsModal'));
                const container = document.getElementById('v-modal-loader-content');

                // SHOW LOADER
                container.innerHTML = `
                    <div class="modal-body text-center py-5">
                        <div class="spinner-grow text-primary" role="status"></div>
                        <p class="mt-3 text-muted fw-semibold small">Fetching vehicle data...</p>
                    </div>
                `;

                modal.show();

                // FETCH VIA AJAX
                fetch(`{{ url('/app/vehicles') }}/${vehicleId}?modal=1`)
                    .then(res => res.text())
                    .then(html => {
                        container.innerHTML = html;
                    })
                    .catch(err => {
                        container.innerHTML = `<div class="modal-body text-center py-5 text-danger font-bold">Error loading vehicle details. Please try again.</div>`;
                    });
            });
        });
    </script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    <style>
        /* Search box */
        .dataTables_filter input {
            border-radius: 8px;
            padding: 6px 10px;
            border: 1px solid #ddd;
            width: 220px;
        }

        /* Align controls properly */
        .dataTables_wrapper .dataTables_filter {
            display: flex;
            justify-content: flex-end;
        }

        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
        }

        .dataTables_length select {
            padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            min-width: 80px !important;
        }

        /* Pagination */
        .dataTables_paginate {
            display: flex;
            justify-content: flex-end;
        }

        /* Mobile fix */
        @media (max-width: 768px) {
            .dataTables_filter {
                justify-content: start !important;
                margin-top: 10px;
            }
        }

        #vehicles-table tbody tr {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        #vehicles-table tbody tr:hover {
            background-color: rgba(105, 108, 255, 0.04) !important;
            transform: scale(1.002);
        }

        .shadow-xs {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection