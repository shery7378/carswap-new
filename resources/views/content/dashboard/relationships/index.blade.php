@extends('layouts/contentNavbarLayout')

@section('title', 'Manage ' . $title)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <h5 class="card-header">Add {{ Str::singular($title) }}</h5>
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

                        <button type="submit" class="btn btn-primary btn-sm">Save {{ Str::singular($title) }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <h5 class="card-header">{{ $title }} List</h5>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    @if($type === 'models')
                                        <th>Brand</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        @if($type === 'models')
                                            <td>
                                                @php $brand = DB::table('brands')->where('id', $item->brand_id)->first(); @endphp
                                                {{ $brand->name ?? 'N/A' }}
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
                                        <td colspan="4" class="text-center">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection