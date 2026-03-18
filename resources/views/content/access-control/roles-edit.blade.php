@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Role')

@section('content')
<style>
  .role-form-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  .section-label {
    font-size: 13px;
    font-weight: 700;
    color: #696cff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
  }
  .section-label i { margin-right: 8px; font-size: 16px; }

  .form-control {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d9dee3;
  }
  .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
  }

  /* Permission List Style */
  .perm-section {
    margin-bottom: 0;
  }
  .perm-section-header {
    font-weight: 700;
    font-size: 14px;
    color: #2c2c44;
    padding: 14px 0 8px 0;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 0;
  }
  .perm-row {
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid #f0f2f5;
    padding: 6px 0;
  }
  .perm-row:last-child {
    border-bottom: none;
  }
  .perm-item {
    width: 25%;
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px 5px 0;
  }
  .perm-item input[type="checkbox"] {
    width: 14px;
    height: 14px;
    accent-color: #696cff;
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 1px;
  }
  .perm-item label {
    font-size: 13px;
    color: #3c5a78;
    cursor: pointer;
    margin-bottom: 0;
    user-select: none;
    line-height: 1.4;
  }
  .perm-item label:hover {
    color: #696cff;
  }
  .perm-module-block {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 18px;
    background: #fff;
  }
  .perm-module-block .perm-section-header {
    background: #f8f9fd;
    padding: 10px 16px;
    margin-bottom: 0;
    border-bottom: 1px solid #e9ecef;
  }
  .perm-module-block .perm-rows-wrap {
    padding: 4px 16px;
  }
  .perm-module-block .perm-row {
    border-bottom: 1px solid #f0f2f5;
  }
  .perm-module-block .perm-row:last-child {
    border-bottom: none;
  }
  @media (max-width: 768px) {
    .perm-item { width: 50%; }
  }
</style>

<div class="d-flex align-items-center mb-4">
  <a href="{{ route('admin.roles.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle">
    <i class="bx bx-chevron-left"></i>
  </a>
  <div>
    <h4 class="mb-0">
      <span class="text-muted fw-light">Access Control / Roles /</span>
      <span class="fw-bold text-primary">Edit Role</span>
    </h4>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card role-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" id="role-edit-form">
          @csrf
          @method('PUT')

          {{-- ── Role Details ── --}}
          <div class="section-label mb-3">
            <i class="bx bx-cog"></i> Role Details
          </div>

          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="role-name">Role Name</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-shield"></i></span>
                <input type="text" class="form-control" id="role-name" name="name"
                  value="{{ $role->name }}" placeholder="e.g. Sales Manager" required />
              </div>
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Guard</label>
              <div class="mt-2">
                <span class="badge {{ $role->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} py-2 px-3 fw-bold">
                  <i class="bx bx-lock-alt me-1"></i> {{ strtoupper($role->guard_name) }}
                </span>
                <p class="text-muted small mt-2 mb-0">Guard cannot be changed after creation.</p>
              </div>
            </div>
          </div>

          <hr class="my-3 opacity-25">

          {{-- ── Role Permissions ── --}}
          <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
            <div>
              <div class="section-label mb-0">
                <i class="bx bx-key"></i> Role Permissions
              </div>
              <p class="text-muted small mb-0 mt-1">Update the role permissions in the form below</p>
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-label-primary" onclick="toggleAll(true)">
                <i class="bx bx-check-square me-1"></i> Select All
              </button>
              <button type="button" class="btn btn-sm btn-label-secondary" onclick="toggleAll(false)">
                <i class="bx bx-square me-1"></i> Deselect All
              </button>
            </div>
          </div>

          <div class="mt-4">
            @foreach($permissions as $module => $modulePermissions)
            @php
              // Chunk permissions into rows of 4
              $chunks = $modulePermissions->chunk(4);
            @endphp
            <div class="perm-module-block">
              <div class="perm-section-header">
                {{ ucwords(str_replace('-', ' ', $module)) }}
              </div>
              <div class="perm-rows-wrap">
                @foreach($chunks as $chunk)
                <div class="perm-row">
                  @foreach($chunk as $permission)
                  @php
                    $isChecked = in_array($permission->name, $rolePermissions);
                    $parts = explode('-', $permission->name);
                    $action = ucfirst($parts[0] ?? '');
                    $modLabel = ucwords(str_replace('-', ' ', $module));
                    $label = $action . ' ' . $modLabel;
                  @endphp
                  <div class="perm-item">
                    <input
                      type="checkbox"
                      name="permissions[]"
                      value="{{ $permission->name }}"
                      id="perm-{{ $permission->id }}"
                      {{ $isChecked ? 'checked' : '' }}
                    >
                    <label for="perm-{{ $permission->id }}">{{ $label }}</label>
                  </div>
                  @endforeach
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>

          <div class="d-flex justify-content-end gap-3 mt-4">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4">Discard</a>
            <button type="submit" class="btn btn-primary px-5 shadow-sm">
              <i class="bx bx-save me-1"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleAll(checked) {
    document.querySelectorAll('#role-edit-form input[type="checkbox"]').forEach(cb => {
      cb.checked = checked;
    });
  }
</script>
@endsection
