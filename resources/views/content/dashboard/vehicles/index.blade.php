@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('Vehicles List') }}</h5>

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <!-- Status Filter -->
                        <form action="{{ route('admin.vehicles.index') }}" method="GET">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                    {{ __('Published') }}
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    {{ __('Rejected') }}
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}
                                </option>
                            </select>
                        </form>

                        @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-vehicles', 'admin-guard'))
                            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i>{{ __('Add Vehicle') }}
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

                    <!-- ─── DESKTOP TABLE (hidden on xs/sm) ─── -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover" id="vehicles-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Thumbnail') }}</th>
                                    <th>{{ __('Vehicle') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('User') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('Year') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th class="d-none d-xl-table-cell">{{ __('Details') }}</th>
                                    <th class="text-center">{{ __('Featured') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-end pe-4">{{ __('Actions') }}</th>
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
                                                <span class="badge bg-secondary">{{ __('No Image') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <strong>{{ $vehicle->title }}</strong><br>
                                            <small class="text-muted">
                                                {{ optional($vehicle->brand)->name }}
                                                {{ optional($vehicle->model)->name }}
                                            </small>
                                        </td>

                                        <td class="d-none d-lg-table-cell">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $vehicle->user->first_name ?? 'N/A' }}
                                                    {{ $vehicle->user->last_name ?? '' }}</span>
                                                <small class="text-muted">{{ $vehicle->user->email ?? '' }}</small>
                                            </div>
                                        </td>

                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-label-secondary">{{ $vehicle->year }}</span>
                                        </td>

                                        <td><span class="fw-bold text-primary">{{ number_format($vehicle->price) }} Ft</span>
                                        </td>

                                        <td class="d-none d-xl-table-cell">
                                            <div class="d-flex flex-column small">
                                                <span><i
                                                        class="bx bx-gas-pump me-1"></i>{{ optional($vehicle->fuelType)->name }}</span>
                                                <span><i
                                                        class="bx bx-cog me-1"></i>{{ optional($vehicle->transmission)->name }}</span>
                                                <span><i class="bx bx-tachometer me-1"></i>{{ $vehicle->mileage }} km</span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-icon btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn"
                                                data-id="{{ $vehicle->id }}" data-bs-toggle="tooltip"
                                                title="{{ $vehicle->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                                            </button>
                                        </td>

                                        <td class="td-status text-center">
                                            @php
                                                $statusClass = match ($vehicle->ad_status) {
                                                    'published' => 'success',
                                                    'pending' => 'warning',
                                                    'rejected' => 'danger',
                                                    'draft' => 'secondary',
                                                    default => 'primary',
                                                };
                                            @endphp
                                            <div class="dropdown status-dropdown">
                                                <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span
                                                        class="badge bg-{{ $statusClass }}">{{ ucfirst($vehicle->ad_status) }}</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        @foreach(['published' => 'success', 'pending' => 'warning', 'rejected' => 'danger', 'draft' => 'secondary'] as $val => $cls)
                                                            <li>
                                                                <button type="submit" name="ad_status" value="{{ $val }}"
                                                                    class="dropdown-item d-flex align-items-center py-2">
                                                                    <span class="badge badge-dot bg-{{ $cls }} me-2"></span>
                                                                    {{ ucfirst($val) }}
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </form>
                                                </ul>
                                            </div>
                                        </td>

                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end align-items-center gap-1 flex-nowrap">
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
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-confirmation"
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
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bx bx-car fs-2 d-block mb-2"></i>
                                            No vehicles found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- /desktop table -->

                    <!-- ─── MOBILE CARD LIST (visible only xs/sm) ─── -->
                    <div class="d-md-none" id="vehicles-mobile-list">

                        <!-- Mobile search bar -->
                        <div class="mb-3">
                            <input type="text" id="mobile-search" class="form-control form-control-sm"
                                placeholder="Quick search vehicles…">
                        </div>

                        @forelse($vehicles as $vehicle)
                            @php
                                $statusClass = match ($vehicle->ad_status) {
                                    'published' => 'success',
                                    'pending' => 'warning',
                                    'rejected' => 'danger',
                                    'draft' => 'secondary',
                                    default => 'primary',
                                };
                            @endphp

                            <div class="vehicle-mobile-card card mb-3 shadow-sm border-0" data-id="{{ $vehicle->id }}"
                                data-title="{{ strtolower($vehicle->title . ' ' . optional($vehicle->brand)->name . ' ' . optional($vehicle->model)->name) }}">

                                <div class="card-body p-3">

                                    <!-- Row 1: Thumbnail + Title + Badges -->
                                    <div class="d-flex gap-3 align-items-start">
                                        <div class="flex-shrink-0">
                                            @if($vehicle->main_image)
                                                <img src="{{ asset('storage/' . $vehicle->main_image) }}" width="70" height="55"
                                                    class="rounded object-fit-cover">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                    style="width:70px;height:55px">
                                                    <i class="bx bx-car text-muted fs-4"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                                                <div>
                                                    <strong class="d-block text-truncate"
                                                        style="max-width:180px">{{ $vehicle->title }}</strong>
                                                    <small class="text-muted">
                                                        {{ optional($vehicle->brand)->name }}
                                                        {{ optional($vehicle->model)->name }}
                                                    </small>
                                                </div>
                                                <!-- Status dropdown (mobile) -->
                                                <div class="dropdown status-dropdown">
                                                    <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <span
                                                            class="badge bg-{{ $statusClass }}">{{ ucfirst($vehicle->ad_status) }}</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            @foreach(['published' => 'success', 'pending' => 'warning', 'rejected' => 'danger', 'draft' => 'secondary'] as $val => $cls)
                                                                <li>
                                                                    <button type="submit" name="ad_status" value="{{ $val }}"
                                                                        class="dropdown-item d-flex align-items-center py-2">
                                                                        <span class="badge badge-dot bg-{{ $cls }} me-2"></span>
                                                                        {{ ucfirst($val) }}
                                                                    </button>
                                                                </li>
                                                            @endforeach
                                                        </form>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- Row 2: Price + Year -->
                                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                                <span class="fw-bold text-primary small">{{ number_format($vehicle->price) }}
                                                    Ft</span>
                                                <span class="badge bg-label-secondary small">{{ $vehicle->year }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Row 3: Details strip -->
                                    <div class="d-flex gap-3 mt-2 text-muted small flex-wrap">
                                        @if(optional($vehicle->fuelType)->name)
                                            <span><i class="bx bx-gas-pump me-1"></i>{{ optional($vehicle->fuelType)->name }}</span>
                                        @endif
                                        @if(optional($vehicle->transmission)->name)
                                            <span><i class="bx bx-cog me-1"></i>{{ optional($vehicle->transmission)->name }}</span>
                                        @endif
                                        @if($vehicle->mileage)
                                            <span><i class="bx bx-tachometer me-1"></i>{{ $vehicle->mileage }} km</span>
                                        @endif
                                    </div>

                                    <!-- Row 4: User -->
                                    <div class="mt-1 small text-muted">
                                        <i class="bx bx-user me-1"></i>
                                        {{ $vehicle->user->first_name ?? 'N/A' }} {{ $vehicle->user->last_name ?? '' }}
                                        @if($vehicle->user->email ?? null)
                                            <span class="ms-1 d-none d-sm-inline">&bull; {{ $vehicle->user->email }}</span>
                                        @endif
                                    </div>

                                    <!-- Row 5: Action buttons -->
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">

                                        <!-- Featured toggle -->
                                        <button type="button"
                                            class="btn btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn"
                                            data-id="{{ $vehicle->id }}">
                                            <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }} me-1"></i>
                                            {{ $vehicle->is_featured ? 'Featured' : 'Feature' }}
                                        </button>

                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.vehicles.show', $vehicle->id) }}"
                                                class="btn btn-icon btn-sm btn-label-secondary border-0">
                                                <i class="bx bx-show"></i>
                                            </a>

                                            @if(auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                                <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}"
                                                    class="btn btn-icon btn-sm btn-label-info border-0">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                            @endif

                                            @if(auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard'))
                                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-label-danger border-0 delete-confirmation">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                </div><!-- /card-body -->
                            </div><!-- /vehicle-mobile-card -->

                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bx bx-car fs-1 d-block mb-2"></i>
                                No vehicles found.
                            </div>
                        @endforelse

                    </div><!-- /mobile list -->

                </div><!-- /card-body -->
            </div>
        </div>
    </div>

    <!-- Vehicle Details Modal -->
    <div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0 rounded-3" id="v-modal-loader-content">
                <div class="modal-body text-center py-5">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted fw-semibold">Acquiring vehicle specifications…</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {

            // ── DataTable (desktop only) ─────────────────────────────────────────
            if ($.fn.DataTable) {
                $('#vehicles-table').DataTable({
                    order: [[1, 'asc']],
                    pageLength: 25,
                    responsive: false, // we handle responsiveness ourselves
                    dom:
                        "<'row align-items-center mb-3'" +
                        "<'col-12 col-sm-6 mb-2 mb-sm-0 d-flex align-items-center'l>" +
                        "<'col-12 col-sm-6 d-flex justify-content-sm-end'f>" +
                        ">" +
                        "t" +
                        "<'row mt-3'" +
                        "<'col-12 col-sm-6 mb-2 mb-sm-0'i>" +
                        "<'col-12 col-sm-6 d-flex justify-content-sm-end'p>" +
                        ">",
                    language: {
                        search: '',
                        searchPlaceholder: 'Quick Search Vehicles…',
                        paginate: {
                            next: '<i class="bx bx-chevron-right"></i>',
                            previous: '<i class="bx bx-chevron-left"></i>'
                        }
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 6, 7, 8] }
                    ]
                });
            }

            // ── Featured toggle ──────────────────────────────────────────────────
            $(document).on('click', '.featured-toggle-btn', function () {
                const btn = $(this);
                const id = btn.data('id');

                $.ajax({
                    url: `{{ url('/app/vehicles') }}/${id}/toggle-featured`,
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res.success) {
                            // Sync ALL buttons for this vehicle (table + mobile card + modal)
                            $(`.featured-toggle-btn[data-id="${id}"]`).each(function () {
                                const t = $(this);
                                const icon = t.find('i');
                                const isMobileCard = t.closest('.vehicle-mobile-card').length;

                                if (res.is_featured) {
                                    t.removeClass('btn-label-secondary').addClass('btn-label-warning');
                                    icon.removeClass('bx-star').addClass('bxs-star');
                                    if (isMobileCard) t.html('<i class="bx bxs-star me-1"></i>Featured');
                                    t.attr('data-bs-original-title', 'Remove from Featured');
                                } else {
                                    t.removeClass('btn-label-warning').addClass('btn-label-secondary');
                                    icon.removeClass('bxs-star').addClass('bx-star');
                                    if (isMobileCard) t.html('<i class="bx bx-star me-1"></i>Feature');
                                    t.attr('data-bs-original-title', 'Mark as Featured');
                                }
                            });
                            toastr.success(res.message, 'Updated');
                        }
                    },
                    error: function () {
                        toastr.error('Could not update featured status.', 'Error');
                    }
                });
            });

            // ── Desktop row click → modal ────────────────────────────────────────
            $(document).on('click', '#vehicles-table tbody tr', function (e) {
                if ($(e.target).closest('.dropdown-menu, .dropdown-toggle, .btn-close, form, a, button').length) return;
                openVehicleModal($(this).data('id'));
            });

            // ── Mobile card click → modal (tap on card body, not buttons) ────────
            $(document).on('click', '.vehicle-mobile-card', function (e) {
                if ($(e.target).closest('form, a, button, .dropdown-menu, .dropdown-toggle').length) return;
                openVehicleModal($(this).data('id'));
            });

            function openVehicleModal(vehicleId) {
                if (!vehicleId) return;
                const modal = new bootstrap.Modal(document.getElementById('vehicleDetailsModal'));
                const container = document.getElementById('v-modal-loader-content');

                container.innerHTML = `
                                        <div class="modal-body text-center py-5">
                                            <div class="spinner-grow text-primary" role="status"></div>
                                            <p class="mt-3 text-muted fw-semibold small">Fetching vehicle data…</p>
                                        </div>`;

                modal.show();

                fetch(`{{ url('/app/vehicles') }}/${vehicleId}?modal=1`)
                    .then(r => r.text())
                    .then(html => { container.innerHTML = html; })
                    .catch(() => {
                        container.innerHTML = `<div class="modal-body text-center py-5 text-danger fw-bold">
                                                Error loading vehicle details. Please try again.</div>`;
                    });
            }

            // ── Mobile card search ───────────────────────────────────────────────
            $('#mobile-search').on('input', function () {
                const q = $(this).val().toLowerCase();
                $('.vehicle-mobile-card').each(function () {
                    const haystack = $(this).data('title') || '';
                    $(this).toggle(haystack.includes(q));
                });
            });

            // ── Init tooltips ────────────────────────────────────────────────────
            $('[data-bs-toggle="tooltip"]').tooltip();

        });
    </script>

    <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    <style>
        /* ── DataTable controls ─────────────────────────────── */
        .dataTables_filter input {
            border-radius: 8px;
            padding: 6px 10px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 220px;
        }

        .dataTables_wrapper .dataTables_filter {
            display: flex;
            justify-content: flex-end;
        }

        .dataTables_wrapper .dataTables_length {
            display: flex;
            align-items: center;
        }

        .dataTables_length select {
            padding: .25rem 1.5rem .25rem .5rem !important;
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            min-width: 80px !important;
        }

        .dataTables_paginate {
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 576px) {

            .dataTables_filter,
            .dataTables_paginate {
                justify-content: flex-start !important;
            }

            .dataTables_filter input {
                max-width: 100%;
            }
        }

        /* ── Desktop table row ──────────────────────────────── */
        #vehicles-table tbody tr {
            cursor: pointer;
            transition: background-color .15s ease, transform .15s ease;
        }

        #vehicles-table tbody tr:hover {
            background-color: rgba(105, 108, 255, .04) !important;
            transform: scale(1.002);
        }

        /* ── Status dropdown z-index ────────────────────────── */
        .status-dropdown .dropdown-menu {
            z-index: 1060;
            min-width: 140px;
        }

        .td-status {
            position: relative;
        }

        /* ── Mobile card ────────────────────────────────────── */
        .vehicle-mobile-card {
            cursor: pointer;
            transition: box-shadow .15s ease, transform .15s ease;
            border-radius: 12px !important;
        }

        .vehicle-mobile-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, .1) !important;
            transform: translateY(-1px);
        }

        .vehicle-mobile-card img.object-fit-cover {
            object-fit: cover;
        }

        /* ── Modal scrollable on small screens ──────────────── */
        @media (max-width: 576px) {
            .modal-xl {
                margin: .5rem;
                max-width: calc(100% - 1rem);
            }
        }

        /* ── Actions: never wrap ────────────────────────────── */
        .d-flex.flex-nowrap {
            flex-wrap: nowrap !important;
        }
    </style>
@endsection