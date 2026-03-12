@extends('layouts/contentNavbarLayout')

@section('title', 'Add Role')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control / Roles /</span> Add Role
</h4>

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Create New Role</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST">
          @csrf
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="role-name">Role Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="role-name" name="name" placeholder="Enter role name" required />
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Permissions</label>
            <div class="col-sm-10">
              <div class="row">
                @foreach($permissions as $permission)
                <div class="col-md-3">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                      {{ $permission->name }}
                    </label>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Save Role</button>
              <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
