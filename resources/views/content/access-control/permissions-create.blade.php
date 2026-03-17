@extends('layouts/contentNavbarLayout')

@section('title', 'Add Permission')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Access Control / Permissions /</span> Add Permission
</h4>

<div class="row">
  <div class="col-xxl">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Create New Permission</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
          @csrf
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="permission-name">Permission Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="permission-name" name="name" placeholder="Enter permission name (e.g., manage-users)" value="{{ old('name') }}" required />
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label" for="guard-name">Guard</label>
            <div class="col-sm-10">
              <select class="form-select" id="guard-name" name="guard_name">
                <option value="admin-guard" {{ old('guard_name') == 'admin-guard' ? 'selected' : '' }}>Admin (admin-guard)</option>
                <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Users (web)</option>
              </select>
              @error('guard_name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Save Permission</button>
              <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
