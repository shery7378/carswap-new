@extends('layouts/contentNavbarLayout')

@section('title', 'Manage ' . $title)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <h5 class="card-header text-primary fw-bold">Add {{ Str::singular($title) }}</h5>
                <div class="card-body">
                    <form action="{{ route('admin.vehicle-settings.store', $type) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="Enter name...">
                        </div>

                        @if($type === 'models')
                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <select class="form-select" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    @php $brands = DB::table('brands')->get(); @endphp
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center">
                            <i class="bx bx-check me-1"></i> Save {{ Str::singular($title) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <h5 class="card-header bg-label-white border-bottom fw-bold">{{ $title }} List</h5>
                <div class="card-body pt-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="relationships-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th>Name</th>
                                    @if($type === 'models')
                                        <th>Brand</th>
                                    @endif
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><strong>{{ $item->name }}</strong></td>
                                        @if($type === 'models')
                                            <td>
                                                @php $brand = DB::table('brands')->where('id', $item->brand_id)->first(); @endphp
                                                <span class="badge bg-label-info">{{ $brand->name ?? 'N/A' }}</span>
                                            </td>
                                        @endif
                                        <td>
                                            <form action="{{ route('admin.vehicle-settings.destroy', [$type, $item->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this item?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $type === 'models' ? 4 : 3 }}" class="text-center">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{-- {{ $items->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    $('#relationships-table').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 10,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        "language": {
            "search": "",
            "searchPlaceholder": "Search {{ $title }}...",
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
    width: 200px;
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