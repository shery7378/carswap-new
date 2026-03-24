@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Administrator')

@section('content')
<style>
  .admin-form-card { border:none; border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,0.06); }
  .section-title {
    font-size:13px; font-weight:700; color:#696cff; text-transform:uppercase;
    letter-spacing:.6px; margin-bottom:18px; display:flex; align-items:center; gap:8px;
  }
  .form-control, .form-select { border-radius:8px; padding:10px 15px; border:1px solid #d9dee3; }
  .form-control:focus { border-color:#696cff; box-shadow:0 0 0 .2rem rgba(105,108,255,.1); }

  /* Module Permission Cards */
  .module-perm-card {
    border:1.5px solid #e9ecef;
    border-radius:12px;
    overflow:hidden;
    margin-bottom:16px;
    transition: box-shadow .2s;
  }
  .module-perm-card:hover { box-shadow:0 4px 16px rgba(105,108,255,.1); border-color:#c5c7ff; }
  .module-perm-header {
    background: linear-gradient(90deg, #f5f5ff 0%, #fafaff 100%);
    border-bottom:1.5px solid #e9ecef;
    padding:10px 16px;
    display:flex; align-items:center; justify-content:space-between;
  }
  .module-perm-header .module-name {
    font-weight:700; font-size:14px; color:#2c2c44;
    display:flex; align-items:center; gap:8px;
  }
  .module-perm-body { padding:14px 16px; }
  .perm-action-pill {
    display:inline-flex; align-items:center; gap:6px;
    border:1.5px solid #e2e3ff;
    border-radius:20px;
    padding:5px 14px;
    margin:4px;
    cursor:pointer;
    font-size:12.5px;
    font-weight:500;
    color:#555;
    transition:all .2s;
    background:#fff;
    user-select:none;
  }
  .perm-action-pill:hover { background:#eef0ff; border-color:#696cff; color:#696cff; }
  .perm-action-pill.is-checked { background:#696cff; border-color:#696cff; color:#fff !important; }
  .perm-action-pill input[type="checkbox"] { display:none; }
  .perm-action-pill i { font-size:14px; }
  .select-all-link { font-size:12px; color:#696cff; cursor:pointer; text-decoration:none; }
  .select-all-link:hover { text-decoration:underline; }
</style>

<div class="d-flex align-items-center mb-4">
  <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle">
    <i class="bx bx-chevron-left"></i>
  </a>
  <h4 class="mb-0">
    <span class="text-muted fw-light">Access Control / Admins /</span>
    <span class="fw-bold text-primary">Edit Administrator</span>
  </h4>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card admin-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')

          <!-- Basic Info -->
          <div class="section-title"><i class="bx bx-user-circle fs-5"></i> Basic Information</div>
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">First Name</label>
              <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Last Name</label>
              <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Email Address</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required />
              </div>
              @error('email')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Change Password</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                <input type="password" class="form-control" name="password" placeholder="············" />
              </div>
              <small class="text-muted">Leave blank to keep current password.</small>
            </div>
          </div>

          <hr class="opacity-25">

          <!-- Roles -->
          <div class="section-title mt-4"><i class="bx bx-shield-quarter fs-5"></i> Assign Role</div>
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
              @foreach($roles as $role)
              @php $isRoleChecked = in_array($role->name, $userRoles); @endphp
              <label class="perm-action-pill {{ $isRoleChecked ? 'is-checked' : '' }}" onclick="togglePill(this)" style="font-size:13px; padding:7px 18px;">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $isRoleChecked ? 'checked' : '' }}>
                <i class="bx bx-shield-quarter"></i> {{ ucfirst($role->name) }}
              </label>
              @endforeach
            </div>
          </div>

          <hr class="opacity-25">

          <!-- Grouped Permissions -->
          <div class="d-flex align-items-center justify-content-between mt-4 mb-1">
            <div class="section-title mb-0"><i class="bx bx-key fs-5"></i> Extra Permissions by Module</div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-label-primary" onclick="toggleAllPerms(true)">
                <i class="bx bx-check-double me-1"></i>Select All
              </button>
              <button type="button" class="btn btn-sm btn-label-secondary" onclick="toggleAllPerms(false)">
                <i class="bx bx-x me-1"></i>Clear All
              </button>
            </div>
          </div>
          <p class="text-muted small mb-4 mt-2">Extra permissions assigned on top of the role. Each module is grouped for clarity.</p>

          @php
            $moduleIconsMap = [
              'vehicles'        => ['icon'=>'bx-car',            'color'=>'primary',   'label'=>'Vehicles'],
              'users'           => ['icon'=>'bx-user',           'color'=>'info',      'label'=>'Users'],
              'roles'           => ['icon'=>'bx-shield-quarter', 'color'=>'warning',   'label'=>'Roles'],
              'subscriptions'   => ['icon'=>'bx-credit-card',    'color'=>'success',   'label'=>'Subscriptions'],
              'orders'          => ['icon'=>'bx-shopping-bag',   'color'=>'danger',    'label'=>'Orders'],
              'partners'        => ['icon'=>'bx-group',          'color'=>'secondary', 'label'=>'Partners'],
              'inquiries'       => ['icon'=>'bx-message-dots',   'color'=>'info',      'label'=>'Inquiries'],
              'email_templates' => ['icon'=>'bx-envelope',       'color'=>'primary',   'label'=>'Email Templates'],
              'settings'        => ['icon'=>'bx-cog',            'color'=>'secondary', 'label'=>'Settings'],
              'car_settings'    => ['icon'=>'bx-wrench',         'color'=>'warning',    'label'=>'Car Settings'],
              'products'        => ['icon'=>'bx-cart',           'color'=>'success',   'label'=>'Products'],
              'customers'       => ['icon'=>'bx-user-circle',    'color'=>'info',      'label'=>'Customers'],
              'general'         => ['icon'=>'bx-list-check',     'color'=>'dark',      'label'=>'General'],
            ];
            $actionIconsMap = [
              'view'   => ['icon'=>'bx-show',        'label'=>'View'],
              'create' => ['icon'=>'bx-plus-circle', 'label'=>'Create'],
              'edit'   => ['icon'=>'bx-edit',        'label'=>'Edit'],
              'delete' => ['icon'=>'bx-trash',       'label'=>'Delete'],
              'access' => ['icon'=>'bx-key',         'label'=>'Access'],
            ];
          @endphp

          <div class="row g-3">
            @foreach($permissions as $module => $modulePerms)
            @php
              $meta      = $moduleIconsMap[$module] ?? ['icon'=>'bx-circle','color'=>'secondary','label'=>ucwords(str_replace('_',' ',$module))];
              $color     = $meta['color'];
              $icon      = $meta['icon'];
              $label     = $meta['label'];
              $moduleId  = 'mod_' . preg_replace('/[^a-z0-9]/','_', $module);
            @endphp
            <div class="col-md-6 col-lg-4">
              <div class="module-perm-card">
                <div class="module-perm-header">
                  <div class="module-name">
                    <i class="bx {{ $icon }} text-{{ $color }}"></i>
                    {{ $label }}
                    <span class="badge bg-label-{{ $color }} ms-1">{{ $modulePerms->count() }}</span>
                  </div>
                  <div>
                    <a class="select-all-link" onclick="toggleModule('{{ $moduleId }}', true)">All</a>
                    <span class="text-muted mx-1">/</span>
                    <a class="select-all-link" style="color:#aaa;" onclick="toggleModule('{{ $moduleId }}', false)">None</a>
                  </div>
                </div>
                <div class="module-perm-body" id="{{ $moduleId }}">
                  @foreach($modulePerms as $perm)
                  @php
                    $actionKey  = explode('-', $perm->name)[0] ?? 'view';
                    $actionMeta = $actionIconsMap[$actionKey] ?? ['icon'=>'bx-circle','label'=>ucfirst($actionKey)];
                    $isChecked  = in_array($perm->name, $userPermissions);
                  @endphp
                  <label class="perm-action-pill {{ $isChecked ? 'is-checked' : '' }}" onclick="togglePill(this)">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ $isChecked ? 'checked' : '' }}>
                    <i class="bx {{ $actionMeta['icon'] }}"></i> {{ $actionMeta['label'] }}
                  </label>
                  @endforeach
                </div>
              </div>
            </div>
            @endforeach
          </div>

          <div class="row mt-5">
            <div class="col-12 d-flex justify-content-end gap-3">
              <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
              <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-save me-1"></i> Update Administrator
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function togglePill(label) {
    const cb = label.querySelector('input[type="checkbox"]');
    cb.checked = !cb.checked;
    label.classList.toggle('is-checked', cb.checked);
  }
  function toggleModule(moduleId, checked) {
    document.querySelectorAll('#' + moduleId + ' .perm-action-pill').forEach(pill => {
      const cb = pill.querySelector('input');
      cb.checked = checked;
      pill.classList.toggle('is-checked', checked);
    });
  }
  function toggleAllPerms(checked) {
    document.querySelectorAll('.perm-action-pill').forEach(pill => {
      const cb = pill.querySelector('input');
      cb.checked = checked;
      pill.classList.toggle('is-checked', checked);
    });
  }
</script>
@endsection
