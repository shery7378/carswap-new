@extends('layouts/contentNavbarLayout')

@section('title', __('View Role Details'))

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
    font-weight: 700;
    font-size: 14px;
    color: #2c2c44;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
  .perm-item .perm-check-icon {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
    margin-top: 1px;
    font-size: 14px;
    color: #696cff;
  }
  .perm-item label {
    font-size: 13px;
    color: #3c5a78;
    margin-bottom: 0;
    line-height: 1.4;
  }

  .role-stats-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 16px 20px;
    background: #f8f9fd;
    text-align: center;
    transition: all 0.2s ease;
  }
  .role-stats-card:hover {
    border-color: #696cff;
    box-shadow: 0 4px 12px rgba(105, 108, 255, 0.08);
  }
  .role-stats-number {
    font-size: 28px;
    font-weight: 800;
    color: #696cff;
  }
  .role-stats-label {
    font-size: 12px;
    color: #566a7f;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .perm-item { width: 50%; }
  }
</style>

<h4 class="fw-bold py-3 mb-4">
  <a href="{{ route('admin.roles.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle" style="z-index: 1050;">
    <i class="bx bx-chevron-left"></i>
  </a>
  <span class="text-muted fw-light">
    <a href="{{ route('admin.roles.index') }}" class="text-muted">{{ __('Access Control') }}</a> / 
    <a href="{{ route('admin.roles.index') }}" class="text-muted">{{ __('Roles') }}</a> /
  </span>
  {{ __('View Role') }}
</h4>

<div class="row">
  <div class="col-xl-12">
    <div class="card role-form-card mb-4">
      <div class="card-body p-4">

        {{-- ── Role Details ── --}}
        <div class="section-label mb-3">
          <i class="bx bx-cog"></i> {{ __('Role Details') }}
        </div>

        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">{{ __('Role Name') }}</label>
            <div class="d-flex align-items-center mt-1">
              <div class="me-2" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background: rgba(105, 108, 255, 0.1); color: #696cff; font-size: 1.25rem;">
                <i class="bx bx-shield-quarter"></i>
              </div>
              <span class="fw-bold text-dark fs-5">{{ __($role->name) }}</span>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">{{ __('Security Guard') }}</label>
            <div class="mt-1">
              <span class="badge {{ $role->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} py-2 px-3 fw-bold">
                <i class="bx bx-lock-alt me-1"></i> {{ strtoupper($role->guard_name) }}
              </span>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="role-stats-card">
              <div class="role-stats-number">{{ $role->permissions->count() }}</div>
              <div class="role-stats-label">{{ __('Total Permissions') }}</div>
            </div>
          </div>
        </div>

        <hr class="my-3 opacity-25">

        {{-- ── Permissions Breakdown ── --}}
        <div class="d-flex justify-content-between align-items-center mb-1 mt-4">
          <div>
            <div class="section-label mb-0">
              <i class="bx bx-key"></i> {{ __('Assigned Permissions') }}
            </div>
            <p class="text-muted small mb-0 mt-1">{{ __('Detailed breakdown of all permissions assigned to this role') }}</p>
          </div>
        </div>

        <div class="mt-4">
          @if($permissions->isEmpty())
            <div class="text-center py-5">
              <i class="bx bx-info-circle text-muted display-4"></i>
              <h5 class="text-muted mt-3">{{ __('No permissions assigned to this role yet.') }}</h5>
              @if($role->name !== 'super-admin')
                @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-roles', 'admin-guard'))
                  <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary mt-3">
                    <i class="bx bx-edit-alt me-1"></i> {{ __('Assign Permissions') }}
                  </a>
                @endif
              @endif
            </div>
          @else
            @foreach($permissions as $module => $modulePermissions)
              @php
                $chunks = $modulePermissions->chunk(4);
                $huModules = [
                  'dashboard' => 'Irányítópult',
                  'frontend-pages' => 'Frontend oldalak',
                  'vehicles' => 'Járművek',
                  'users' => 'Felhasználók',
                  'roles' => 'Szerepkörök',
                  'subscriptions' => 'Előfizetések',
                  'orders' => 'Rendelések',
                  'partners' => 'Partnerek',
                  'inquiries' => 'Megkeresések',
                  'email_templates' => 'E-mail sablonok',
                  'settings' => 'Beállítások',
                  'car_settings' => 'Jármű beállítások',
                  'products' => 'Termékek',
                  'customers' => 'Ügyfelek',
                  'cms' => 'CMS',
                  'trade_offers' => 'Csere ajánlatok',
                  'newsletter' => 'Hírlevél',
                  'contacts' => 'Kapcsolatok',
                ];
                $moduleLabel = $huModules[$module] ?? ucwords(str_replace('-', ' ', $module));
              @endphp
              <div class="perm-module-block">
                <div class="perm-section-header">
                  {{ $moduleLabel }}
                  <span class="badge bg-label-primary rounded-pill">{{ $modulePermissions->count() }}</span>
                </div>
                <div class="perm-rows-wrap">
                  @foreach($chunks as $chunk)
                    <div class="perm-row">
                      @foreach($chunk as $permission)
                        @php
                          $parts = explode('-', $permission->name);
                          $action = ucfirst($parts[0] ?? '');
                          $huActions = [
                            'View' => 'megtekintése',
                            'Create' => 'létrehozása',
                            'Edit' => 'szerkesztése',
                            'Delete' => 'törlése',
                            'Access' => 'hozzáférés',
                            'Manage' => 'kezelése',
                          ];
                          $label = ($huModules[$module] ?? ucwords(str_replace('-', ' ', $module))) . ' ' . ($huActions[$action] ?? strtolower($action));
                        @endphp
                        <div class="perm-item">
                          <i class="bx bx-check-circle perm-check-icon"></i>
                          <label>{{ $label }}</label>
                        </div>
                      @endforeach
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          @endif
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-4">{{ __('Back to List') }}</a>
          @if($role->name !== 'super-admin')
            @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-roles', 'admin-guard'))
              <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-edit-alt me-1"></i> {{ __('Edit Role') }}
              </a>
            @endif
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
