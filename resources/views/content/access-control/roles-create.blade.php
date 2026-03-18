@extends('layouts/contentNavbarLayout')

@section('title', 'Add Role')

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

  .form-control, .form-select {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d9dee3;
  }
  .form-control:focus, .form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
  }

  /* Permission List Style */
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
  .perm-item label:hover { color: #696cff; }
  .perm-item.hidden-guard { display: none; }

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
    font-weight: 700;
    font-size: 14px;
    color: #2c2c44;
    border-bottom: 1px solid #e9ecef;
  }
  .perm-module-block .perm-rows-wrap {
    padding: 4px 16px;
  }
  .perm-row {
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid #f0f2f5;
    padding: 6px 0;
  }
  .perm-row:last-child { border-bottom: none; }
  .perm-row:empty { display: none; }

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
      <span class="fw-bold text-primary">Create Role</span>
    </h4>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card role-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.roles.store') }}" method="POST" id="role-create-form">
          @csrf

          {{-- ── Role Details ── --}}
          <div class="section-label mb-3">
            <i class="bx bx-cog"></i> Role Configuration
          </div>

          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="role-name">Role Name</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-shield"></i></span>
                <input type="text" class="form-control" id="role-name" name="name"
                  placeholder="e.g. Sales Manager" value="{{ old('name') }}" required />
              </div>
              @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="guard-name">Authentication Guard</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-open-alt"></i></span>
                <select class="form-select" id="guard-name" name="guard_name" onchange="filterPermissions(this.value)">
                  <option value="admin-guard" {{ old('guard_name', 'admin-guard') == 'admin-guard' ? 'selected' : '' }}>Admin (Staff / Backend)</option>
                  <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Web (Regular Users / Frontend)</option>
                </select>
              </div>
              <small class="text-muted">Determines which authentication system this role applies to.</small>
            </div>
          </div>

          <hr class="my-3 opacity-25">

          {{-- ── Role Permissions ── --}}
          <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
            <div>
              <div class="section-label mb-0">
                <i class="bx bx-key"></i> Role Permissions
              </div>
              <p class="text-muted small mb-0 mt-1">Select the capabilities for this role in the form below</p>
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

          <div class="mt-4" id="perm-matrix">
            @foreach($permissions as $module => $modulePermissions)
            @php $chunks = $modulePermissions->chunk(4); @endphp
            <div class="perm-module-block" data-module-block="{{ $module }}">
              <div class="perm-section-header">
                {{ ucwords(str_replace('-', ' ', $module)) }}
              </div>
              <div class="perm-rows-wrap">
                @foreach($chunks as $chunk)
                <div class="perm-row" data-row-chunk>
                  @foreach($chunk as $permission)
                  @php
                    $parts = explode('-', $permission->name);
                    $action = ucfirst($parts[0] ?? '');
                    $modLabel = ucwords(str_replace('-', ' ', $module));
                    $label = $action . ' ' . $modLabel;
                    $isOldChecked = in_array($permission->name, old('permissions', []));
                  @endphp
                  <div class="perm-item" data-guard="{{ $permission->guard_name }}" data-module="{{ $module }}">
                    <input
                      type="checkbox"
                      name="permissions[]"
                      value="{{ $permission->name }}"
                      id="perm-{{ $permission->id }}"
                      data-guard="{{ $permission->guard_name }}"
                      {{ $isOldChecked ? 'checked' : '' }}
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
              <i class="bx bx-save me-1"></i> Create Role
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function filterPermissions(guard) {
    document.querySelectorAll('[data-module-block]').forEach(block => {
      const items = block.querySelectorAll('.perm-item');
      let visibleInBlock = 0;
      items.forEach(item => {
        const cb = item.querySelector('input');
        if (item.dataset.guard === guard) {
          item.style.display = 'flex';
          cb.disabled = false;
          visibleInBlock++;
        } else {
          item.style.display = 'none';
          cb.disabled = true;
          cb.checked = false;
        }
      });
      block.style.display = visibleInBlock > 0 ? 'block' : 'none';
    });
  }

  function toggleAll(checked) {
    document.querySelectorAll('#role-create-form input[type="checkbox"]:not([disabled])').forEach(cb => {
      cb.checked = checked;
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    filterPermissions(document.getElementById('guard-name').value);
  });
</script>
@endsection
