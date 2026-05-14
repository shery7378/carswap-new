@extends('layouts/contentNavbarLayout')

@section('title', __('Role Management'))

@section('content')
  <style>
    .role-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .role-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    .role-icon {
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      background: rgba(105, 108, 255, 0.1);
      color: #696cff;
      font-size: 1.25rem;
      margin-right: 12px;
    }

    .badge-premium {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table thead th {
      background: #f8f9fa;
      color: #566a7f;
      font-weight: 600;
      text-transform: none;
      font-size: 13px;
    }

    .action-btn {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      transition: all 0.2s;
    }

    .action-btn:hover {
      transform: scale(1.1);
    }

    .permission-scroll {
      max-width: 500px;
      white-space: normal;
    }
  </style>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
      <span class="text-muted fw-light">{{ __('Access Control') }} /</span>
      <span class="fw-bold text-primary">{{ __('Roles') }}</span>
    </h4>
    @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('create-roles', 'admin-guard'))
      <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
        <i class="bx bx-plus-circle me-1"></i> {{ __('Create New Role') }}
      </a>
    @endif
  </div>

  <div class="card role-card overflow-hidden">
    <div class="card-header border-bottom d-flex align-items-center bg-light-soft">
      <div class="card-title mb-0">
        <h5 class="mb-1 text-dark">{{ __('Administrative Roles') }}</h5>
        <p class="text-muted mb-0 small">{{ __('Define access levels for different guards and administrators.') }}</p>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>{{ __('Role Name') }}</th>
            <th>{{ __('Security Guard') }}</th>
            <th class="text-center">{{ __('Permissions Count') }}</th>
            <th style="width: 150px;">{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($roles as $role)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="role-icon">
                    <i class="bx bx-shield-quarter"></i>
                  </div>
                  <div class="fw-bold text-dark">{{ __($role->name) }}</div>
                </div>
              </td>
              <td>
                <span class="badge {{ $role->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} fw-bold">
                  {{ strtoupper($role->guard_name) }}
                </span>
              </td>
              <td class="text-center">
                <span class="badge rounded-pill bg-label-primary px-3 py-2">
                  <i class="bx bx-key me-1 small"></i> {{ $role->permissions->count() }}
                </span>
              </td>
              <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.show', $role->id) }}" class="action-btn text-primary bg-label-primary shadow-none"
                      title="{{ __('View Permissions') }}">
                      <i class="bx bx-show"></i>
                    </a>
                    @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-roles', 'admin-guard'))
                      <a href="{{ route('admin.roles.edit', $role->id) }}" class="action-btn text-info bg-label-info shadow-none"
                        title="{{ __('Edit Role') }}">
                        <i class="bx bx-edit-alt"></i>
                      </a>
                    @endif
                    @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-roles', 'admin-guard'))
                      <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="action-btn text-danger bg-label-danger border-0 delete-confirmation shadow-none" 
                                data-confirm-text="{{ __('Are you sure you want to delete this role?') }}" title="{{ __('Delete Role') }}">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
                    @endif
                  </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection