@extends('layouts/contentNavbarLayout')

@section('title', 'Edit User')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control / Users /</span> Edit User
</h4>

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Edit User: {{ $user->first_name }} {{ $user->last_name }}</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="first_name">First Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}" required />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="last_name">Last Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}" required />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="email">Email</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required />
              @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="password">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password" />
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Roles</label>
            <div class="col-sm-10">
              <div class="row">
                @foreach($roles as $role)
                <div class="col-md-3">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                    <label class="form-check-label" for="role-{{ $role->id }}">
                      {{ $role->name }}
                    </label>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Update User</button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
