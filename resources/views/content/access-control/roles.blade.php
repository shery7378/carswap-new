@extends('layouts/contentNavbarLayout')

@section('title', 'Role Management')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control /</span> Roles
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Roles List</h5>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Add Role</a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Permissions</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($roles as $role)
        <tr>
          <td>{{ $role->name }}</td>
          <td>
            @foreach($role->permissions as $permission)
            <span class="badge bg-label-primary">{{ $permission->name }}</span>
            @endforeach
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-menu-item" href="{{ route('admin.roles.edit', $role->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
