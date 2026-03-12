@extends('layouts/contentNavbarLayout')

@section('title', 'User Management')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control /</span> Users
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Users List</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Roles</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($users as $user)
        <tr>
          <td>{{ $user->first_name }} {{ $user->last_name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @foreach($user->roles as $role)
            <span class="badge bg-label-info">{{ $role->name }}</span>
            @endforeach
          </td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
  <div class="card-footer">
    {{ $users->links() }}
  </div>
</div>
@endsection
