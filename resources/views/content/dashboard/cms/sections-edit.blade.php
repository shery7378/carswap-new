@extends('layouts/contentNavbarLayout')

@section('title', 'Edit CMS Section')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">CMS /</span> Edit Section: {{ $section->name }}</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Section Details -->
    <div class="col-md-5">
        <div class="card mb-4">
            <h5 class="card-header">Section Details</h5>
            <div class="card-body">
                <form action="{{ route('admin.cms.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label" for="name">Internal Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $section->name }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="slug">Slug (Unique Key)</label>
                        <input type="text" class="form-control" name="slug" value="{{ $section->slug }}" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="title">Public Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $section->title }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="subtitle">Subtitle / Caption</label>
                        <input type="text" class="form-control" name="subtitle" value="{{ $section->subtitle }}" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">Main Description / Content</label>
                        <textarea class="form-control" name="description" rows="3">{{ $section->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image Header (Optional)</label>
                        @if($section->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$section->image) }}" width="100" class="rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" class="form-control" name="image" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $section->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$section->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Update Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Items List (Grid data as shown in user image) -->
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Section Items / Grid Elements</h5>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bx bx-plus me-1"></i> Add New Item
                </button>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Icon/Image</th>
                            <th>Title</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($section->items as $item)
                            <tr>
                                <td>
                                    @if($item->icon)
                                        <div class="badge bg-label-info p-2"><i class="bx {{ $item->icon }}"></i></div>
                                    @elseif($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" width="32" height="32" class="rounded">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td><span class="badge bg-secondary">{{ $item->order }}</span></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-icon btn-outline-primary me-2 edit-item" 
                                                data-item="{{ json_encode($item) }}" data-bs-toggle="modal" data-bs-target="#editItemModal">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.cms.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger shadow-none">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No grid items found for this section.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.cms.items.store', $section->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Grid Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Item Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Easy Exchange" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon Class (Boxicons)</label>
                        <input type="text" name="icon" class="form-control" placeholder="bx-car">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Custom Image (Optional)</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Item Modal (Simple implementation) -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="editItemForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Grid Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Item Title</label>
                    <input type="text" id="edit-item-title" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="edit-item-description" name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" id="edit-item-icon" name="icon" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" id="edit-item-order" name="order" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.edit-item').forEach(button => {
    button.addEventListener('click', function() {
        const item = JSON.parse(this.dataset.item);
        const form = document.querySelector('#editItemForm');
        form.action = `/app/cms/items/${item.id}`;
        
        document.querySelector('#edit-item-title').value = item.title;
        document.querySelector('#edit-item-description').value = item.description;
        document.querySelector('#edit-item-icon').value = item.icon || '';
        document.querySelector('#edit-item-order').value = item.order || 0;
    });
});
</script>
@endsection
