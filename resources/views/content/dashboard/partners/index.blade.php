@extends('layouts/contentNavbarLayout')

@section('title', 'Partners')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ✅ HEADER FIXED -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="mb-0 fw-bold">{{ __('Partners Management') }}</h5>

                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-partners', 'admin-guard'))
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="bx bx-plus me-1"></i> {{ __('Add New Partner') }}
                        </a>
                    @endif
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible shadow-xs mb-4">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-top" id="partners-table">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th style="width: 80px;">{{ __('Logo') }}</th>
                                    <th>{{ __('Partner Name') }}</th>
                                    <th>{{ __('Contact Information') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center" style="width: 100px;">{{ __('Actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($partners as $partner)
                                    <tr data-id="{{ $partner->id }}">
                                        <td>
                                            @if($partner->image)
                                                <img src="{{ asset('storage/' . $partner->image) }}" width="55" height="55"
                                                    class="rounded-circle shadow-xs border" style="object-fit: cover;">
                                            @else
                                                <div class="avatar avatar-md">
                                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <strong>{{ $partner->name }}</strong>
                                            @if($partner->website)
                                                <br>
                                                <small class="text-primary">
                                                    <i class="bx bx-link-external me-1"></i>
                                                    {{ str_replace(['http://', 'https://'], '', $partner->website) }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            <small class="text-muted d-block mb-1">
                                                <i class="bx bx-envelope me-1"></i>
                                                {{ $partner->email ?? 'N/A' }}
                                            </small>
                                            <small class="text-muted d-block mb-1">
                                                <i class="bx bx-phone me-1"></i>
                                                {{ $partner->phone ?? 'N/A' }}
                                            </small>
                                            @if($partner->address)
                                                <small class="text-dark d-block">
                                                    <i class="bx bx-map-pin me-1 text-primary"></i>
                                                    {{ Str::limit($partner->address, 35) }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            @if(auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                                                <div class="dropdown">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-{{ $partner->is_active ? 'label-success' : 'label-danger' }} dropdown-toggle hide-arrow p-1 px-2" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false"
                                                            data-bs-popper-config='{"strategy":"fixed"}'>
                                                        {{ $partner->is_active ? __('Active') : __('Inactive') }}
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="z-index: 1060; min-width: 120px;">
                                                        <li>
                                                            <form action="{{ route('admin.partners.toggle-status', $partner->id) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                                                    <i class="bx {{ $partner->is_active ? 'bx-block text-danger' : 'bx-check-circle text-success' }} me-2 fs-5"></i>
                                                                    <span>{{ $partner->is_active ? __('Set Inactive') : __('Set Active') }}</span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="badge {{ $partner->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                    {{ $partner->is_active ? __('Active') : __('Inactive') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <a href="{{ route('admin.partners.show', $partner->id) }}"
                                                   class="btn btn-icon btn-sm btn-label-secondary border-0 shadow-none"
                                                   data-bs-toggle="tooltip" title="Full Page">
                                                    <i class="bx bx-show"></i>
                                                </a>

                                                @if(auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                                                    <a href="{{ route('admin.partners.edit', $partner->id) }}"
                                                       class="btn btn-icon btn-sm btn-label-info border-0 shadow-none"
                                                       data-bs-toggle="tooltip" title="Edit Partner">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                @endif

                                                @if(auth('admin-guard')->user()->hasPermissionTo('delete-partners', 'admin-guard'))
                                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-confirmation"
                                                            data-bs-toggle="tooltip" title="Delete Partner">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bx bx-folder-open display-4 text-muted"></i>
                                            <p class="mt-2 mb-0">{{ __('No partners found.') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Partner Details Modal -->
    <div class="modal fade" id="partnerDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" id="modal-loader-content">
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                    <p class="mt-2 text-muted">{{ __('Fetching partner details...') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function () {

            $('#partners-table').DataTable({
                order: [[1, "asc"]],
                pageLength: 25,

                // ✅ FIXED ALIGNMENT
                dom:
                    "<'row align-items-center mb-3'<'col-md-6 d-flex align-items-center'l><'col-md-6 d-flex justify-content-end'f>>" +
                    "t" +
                    "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",

                language: {
                    search: "",
                    searchPlaceholder: "{{ __('Search partners...') }}",
                    lengthMenu: "{{ __('Show _MENU_ entries') }}",
                    info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                    paginate: {
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>'
                    }
                }
            });

        });


        // ✅ MODAL TRIGGER ON ROW CLICK
        $(document).on('click', '#partners-table tbody tr', function (e) {
            // IGNORE DOTS OR ACTIONS
            if ($(e.target).closest('.dropdown-menu, .dropdown-toggle, .btn-close, form').length) return;

            const partnerId = $(this).data('id');
            if (!partnerId) return;

            const modal = new bootstrap.Modal(document.getElementById('partnerDetailsModal'));
            const container = document.getElementById('modal-loader-content');

            // SHOW LOADER
            container.innerHTML = `
                    <div class="modal-body text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted small">{{ __('Loading partner information...') }}</p>
                    </div>
                `;

            modal.show();

            // FETCH VIA AJAX
            fetch(`{{ url('/app/partners') }}/${partnerId}?modal=1`)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;

                    // RE-INIT MAP IF SCRIPT EXISTS IN HTML
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        newScript.text = oldScript.text;
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                })
                .catch(err => {
                    container.innerHTML = `<div class="modal-body text-center py-5 text-danger">Failed to load details.</div>`;
                });
        });
    </script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    <style>
        /* Search box */
        .dataTables_filter input {
            width: 220px !important;
            border-radius: 8px;
            padding: 6px 10px;
            border: 1px solid #d9dee3;
        }

        /* Align header */
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
            border: 1px solid #d9dee3 !important;
            min-width: 80px !important;
        }

        /* Pagination */
        .dataTables_paginate {
            display: flex;
            justify-content: flex-end;
        }

        /* Table header */
        #partners-table thead th {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Shadow */
        .shadow-xs {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Mobile fix */
        @media (max-width: 768px) {
            .dataTables_filter {
                justify-content: start !important;
                margin-top: 10px;
            }
        }

        #partners-table tbody tr {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        #partners-table tbody tr:hover {
            background-color: rgba(105, 108, 255, 0.04) !important;
        }

        /* Dropdown fix for table-responsive */
        .table-responsive {
            overflow: visible !important;
        }
        
        .dropdown-menu {
            margin-top: 0.125rem !important;
        }
    </style>
@endsection