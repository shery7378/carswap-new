@extends('layouts/contentNavbarLayout')

@section('title', 'CMS Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="py-3 mb-0">
        <span class="text-muted fw-light">App /</span> Page Content (CMS)
    </h4>
    <a href="{{ route('admin.cms.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Add New Section
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">Website Sections</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($sections as $section)
                    <tr>
                        <td><strong>{{ $section->name }}</strong></td>
                        <td><code>{{ $section->slug }}</code></td>
                        <td><span class="badge bg-label-info">{{ $section->items_count }} items</span></td>
                        <td>
                            <span class="badge {{ $section->status ? 'bg-label-success' : 'bg-label-danger' }}">
                                {{ $section->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $section->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-menu-item dropdown-item" href="{{ route('admin.cms.edit', $section->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.cms.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this section?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No CMS sections found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
