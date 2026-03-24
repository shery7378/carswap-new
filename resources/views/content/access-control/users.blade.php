@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Management')

@section('content')
<style>
  .admin-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .admin-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
  }
  .avatar-text {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: linear-gradient(135deg, #696cff 0%, #3a3dfb 100%);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    margin-right: 12px;
    box-shadow: 0 4px 10px rgba(105, 108, 255, 0.2);
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
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">
    <span class="text-muted fw-light">Access Control /</span> 
    <span class="fw-bold text-primary">Administrators</span>
  </h4>
  @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('create-users', 'admin-guard'))
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
    <i class="bx bx-plus-circle me-1"></i> Add New Admin
  </a>
  @endif
</div>

<div class="card admin-card overflow-hidden">
  <div class="card-header border-bottom d-flex align-items-center bg-light-soft">
    <div class="card-title mb-0">
      <h5 class="mb-1 text-dark">Active Administrators</h5>
      <p class="text-muted mb-0 small">Manage roles and permissions for backend users.</p>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th style="width: 300px;">Administrator</th>
          <th>Roles</th>
          <th>Extra Permissions</th>
          <th style="width: 100px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar-text">
                {{ strtoupper(substr($user->first_name, 0, 1)) . strtoupper(substr($user->last_name, 0, 1)) }}
              </div>
              <div>
                <div class="fw-bold mb-0 text-dark">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="text-muted small">{{ $user->email }}</div>
              </div>
            </div>
          </td>
          <td>
            @foreach($user->roles as $role)
            <span class="badge bg-label-primary badge-premium mb-1 me-1">
              <i class="bx bxs-shield-alt me-1"></i> {{ $role->name }}
            </span>
            @endforeach
          </td>
          <td>
            @forelse($user->permissions as $permission)
            <span class="badge bg-label-warning badge-premium mb-1 me-1">
               {{ $permission->name }}
            </span>
            @empty
            <span class="text-muted small italic">Default from role</span>
            @endforelse
          </td>
          <td>
            @if($user->hasRole('super-admin', 'admin-guard'))
               <div class="d-flex align-items-center">
                 <span class="badge bg-label-secondary"><i class="bx bx-lock-alt me-1"></i> Protected</span>
               </div>
            @else
              <div class="d-flex gap-2">
                @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('edit-users', 'admin-guard'))
                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn text-info bg-label-info" data-bs-toggle="tooltip" title="Edit Admin">
                  <i class="bx bx-edit-alt"></i>
                </a>
                @endif
                @if(auth('admin-guard')->user()->hasRole('super-admin', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-users', 'admin-guard'))
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Strict warning: This will permanently remove access for this administrator. Proceed?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn text-danger bg-label-danger border-0" data-bs-toggle="tooltip" title="Delete Admin">
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
  <div class="card-footer border-top bg-light-soft py-3">
    <div class="d-flex justify-content-between align-items-center">
      <div class="text-muted small">Showing records for active staff members.</div>
      <div>{{ $users->links() }}</div>
    </div>
  </div>
</div>
@endsection
