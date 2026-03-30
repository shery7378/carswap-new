@extends('layouts/contentNavbarLayout')

@section('title', 'Partners')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">Partners Management</h5>
                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-partners', 'admin-guard'))
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="bx bx-plus me-1"></i> Add New Partner
                </a>
                @endif
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible shadow-xs mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
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
                        <tbody class="table-border-bottom-0">
                            @forelse($partners as $partner)
                                <tr>
                                    <td>
                                        @if($partner->image)
                                            <a href="{{ route('admin.partners.show', $partner->id) }}">
                                                <img src="{{ asset('storage/' . $partner->image) }}"
                                                    alt="{{ $partner->name }}" width="55" height="55" 
                                                    class="rounded-circle shadow-xs border" style="object-fit: cover;">
                                            </a>
                                        @else
                                            <div class="avatar avatar-md me-2">
                                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.partners.show', $partner->id) }}" class="text-body fw-bold fs-6">
                                            {{ $partner->name }}
                                        </a>
                                        @if($partner->website)
                                            <br><small class="text-primary"><i class="bx bx-link-external small me-1"></i>{{ str_replace(['http://', 'https://'], '', $partner->website) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <small class="text-muted"><i class="bx bx-envelope me-1"></i> {{ $partner->email ?? 'N/A' }}</small>
                                            <small class="text-muted"><i class="bx bx-phone me-1"></i> {{ $partner->phone ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <span class="badge {{ $partner->is_active ? 'bg-label-success' : 'bg-label-danger' }} rounded-pill px-3 py-2">
                                                <i class="bx bx-circle me-1 small"></i> {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button type="button" class="btn btn-icon btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.partners.show', $partner->id) }}">
                                                    <i class="bx bx-show-alt me-1 text-primary"></i> View Details
                                                </a>
                                                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-partners', 'admin-guard'))
                                                <a class="dropdown-item" href="{{ route('admin.partners.edit', $partner->id) }}">
                                                    <i class="bx bx-edit-alt me-1 text-info"></i> Edit Partner
                                                </a>
                                                @endif

                                                @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('delete-partners', 'admin-guard'))
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="confirmDelete(event, this)">
                                                        <i class="bx bx-trash me-1"></i> Remove
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
                                        <div class="text-muted opacity-50 mb-2 mt-2">
                                            <i class="bx bx-folder-open display-4"></i>
                                        </div>
                                        <h6 class="mb-0">No partners found.</h6>
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

@section('page-script')
<script>
    $(document).ready(function() {
        $('#partners-table').DataTable({
            "order": [[1, "asc"]],
            "pageLength": 25,
            "language": {
                "search": "",
                "searchPlaceholder": "Search partners...",
                "paginate": {
                    "next": '<i class="bx bx-chevron-right"></i>',
                    "previous": '<i class="bx bx-chevron-left"></i>'
                }
            },
            "dom": '<"row mx-2"' +
                   '<"col-md-2"<"me-3"l>>' +
                   '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
                   '>t' +
                   '<"row mx-2"' +
                   '<"col-sm-12 col-md-6"i>' +
                   '<"col-sm-12 col-md-6"p>' +
                   '>',
            "buttons": []
        });
    });

    function confirmDelete(e, form) {
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
                form.closest('form').submit();
            }
        });
    }
</script>
@endsection

<style>
.dataTables_filter {
    width: 300px;
}
.dataTables_filter input {
    width: 100% !important;
    border-radius: 0.5rem;
    padding: 0.45rem 0.8rem;
    border: 1px solid #d9dee3;
    margin-left: 0 !important;
}
.dataTables_length select {
    border-radius: 0.5rem;
    padding: 0.45rem 0.5rem;
    border: 1px solid #d9dee3;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
#partners-table thead th {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
</style>
@endsection
