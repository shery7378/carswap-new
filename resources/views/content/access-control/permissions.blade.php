@extends('layouts/contentNavbarLayout')

@section('title', 'Permission Management')

@section('content')
<style>
  .permission-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .permission-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
  }
  .permission-icon {
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
    border-top: none;
  }
  .action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
  }
  .action-btn:hover {
    transform: scale(1.1);
  }
  .stat-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    overflow: hidden;
  }
  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
  }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">
    <span class="text-muted fw-light">Access Control /</span> 
    <span class="fw-bold text-primary">Permissions</span>
  </h4>
  <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
    <i class="bx bx-plus-circle me-1"></i> Add New Permission
  </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card stat-card">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-label-primary text-primary me-3">
          <i class="bx bx-key"></i>
        </div>
        <div>
          <h5 class="mb-0 fw-bold">{{ $permissions->count() }}</h5>
          <small class="text-muted">Total Permissions</small>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-label-danger text-danger me-3">
          <i class="bx bx-shield-x"></i>
        </div>
        <div>
          <h5 class="mb-0 fw-bold">{{ $permissions->where('guard_name', 'admin-guard')->count() }}</h5>
          <small class="text-muted">Admin Guard</small>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-label-info text-info me-3">
          <i class="bx bx-globe"></i>
        </div>
        <div>
          <h5 class="mb-0 fw-bold">{{ $permissions->where('guard_name', 'web')->count() }}</h5>
          <small class="text-muted">Web Guard</small>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card permission-card overflow-hidden">
  <div class="card-header border-bottom d-flex align-items-center justify-content-between bg-light-soft">
    <div>
        <h5 class="mb-1 text-dark">Permission Matrix</h5>
        <p class="text-muted mb-0 small">Manage and define system permissions across different security guards.</p>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Permission Name</th>
          <th>Guard Type</th>
          <th>Created At</th>
          <th style="width: 120px;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($permissions as $permission)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <div class="permission-icon">
                <i class="bx bx-lock-alt"></i>
              </div>
              <div>
                <div class="fw-bold text-dark">{{ $permission->name }}</div>
                <small class="text-muted">id: #{{ $permission->id }}</small>
              </div>
            </div>
          </td>
          <td>
            <span class="badge {{ $permission->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} fw-bold">
              {{ strtoupper($permission->guard_name) }}
            </span>
          </td>
          <td>
            <div class="text-muted small">
              <i class="bx bx-calendar me-1"></i>{{ $permission->created_at ? $permission->created_at->format('M d, Y') : 'N/A' }}
            </div>
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="action-btn text-info bg-label-info" title="Edit Permission">
                <i class="bx bx-edit-alt"></i>
              </a>
              <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn text-danger bg-label-danger border-0" title="Delete Permission">
                  <i class="bx bx-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
  // Add some micro-animations or interactivity if needed
  document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.1)';
    });
    btn.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });
</script>
@endsection
