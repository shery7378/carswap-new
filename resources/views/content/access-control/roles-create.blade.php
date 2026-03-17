@extends('layouts/contentNavbarLayout')

@section('title', 'Add Role')

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
  .form-control, .form-select {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d9dee3;
  }
  .form-control:focus, .form-select:focus {
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
    <span class="fw-bold text-primary">Create Role</span>
  </h4>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card role-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.roles.store') }}" method="POST">
          @csrf
          
          <div class="section-title">
            <i class="bx bx-cog"></i> Role Configuration
          </div>
          
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="role-name">Functional Role Name</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-shield"></i></span>
                <input type="text" class="form-control" id="role-name" name="name" placeholder="e.g. Sales Manager" value="{{ old('name') }}" required />
              </div>
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="guard-name">Authentication Guard</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-open-alt"></i></span>
                <select class="form-select" id="guard-name" name="guard_name" onchange="filterPermissions()">
                  <option value="admin-guard" {{ old('guard_name', 'admin-guard') == 'admin-guard' ? 'selected' : '' }}>Admin (Staff/Backend)</option>
                  <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Web (Regular Users/Frontend)</option>
                </select>
              </div>
              <small class="text-muted">Determines which authentication system this role applies to.</small>
            </div>
          </div>

          <div class="section-title mt-4">
            <i class="bx bx-key"></i> Permission Matrix
          </div>

          <div class="permission-container">
            <p class="text-muted small mb-4">Select the specific capabilities this role should possess within the chosen guard.</p>
            <div class="row" id="permissions-row">
              @foreach($permissions as $permission)
              <div class="col-md-4 col-lg-3 mb-2 permission-item" data-guard="{{ $permission->guard_name }}">
                <div class="permission-card">
                  <div class="form-check custom-option-basic">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                    <label class="form-check-label fw-medium h6 mb-0" for="perm-{{ $permission->id }}">
                      {{ $permission->name }}
                    </label>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12 d-flex justify-content-end gap-3">
              <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4">Discard changes</a>
              <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-save me-1"></i> Finalize & Save Role
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function filterPermissions() {
    const selectedGuard = document.getElementById('guard-name').value;
    const items = document.querySelectorAll('.permission-item');
    let visibleCount = 0;

    items.forEach(item => {
      const input = item.querySelector('input');
      if (item.dataset.guard === selectedGuard) {
        item.style.display = 'block';
        input.disabled = false;
        visibleCount++;
      } else {
        item.style.display = 'none';
        input.disabled = true;
        input.checked = false;
      }
    });

    // If no permissions found for this guard
    const row = document.getElementById('permissions-row');
    const existingEmptyMsg = document.getElementById('no-perms-msg');
    
    if (visibleCount === 0) {
        if (!existingEmptyMsg) {
            const msg = document.createElement('div');
            msg.id = 'no-perms-msg';
            msg.className = 'col-12 text-center py-4 text-muted italic';
            msg.innerHTML = '<i class="bx bx-info-circle mb-2 d-block fs-2"></i> No permissions registered for this guard.';
            row.appendChild(msg);
        }
    } else if (existingEmptyMsg) {
        existingEmptyMsg.remove();
    }
  }

  // Run on page load
  document.addEventListener('DOMContentLoaded', filterPermissions);
</script>
@endsection
