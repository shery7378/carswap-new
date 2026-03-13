@extends('layouts/contentNavbarLayout')

@section('title', 'Partners')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Partners List</h5>
                <a href="{{ route('admin.partners.create') }}" class="btn btn-primary btn-sm">Add New Partner</a>
            </div>
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
                                            <img src="{{ asset('storage/' . $partner->image) }}"
                                                alt="{{ $partner->name }}" width="50" class="rounded">
                                        @else
                                            <span class="badge bg-secondary">No Logo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $partner->name }}</strong>
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
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.partners.edit', $partner->id) }}"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Are you sure?')"><i
                                                            class="bx bx-trash me-1"></i> Delete</button>
                                                </form>
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
                    {{ $partners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
