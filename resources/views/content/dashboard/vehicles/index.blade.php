@extends('layouts/contentNavbarLayout')

@section('title', __('Vehicles'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('Vehicles List') }}</h5>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Bulk Actions -->
                        <div id="bulk-actions" class="d-none me-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-label-success btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-check-double me-1"></i> {{ __('Bulk Actions') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item bulk-status-btn " href="javascript:void(0);"
                                            data-status="Publikált">{{ __('Mark as Published') }}</a></li>
                                    <li><a class="dropdown-item bulk-status-btn text-danger" href="javascript:void(0);"
                                            data-status="Elutasítva">{{ __('Reject All') }}</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <form action="{{ route('admin.vehicles.index') }}" method="GET">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="Publikált" {{ request('status') == 'Publikált' ? 'selected' : '' }}>
                                    {{ __('Published') }}
                                </option>
                                <option value="Függőben" {{ request('status') == 'Függőben' ? 'selected' : '' }}>
                                    {{ __('Pending') }}
                                </option>
                                <option value="Elutasítva" {{ request('status') == 'Elutasítva' ? 'selected' : '' }}>
                                    {{ __('Rejected') }}
                                </option>
                                <option value="Piszkozat" {{ request('status') == 'Piszkozat' ? 'selected' : '' }}>
                                    {{ __('Draft') }}
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

                    <!-- TABLE VIEW -->
                    <table class="table table-hover align-middle mb-0" id="vehicles-table">
                            <thead>
                                <tr>
                                    <th width="10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check-all">
                                        </div>
                                    </th>
                                    <th class="col-thumb">{{ __('Thumbnail') }}</th>
                                    <th>{{ __('Vehicle') }}</th>
                                    <th class="col-user">{{ __('User') }}</th>
                                    <th class="col-year">{{ __('Year of Manufacture') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th class="col-details">{{ __('Details') }}</th>
                                    <th class="text-center">{{ __('Featured') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-end pe-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($vehicles as $vehicle)
                                    <tr data-id="{{ $vehicle->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input vehicle-checkbox" type="checkbox"
                                                    value="{{ $vehicle->id }}">
                                            </div>
                                        </td>
                                        <td class="col-thumb">
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

                                        <td class="col-user" style="max-width: 150px;">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-truncate">{{ $vehicle->user->first_name ?? 'N/A' }}
                                                    {{ $vehicle->user->last_name ?? '' }}</span>
                                                <small class="text-muted text-truncate">{{ $vehicle->user->email ?? '' }}</small>
                                            </div>
                                        </td>

                                        <td class="col-year">
                                            <span class="badge bg-label-secondary">{{ $vehicle->year }}</span>
                                        </td>

                                        <td><span class="fw-bold text-primary">@formatCurrency($vehicle->price)</span>
                                        </td>

                                        <td class="col-details" style="max-width: 180px;">
                                            <div class="d-flex flex-column small">
                                                <span class="text-truncate"><i class="bx bx-gas-pump me-1"></i>{{ __(optional($vehicle->fuelType)->name) }}</span>
                                                <span class="text-truncate"><i class="bx bx-cog me-1"></i>{{ __(optional($vehicle->transmission)->name) }}</span>
                                                <span class="text-truncate"><i class="bx bx-tachometer me-1"></i>{{ $vehicle->mileage }} km</span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-icon btn-sm {{ $vehicle->is_featured ? 'btn-label-warning' : 'btn-label-secondary' }} featured-toggle-btn"
                                                data-id="{{ $vehicle->id }}" data-bs-toggle="tooltip"
                                                title="{{ $vehicle->is_featured ? __('Remove from Featured') : __('Mark as Featured') }}">
                                                <i class="bx {{ $vehicle->is_featured ? 'bxs-star' : 'bx-star' }}"></i>
                                            </button>
                                        </td>

                                        <td class="td-status text-center">
                                            @php
                                                $statusClass = match ($vehicle->ad_status) {
                                                    'Publikált' => 'success',
                                                    'Függőben' => 'warning',
                                                    'Elutasítva' => 'danger',
                                                    'Piszkozat' => 'secondary',
                                                    default => 'primary',
                                                };
                                            @endphp
                                            <div class="dropdown status-dropdown">
                                                <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button"
                                                    data-bs-toggle="dropdown"
                                                    data-bs-flip="false"
                                                    aria-expanded="false">
                                                    <span
                                                        class="badge bg-{{ $statusClass }}">{{ __($vehicle->ad_status) }}</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        @foreach(['Publikált' => 'success', 'Függőben' => 'warning', 'Elutasítva' => 'danger', 'Piszkozat' => 'secondary'] as $val => $cls)
                                                            <li>
                                                                <button type="submit" name="ad_status" value="{{ $val }}"
                                                                    class="dropdown-item d-flex align-items-center py-2 status-option-{{ $cls }}">
                                                                    <span class="badge badge-dot bg-{{ $cls }} me-2"></span>
                                                                    {{ __($val) }}
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </form>
                                                </ul>
                                            </div>
                                            @if($vehicle->ad_status !== 'Publikált')
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-label-success border-0 shadow-none quick-approve-btn mt-1"
                                                    data-id="{{ $vehicle->id }}" data-bs-toggle="tooltip"
                                                    title="{{ __('Quick Approve') }}" aria-label="{{ __('Quick Approve') }}">
                                                    <i class="icon-base bx bx-check"></i>
                                                </button>
                                            @endif
                                        </td>

                                        <td class="text-end pe-4">
                                            <div class="action-container">
                                                <button type="button" class="btn-action view open-vehicle-modal-btn" 
                                                        data-id="{{ $vehicle->id }}" data-bs-toggle="tooltip" title="{{ __('View Details') }}">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                
                                                @if(auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}"
                                                       class="btn-action edit"
                                                       data-bs-toggle="tooltip" title="{{ __('Edit Vehicle') }}">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                @endif

                                                @if(auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard'))
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="m-0 d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-action delete delete-confirmation"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete Vehicle') }}">
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
                                            {{ __('No vehicles found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>




                </div>
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
                    <p class="mt-3 text-muted fw-semibold">{{ __('Acquiring vehicle specifications…') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {

            // ── DataTable ────────────────────────────────────────────────────────
            if ($.fn.DataTable) {
                $('#vehicles-table').DataTable({
                    order: [[1, 'asc']],
                    pageLength: 25,
                    responsive: false,
                    dom:
                        "<'row align-items-center mb-3'" +
                        "<'col-12 col-md-auto mb-2 mb-md-0'l>" +
                        "<'col-12 col-md d-flex justify-content-md-end'f>" +
                        ">" +
                        "<'table-responsive vehicles-table-responsive't>" +
                        "<'row mt-3 d-flex align-items-center justify-content-between flex-wrap'" +
                        "<'col-12 col-md-auto mb-2 mb-md-0 text-center text-md-start'i>" +
                        "<'col-12 col-md-auto d-flex justify-content-center justify-content-md-end'p>" +
                        ">",
                    language: {
                        searchPlaceholder: "{{ __('Quick Search Vehicles…') }}"
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 6, 7, 8, 9] }
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
                            $(`.featured-toggle-btn[data-id="${id}"]`).each(function () {
                                const t = $(this);
                                const icon = t.find('i');
                                const isMobileCard = t.closest('.vehicle-mobile-card').length;

                                if (res.is_featured) {
                                    t.removeClass('btn-label-secondary').addClass('btn-label-warning');
                                    icon.removeClass('bx-star').addClass('bxs-star');
                                    if (isMobileCard) t.html('<i class="bx bxs-star me-1"></i>{{ __('Featured') }}');
                                    t.attr('data-bs-original-title', '{{ __('Remove from Featured') }}');
                                } else {
                                    t.removeClass('btn-label-warning').addClass('btn-label-secondary');
                                    icon.removeClass('bxs-star').addClass('bx-star');
                                    if (isMobileCard) t.html('<i class="bx bx-star me-1"></i>{{ __('Feature') }}');
                                    t.attr('data-bs-original-title', '{{ __('Mark as Featured') }}');
                                }
                            });
                            toastr.success(res.message, 'Updated');
                        }
                    },
                    error: function () {
                        toastr.error('{{ __('Could not update featured status.') }}', '{{ __('Error') }}');
                    }
                });
            });

            // ── Desktop row click → modal ────────────────────────────────────────
            $(document).on('click', '.open-vehicle-modal-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openVehicleModal($(this).data('id'));
            });

            $(document).on('click', '#vehicles-table tbody tr', function (e) {
                if ($(e.target).closest('.dropdown-menu, .dropdown-toggle, .btn-close, form, a, button, .form-check').length) return;
                openVehicleModal($(this).data('id'));
            });

            // ── Mobile card click → modal ────────────────────────────────────────
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
                        <p class="mt-3 text-muted fw-semibold small">{{ __('Fetching vehicle data…') }}</p>
                    </div>`;

                modal.show();

                fetch(`{{ url('/app/vehicles') }}/${vehicleId}?modal=1`)
                    .then(r => r.text())
                    .then(html => { container.innerHTML = html; })
                    .catch(() => {
                        container.innerHTML = `<div class="modal-body text-center py-5 text-danger fw-bold">
                            {{ __('Error loading vehicle details. Please try again.') }}</div>`;
                    });
            }

            // ── Mobile search ────────────────────────────────────────────────────
            $('#mobile-search').on('input', function () {
                const q = $(this).val().toLowerCase();
                $('.vehicle-mobile-card').each(function () {
                    const haystack = $(this).data('title') || '';
                    $(this).toggle(haystack.includes(q));
                });
            });

            // ── Bulk Status ──────────────────────────────────────────────────────
            const bulkActions = $('#bulk-actions');
            const checkAll = $('#check-all');
            const checkboxes = $('.vehicle-checkbox');

            checkAll.on('change', function () {
                checkboxes.prop('checked', this.checked);
                toggleBulkActions();
            });

            checkboxes.on('change', function () {
                toggleBulkActions();
            });

            function toggleBulkActions() {
                const checkedCount = $('.vehicle-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkActions.removeClass('d-none');
                } else {
                    bulkActions.addClass('d-none');
                }
            }

            $('.bulk-status-btn').on('click', function () {
                const status = $(this).data('status');
                const selectedIds = $('.vehicle-checkbox:checked').map(function () { return $(this).val(); }).get();

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: `{{ __('Update status to :status for :count vehicles?', ['status' => '${status}', 'count' => '${selectedIds.length}']) }}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Yes, update all!') }}',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route("admin.vehicles.bulk-status") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds,
                                ad_status: status
                            },
                            success: function (res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                            }
                        });
                    }
                });
            });

            // ── Quick Approve ────────────────────────────────────────────────────
            $(document).on('click', '.quick-approve-btn', function () {
                const id = $(this).data('id');

                $.ajax({
                    url: `{{ url('/app/vehicles') }}/${id}/status`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ad_status: 'Publikált'
                    },
                    success: function (res) {
                        toastr.success('{{ __('Vehicle approved successfully!') }}');
                        setTimeout(() => window.location.reload(), 500);
                    }
                });
            });

            // ── Status dropdown z-index fix ───────────────────────────────────────
            $(document).on('shown.bs.dropdown', '.status-dropdown', function () {
                $(this).closest('tr').addClass('status-dropdown-open');
                $(this).closest('.vehicle-mobile-card').addClass('status-dropdown-open');
            });
            $(document).on('hide.bs.dropdown', '.status-dropdown', function () {
                $(this).closest('tr').removeClass('status-dropdown-open');
                $(this).closest('.vehicle-mobile-card').removeClass('status-dropdown-open');
            });

            // ── Actions dropdown z-index fix ─────────────────────────────────────
            $(document).on('shown.bs.dropdown', '.vehicles-actions.dropdown', function () {
                $(this).closest('tr').addClass('actions-dropdown-open');
                $(this).closest('.vehicle-mobile-card').addClass('actions-dropdown-open');
            });
            $(document).on('hide.bs.dropdown', '.vehicles-actions.dropdown', function () {
                $(this).closest('tr').removeClass('actions-dropdown-open');
                $(this).closest('.vehicle-mobile-card').removeClass('actions-dropdown-open');
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

        .dataTables_length label {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
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
            margin-top: 0.25rem;
        }

        .dataTables_info {
            white-space: normal !important;
        }

        @media (min-width: 768px) {
            .dataTables_paginate {
                margin-top: 0;
            }
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
        .table-responsive { overflow-y: visible; }
        #vehicles-table tbody tr.status-dropdown-open { position: relative; z-index: 1065; }
        .td-status { position: relative; }
        #vehicles-table tbody tr.status-dropdown-open .td-status { z-index: 1066; }
	        .status-dropdown { position: relative; }
	        .status-dropdown.show { z-index: 1066; }
	        .status-dropdown .dropdown-menu { z-index: 1067; min-width: 140px; }
            .status-dropdown .dropdown-item { transition: color .15s ease; }
            .status-dropdown .dropdown-item i,
            .status-dropdown .dropdown-item .badge-dot { transition: color .15s ease, background-color .15s ease; }
            .status-dropdown .dropdown-item.status-option-success:hover,
            .status-dropdown .dropdown-item.status-option-success:focus { color: var(--bs-success) !important; }
            .status-dropdown .dropdown-item.status-option-warning:hover,
            .status-dropdown .dropdown-item.status-option-warning:focus { color: var(--bs-warning) !important; }
            .status-dropdown .dropdown-item.status-option-danger:hover,
            .status-dropdown .dropdown-item.status-option-danger:focus { color: var(--bs-danger) !important; }
            .status-dropdown .dropdown-item.status-option-secondary:hover,
            .status-dropdown .dropdown-item.status-option-secondary:focus { color: var(--bs-secondary) !important; }
	        .vehicle-mobile-card.status-dropdown-open { position: relative; z-index: 1065; }

        /* ── Actions dropdown z-index ───────────────────────── */
        #vehicles-table tbody tr.actions-dropdown-open { position: relative; z-index: 1070; }
        #vehicles-table tbody tr.actions-dropdown-open td:last-child { position: relative; z-index: 1071; }
        .vehicles-actions.dropdown.show { z-index: 1072; }
        .vehicles-actions.dropdown .dropdown-menu { z-index: 1073; }
        .vehicle-mobile-card.actions-dropdown-open { position: relative; z-index: 1070; }

        /* ── Actions dropdown item colors ───────────────────── */
        .vehicles-actions .dropdown-item { transition: color .15s ease; }
        .vehicles-actions .dropdown-item i { transition: color .15s ease; }
        .vehicles-actions .dropdown-item.action-view:hover,
        .vehicles-actions .dropdown-item.action-view:focus,
        .vehicles-actions .dropdown-item.action-view:hover i,
        .vehicles-actions .dropdown-item.action-view:focus i { color: var(--bs-primary) !important; }
        .vehicles-actions .dropdown-item.action-edit:hover,
        .vehicles-actions .dropdown-item.action-edit:focus,
        .vehicles-actions .dropdown-item.action-edit:hover i,
        .vehicles-actions .dropdown-item.action-edit:focus i { color: var(--bs-info) !important; }
        .vehicles-actions .dropdown-item.action-delete:hover,
        .vehicles-actions .dropdown-item.action-delete:focus,
        .vehicles-actions .dropdown-item.action-delete:hover i,
        .vehicles-actions .dropdown-item.action-delete:focus i { color: var(--bs-danger) !important; }

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

        /* ── Modal on small screens ─────────────────────────── */
        @media (max-width: 576px) {
            .modal-xl {
                margin: .5rem;
                max-width: calc(100% - 1rem);
            }
        }

        /* ── Table layout ───────────────────────────────────── */
        .vehicles-table-responsive {
            overflow-x: auto;        /* Restore horizontal scroll when needed */
            overflow-y: auto;        /* Enables sticky header */
            max-height: 75vh;        /* Scroll container needed for sticky */
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .vehicles-table-responsive::-webkit-scrollbar {
            width: 0;
            height: 0;
            background: transparent;
        }
        .vehicles-table-responsive::-webkit-scrollbar-track,
        .vehicles-table-responsive::-webkit-scrollbar-thumb {
            display: none;
            background: transparent;
        }
        #vehicles-table_wrapper { overflow-x: hidden; }
        #vehicles-table { width: 100% !important; min-width: 0 !important; table-layout: fixed; }
        #vehicles-table th,
        #vehicles-table td { padding: .65rem .35rem !important; font-size: .76rem; vertical-align: middle; }
        #vehicles-table th { font-size: .74rem; white-space: nowrap; line-height: 1.15; position: relative; padding-right: 18px !important; }
        #vehicles-table td { word-break: break-word; }
        
        /* Truncation and column sizing */
        .text-truncate { max-width: 100%; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #vehicles-table th:nth-child(1),
        #vehicles-table td:nth-child(1) { width: 3%; }
        #vehicles-table th:nth-child(2),
        #vehicles-table td:nth-child(2) { width: 9%; }
        #vehicles-table th:nth-child(3),
        #vehicles-table td:nth-child(3) { width: 14%; }
        #vehicles-table th:nth-child(4),
        #vehicles-table td:nth-child(4) { width: 17%; }
        #vehicles-table th:nth-child(5),
        #vehicles-table td:nth-child(5) { width: 7%; text-align: center; }
        #vehicles-table th:nth-child(6),
        #vehicles-table td:nth-child(6) { width: 9%; }
        #vehicles-table th:nth-child(7),
        #vehicles-table td:nth-child(7) { width: 17%; }
        #vehicles-table th:nth-child(8),
        #vehicles-table td:nth-child(8) { width: 7%; text-align: center; }
        #vehicles-table th:nth-child(9),
        #vehicles-table td:nth-child(9) { width: 7%; text-align: center; }
        #vehicles-table th:nth-child(10),
        #vehicles-table td:nth-child(10) { width: 110px; text-align: right; }
        
        /* Premium Action Buttons */
        .btn-action {
            width: 28px !important;
            height: 28px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            padding: 0 !important;
            border: none !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-action.edit { background-color: #e0f7fa !important; color: #00bcd4 !important; }
        .btn-action.delete { background-color: #ffebee !important; color: #f44336 !important; }
        .btn-action.view { background-color: #f3e5f5 !important; color: #9c27b0 !important; }
        .btn-action i { font-size: 1rem !important; }
        
        .action-container {
            display: flex;
            gap: 4px;
            justify-content: flex-end;
            flex-wrap: nowrap;
            min-width: 98px;
        }

        /* ── STICKY HEADER – always above table rows ─────── */
        #vehicles-table thead th {
            position: sticky;
            top: 0;
            z-index: 1068;           /* Above row (1065) and dropdown-menu (1067) – header never gets covered */
            background: var(--bs-body-bg, #fff);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
        }

        /* ── Mobile overrides ───────────────────────────────── */
        @media (max-width: 767.98px) {
            .vehicles-table-responsive {
                overflow-x: auto !important;
                overflow-y: auto !important;
                max-height: 70vh;
                -webkit-overflow-scrolling: touch;
            }
            /* Keep all desktop columns visible on mobile via horizontal scroll */
            #vehicles-table { min-width: 980px !important; width: 980px !important; table-layout: auto !important; }
            #vehicles-table th,
            #vehicles-table td { font-size: .78rem; padding: .45rem .3rem !important; }
            #vehicles-table th { font-size: .74rem; }
            #vehicles-table th:first-child,
            #vehicles-table td:first-child,
            #vehicles-table .col-thumb,
            #vehicles-table .col-user,
            #vehicles-table .col-year,
            #vehicles-table .col-details { display: table-cell !important; }
            #vehicles-table td:last-child { width: 100px; }
            .action-container { gap: 4px; }
            .btn-action {
                width: 28px !important;
                height: 28px !important;
            }
            .btn-action i { font-size: 1rem !important; }
            .vehicles-table-responsive,
            #vehicles-table_wrapper,
            #vehicles-table_wrapper .vehicles-table-responsive {
                scrollbar-width: none !important;
                -ms-overflow-style: none !important;
            }
            .vehicles-table-responsive::-webkit-scrollbar,
            #vehicles-table_wrapper::-webkit-scrollbar,
            #vehicles-table_wrapper .vehicles-table-responsive::-webkit-scrollbar {
                width: 0 !important;
                height: 0 !important;
                background: transparent !important;
            }
            .vehicles-table-responsive::-webkit-scrollbar-track,
            .vehicles-table-responsive::-webkit-scrollbar-thumb,
            #vehicles-table_wrapper::-webkit-scrollbar-track,
            #vehicles-table_wrapper::-webkit-scrollbar-thumb,
            #vehicles-table_wrapper .vehicles-table-responsive::-webkit-scrollbar-track,
            #vehicles-table_wrapper .vehicles-table-responsive::-webkit-scrollbar-thumb {
                display: none !important;
                background: transparent !important;
            }
        }
    </style>
@endsection
