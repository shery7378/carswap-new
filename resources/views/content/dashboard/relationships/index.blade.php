@extends('layouts/contentNavbarLayout')

@section('title', 'Manage ' . $title)

@section('content')
<div class="row">
    <!-- Form Side -->
    <div class="col-md-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold text-primary"><i class="bx bx-plus me-1"></i> Add {{ Str::singular($title) }}</h5>
            </div>
            <div class="card-body pt-4">
                <form action="{{ route('admin.vehicle-settings.store', $type) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name / Megnevezés *</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter {{ Str::lower(Str::singular($title)) }} name...">
                    </div>

                    @if($type === 'models')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link to Brand *</label>
                            <select class="form-select select2-brand" name="brand_id" required>
                                <option value="">Select Brand</option>
                                @php $brands = DB::table('brands')->where('is_active', true)->orderBy('name')->get(); @endphp
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center shadow-sm py-2">
                        <i class="bx bx-save me-2"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Side -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold">{{ $title }} Management</h5>
                <span class="badge bg-label-info">{{ count($items) }} Total</span>
            </div>
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="p-3">
                        <div class="alert alert-success alert-dismissible mb-0" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle" id="relationships-table">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Name / Label</th>
                                @if($type === 'models')
                                    <th>Parent Brand</th>
                                @endif
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($items as $item)
                                <tr>
                                    <td><span class="text-muted small">#{{ $item->id }}</span></td>
                                    <td><span class="fw-bold fs-6">{{ $item->name }}</span></td>
                                    @if($type === 'models')
                                        <td>
                                            @php $brand = DB::table('brands')->where('id', $item->brand_id)->first(); @endphp
                                            <span class="badge bg-label-primary px-3 rounded-pill">
                                                <i class="bx bx-purchase-tag-alt me-1 small"></i> {{ $brand->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                data-id="{{ $item->id }}" 
                                                data-type="{{ $type }}"
                                                {{ ($item->is_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-label-info border-0 edit-btn"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                @if($type === 'models') data-brand="{{ $item->brand_id }}" @endif
                                                data-bs-toggle="modal" data-bs-target="#editModal">
                                                <i class="bx bx-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.vehicle-settings.destroy', [$type, $item->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $type === 'models' ? 5 : 4 }}" class="text-center py-5">
                                        <div class="opacity-25 mb-2"><i class="bx bx-layers display-4"></i></div>
                                        <h6 class="text-muted">No {{ Str::lower($title) }} records found yet.</h6>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit {{ Str::singular($title) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name / Megnevezés *</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>

                    @if($type === 'models')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Link to Brand *</label>
                            <select class="form-select" name="brand_id" id="edit_brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach(DB::table('brands')->where('is_active', true)->orderBy('name')->get() as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function() {
        $('#relationships-table').DataTable({
            "order": [[1, "asc"]],
            "pageLength": 25,
            "language": {
                "search": "",
                "searchPlaceholder": "Search {{ Str::lower($title) }}...",
                "paginate": {
                    "next": '<i class="bx bx-chevron-right"></i>',
                    "previous": '<i class="bx bx-chevron-left"></i>'
                }
            },
            "dom": '<"row mx-2"' +
                   '<"col-md-3"<"me-3"l>>' +
                   '<"col-md-9"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
                   '>t' +
                   '<"row mx-2"' +
                   '<"col-sm-12 col-md-6"i>' +
                   '<"col-sm-12 col-md-6"p>' +
                   '>',
            "buttons": []
        });

        // Edit Button Click
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const brandId = $(this).data('brand');
            const type = '{{ $type }}';

            $('#edit_name').val(name);
            $('#edit_brand_id').val(brandId);
            $('#editForm').attr('action', `{{ url('/app/vehicle-settings') }}/${type}/${id}`);
        });

        // Status Toggle Change
        $('.status-toggle').on('change', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const checked = $(this).prop('checked');
            
            $.ajax({
                url: `{{ url('/app/vehicle-settings') }}/${type}/${id}/toggle-status`,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Status updated successfully');
                    }
                },
                error: function() {
                    toastr.error('Failed to update status');
                }
            });
        });
    });

    function confirmDelete(e, btn) {
        e.preventDefault();
        Swal.fire({
            title: 'Permanent Delete?',
            text: "This relation item will be removed forever!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endsection

<style>
.dataTables_filter {
    width: 250px;
}
.dataTables_filter input {
    width: 100% !important;
    border-radius: 0.5rem !important;
    padding: 0.45rem 0.8rem !important;
    border: 1px solid #d9dee3 !important;
    margin-left: 0 !important;
}
.dataTables_length select {
    border-radius: 0.4rem !important;
    padding: 0.35rem 1.5rem 0.35rem 0.5rem !important;
    min-width: 80px !important;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
#relationships-table thead th {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
</style>
@endsection