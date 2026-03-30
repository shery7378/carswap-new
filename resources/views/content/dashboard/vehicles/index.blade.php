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
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            @if($vehicle->main_image)
                                                <img src="{{ asset('storage/' . $vehicle->main_image) }}"
                                                     width="50"
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
                                            <a href="{{ route('admin.vehicles.show', $vehicle->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
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
            pageLength: 10,

            // ✅ FIXED HEADER ALIGNMENT
            dom:
                "<'row align-items-center mb-3'<'col-md-6 d-flex align-items-center'l><'col-md-6 d-flex justify-content-end'f>>" +
                "t" +
                "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

            language: {
                search: "",
                searchPlaceholder: "Search Vehicles...",
                paginate: {
                    next: '<i class="bx bx-chevron-right"></i>',
                    previous: '<i class="bx bx-chevron-left"></i>'
                }
            }
        });

    });
    </script>

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

    </style>
@endsection