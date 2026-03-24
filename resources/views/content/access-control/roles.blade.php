@extends('layouts/contentNavbarLayout')

@section('title', 'Role Management')

@section('content')
<style>
  .role-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
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
    <span class="text-muted fw-light">Access Control /</span> 
    <span class="fw-bold text-primary">Roles</span>
  </h4>
  @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('create-roles', 'admin-guard'))
  <a href="{{ route('admin.roles.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
    <i class="bx bx-plus-circle me-1"></i> Create New Role
  </a>
  @endif
</div>

<div class="card role-card overflow-hidden">
  <div class="card-header border-bottom d-flex align-items-center bg-light-soft">
    <div class="card-title mb-0">
        <h5 class="mb-1 text-dark">Administrative Roles</h5>
        <p class="text-muted mb-0 small">Define access levels for different guards and administrators.</p>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Role Name</th>
          <th>Security Guard</th>
          <th>Permissions Bound</th>
          <th style="width: 120px;">Actions</th>
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
              <div class="fw-bold text-dark">{{ $role->name }}</div>
            </div>
          </td>
          <td>
            <span class="badge {{ $role->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} fw-bold">
              {{ strtoupper($role->guard_name) }}
            </span>
          </td>
          <td>
            <div class="permission-scroll d-flex flex-wrap gap-1">
              @forelse($role->permissions as $permission)
              <span class="badge bg-label-secondary badge-premium py-1 px-2" style="font-size: 10px;">{{ $permission->name }}</span>
              @empty
              <span class="text-muted small italic">No permissions assigned</span>
              @endforelse
            </div>
          </td>
          <td>
            @if($role->name === 'super-admin')
               <div class="d-flex align-items-center">
                 <span class="badge bg-label-secondary"><i class="bx bx-lock-alt me-1"></i> Protected</span>
               </div>
            @else
              <div class="d-flex gap-2">
                @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-roles', 'admin-guard'))
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="action-btn text-info bg-label-info" title="Edit Role">
                  <i class="bx bx-edit-alt"></i>
                </a>
                @endif
                @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-roles', 'admin-guard'))
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn text-danger bg-label-danger border-0" title="Delete Role">
                    <i class="bx bx-trash"></i>
                  </button>
                </form>
                @endif
              </div>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
