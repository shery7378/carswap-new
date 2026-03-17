@extends('layouts/contentNavbarLayout')

@section('title', 'Permission Management')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control /</span> Permissions
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Permissions List</h5>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Add Permission</a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Guard</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($permissions as $permission)
        <tr>
          <td>{{ $permission->name }}</td>
          <td>{{ $permission->guard_name }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.permissions.edit', $permission->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="dropdown-item"><i class="bx bx-trash me-1"></i> Delete</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
