@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Role')

@section('content')
<style>
  .role-form-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  .section-title {
    font-size: 14px;
    font-weight: 700;
    color: #696cff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }
  .section-title i {
    margin-right: 8px;
    font-size: 18px;
  }
  .form-control {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d9dee3;
  }
  .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
  }
  .permission-container {
    background: #fcfcfd;
    border: 1px solid #f0f2f4;
    border-radius: 12px;
    padding: 25px;
  }
  .permission-card {
    transition: all 0.2s;
    border-radius: 8px;
    padding: 10px;
    border: 1px solid transparent;
  }
  .permission-card.selected {
      background: rgba(105, 108, 255, 0.05);
      border-color: rgba(105, 108, 255, 0.2);
  }
  .permission-card:hover {
    background: #fff;
    border-color: #d9dee3;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }
</style>

<div class="d-flex align-items-center mb-4">
  <a href="{{ route('admin.roles.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle">
    <i class="bx bx-chevron-left"></i>
  </a>
  <h4 class="mb-0">
    <span class="text-muted fw-light">Access Control / Roles /</span> 
    <span class="fw-bold text-primary">Edit Role</span>
  </h4>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card role-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="section-title">
            <i class="bx bx-cog"></i> Role Details
          </div>
          
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="role-name">Functional Role Name</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-shield"></i></span>
                <input type="text" class="form-control" id="role-name" name="name" value="{{ $role->name }}" placeholder="e.g. Sales Manager" required />
              </div>
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Active Guard</label>
              <div class="mt-2">
                  <span class="badge {{ $role->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} py-2 px-3 fw-bold">
                    <i class="bx bx-lock-alt me-1"></i> {{ strtoupper($role->guard_name) }}
                  </span>
                  <p class="text-muted small mt-2 mb-0">The authentication guard cannot be changed once a role is created.</p>
              </div>
            </div>
          </div>

          <div class="section-title mt-4">
            <i class="bx bx-key"></i> Adjusted Permission Matrix
          </div>

          <div class="permission-container">
            <p class="text-muted small mb-4">Update the specific capabilities assigned to this role. These changes will reflect immediately for all users holding this role.</p>
            <div class="row">
              @foreach($permissions as $module => $modulePermissions)
              <div class="col-12 mb-4">
                <h6 class="text-primary text-capitalize border-bottom pb-2 mb-3">
                  <i class="bx bx-folder me-1"></i> {{ str_replace('_', ' ', $module) }}
                </h6>
                <div class="row">
                  @foreach($modulePermissions as $permission)
                  <div class="col-md-3 col-sm-4 mb-2">
                    <div class="permission-card px-2 py-1 {{ in_array($permission->name, $rolePermissions) ? 'selected' : '' }}">
                      <div class="form-check custom-option-basic">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium small mb-0" for="perm-{{ $permission->id }}">
                          {{ str_replace('-' . $module, '', $permission->name) }}
                        </label>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              @endforeach
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12 d-flex justify-content-end gap-3">
              <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4">Discard changes</a>
              <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-save me-1"></i> Save Changes
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
