@extends('layouts/contentNavbarLayout')

@section('title', 'Partners')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Partners List</h5>
                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-partners', 'admin-guard'))
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm">Add New Partner</a>
                @endif
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="partners-table">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($partners as $partner)
                                <tr>
                                    <td>
                                        @if($partner->image)
                                            <a href="{{ route('admin.partners.show', $partner->id) }}">
                                                <img src="{{ asset('storage/' . $partner->image) }}"
                                                    alt="{{ $partner->name }}" width="50" class="rounded shadow-xs">
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">No Logo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.partners.show', $partner->id) }}" class="text-body fw-bold">
                                            {{ $partner->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <small>
                                            Email: {{ $partner->email ?? 'N/A' }}<br>
                                            Phone: {{ $partner->phone ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($partner->is_active)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.partners.edit', $partner->id) }}"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                @endif

                                                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('delete-partners', 'admin-guard'))
                                                <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure?')"><i
                                                            class="bx bx-trash me-1"></i> Delete</button>
                                                </form>
                                                @endif

                                                @if(!(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-partners', 'admin-guard')))
                                                    <span class="dropdown-item disabled">No permissions</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No partners found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{-- {{ $partners->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    $('#partners-table').DataTable({
        "order": [[ 1, "asc" ]],
        "pageLength": 10,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        "language": {
            "search": "",
            "searchPlaceholder": "Search Partners...",
            "paginate": {
                "next": '<i class="bx bx-chevron-right"></i>',
                "previous": '<i class="bx bx-chevron-left"></i>'
            }
        }
    });
});
</script>
<style>
.dataTables_filter input {
    border-radius: 0.5rem;
    padding: 0.375rem 0.75rem;
    border: 1px solid #d9dee3;
    margin-bottom: 1rem;
    width: 250px;
}
.dataTables_paginate .pagination {
    justify-content: flex-end !important;
}
.dataTables_length {
    margin-bottom: 1rem;
}
.dataTables_length select {
    border-radius: 0.5rem;
    padding: 0.25rem 0.5rem;
    border: 1px solid #d9dee3;
    margin: 0 5px;
}
</style>
@endsection
