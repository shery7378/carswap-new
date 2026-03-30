@extends('layouts/contentNavbarLayout')

@section('title', 'Partners')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <!-- ✅ HEADER FIXED -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="mb-0 fw-bold">Partners Management</h5>

                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-partners', 'admin-guard'))
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="bx bx-plus me-1"></i> Add New Partner
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
                                    <th style="width: 80px;">Logo</th>
                                    <th>Partner Name</th>
                                    <th>Contact Information</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($partners as $partner)
                                    <tr>
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
                                            <small class="text-muted d-block">
                                                <i class="bx bx-envelope me-1"></i>
                                                {{ $partner->email ?? 'N/A' }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="bx bx-phone me-1"></i>
                                                {{ $partner->phone ?? 'N/A' }}
                                            </small>
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $partner->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>

                                                <div class="dropdown-menu">

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.partners.show', $partner->id) }}">
                                                        <i class="bx bx-show-alt me-1 text-primary"></i> View
                                                    </a>

                                                    @if(auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.partners.edit', $partner->id) }}">
                                                            <i class="bx bx-edit-alt me-1 text-info"></i> Edit
                                                        </a>
                                                    @endif

                                                    @if(auth('admin-guard')->user()->hasPermissionTo('delete-partners', 'admin-guard'))
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="confirmDelete(event, this)">
                                                                <i class="bx bx-trash me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bx bx-folder-open display-4 text-muted"></i>
                                            <p class="mt-2 mb-0">No partners found.</p>
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
                    searchPlaceholder: "Search partners...",
                    paginate: {
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>'
                    }
                }
            });

        });

        // ✅ DELETE CONFIRM
        function confirmDelete(e, el) {
            e.preventDefault();

            Swal.fire({
                title: 'Delete Partner?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    el.closest('form').submit();
                }
            });
        }
    </script>

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
    </style>
@endsection