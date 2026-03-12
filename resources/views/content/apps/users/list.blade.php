@extends('layouts/contentNavbarLayout')

@section('title', 'Users List')

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <!-- Users Management Card -->
      <div class="col-lg-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5 class="card-title">Total Users</h5>
                    <h3>{{ $users->total() }}</h3>
                  </div>
                  <div class="avatar avatar-xl">
                    <i class="bx bx-user-plus bx-tada"></i>
                  </div>
                </div>
                <small>{{ $users->count() }} users in current page</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5 class="card-title">Active</h5>
                    <h3>{{ $users->where('status', 'active')->count() }}</h3>
                  </div>
                  <div class="avatar avatar-xl">
                    <i class="bx bx-check-circle bx-tada"></i>
                  </div>
                </div>
                <small>{{ $users->where('status', 'active')->count() }} active users</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5 class="card-title">New This Month</h5>
                    <h3>{{ $users->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count() }}</h3>
                  </div>
                  <div class="avatar avatar-xl">
                    <i class="bx bx-user-plus bx-tada"></i>
                  </div>
                </div>
                <small>Recent signups</small>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5 class="card-title">Inactive</h5>
                    <h3>{{ $users->where('status', 'inactive')->count() }}</h3>
                  </div>
                  <div class="avatar avatar-xl">
                    <i class="bx bx-user-x bx-tada"></i>
                  </div>
                </div>
                <small>{{ $users->where('status', 'inactive')->count() }} inactive users</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Users Table -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Users Management</h4>
            <div class="d-flex gap-2">
              <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search users...">
                <button class="btn btn-outline-primary" type="button">
                  <i class="bx bx-search"></i>
                </button>
              </div>
              <button class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add User
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- Filter Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab">
                  All ({{ $users->total() }})
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#active" role="tab">
                  Active ({{ $users->where('status', 'active')->count() }})
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#inactive" role="tab">
                  Inactive ({{ $users->where('status', 'inactive')->count() }})
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#banned" role="tab">
                  Banned ({{ $users->where('status', 'banned')->count() }})
                </a>
              </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
              <div class="tab-pane active" id="all" role="tabpanel">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                          </div>
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $user)
                      <tr>
                        <td>
                          <input class="form-check-input" type="checkbox" value="{{ $user->id }}">
                        </td>

                        <td>
                          <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                              <img src="{{ $user->profile_picture  ? asset('storage/' . $user->profile_picture) 
            : 'https://ui-avatars.com/api/?name='.$user->name 
        }}" alt="{{ $user->name }}"
                                class="rounded-circle" width="40">
                            </div>
                            <div>
                              <div class="fw-medium">{{ $user->name }}</div>
                              <small class="text-muted">ID: #USR{{ str_pad($user->id,3,'0',STR_PAD_LEFT) }}</small>
                            </div>
                          </div>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>
                          <span class="badge bg-label-primary">
                            {{ $user->role ?? 'User' }}
                          </span>
                        </td>

                        <td>
                          @if($user->status == 'active')
                          <span class="badge bg-success">Active</span>
                          @elseif($user->status == 'inactive')
                          <span class="badge bg-warning">Inactive</span>
                          @else
                          <span class="badge bg-danger">Banned</span>
                          @endif
                        </td>

                        <td>{{ $user->created_at->format('Y-m-d') }}</td>

                        <td>
                          {{ $user->last_seen ? \Carbon\Carbon::parse($user->last_seen)->diffForHumans() : '—' }}
                        </td>

                        <td>
                          <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                              data-bs-toggle="dropdown">
                              <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>

                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="{{ route('admin.users.view', $user->id) }}">View</a></li>
                              <li><a class="dropdown-item" href="">Edit</a></li>
                              <li><a class="dropdown-item" href="">Reset Password</a></li>
                              <li>
                                <hr class="dropdown-divider">
                              </li>

                              @if($user->status=='active')
                              <li><a class="dropdown-item text-warning"
                                  href="{{ route('admin.users.suspend',$user->id) }}">Suspend</a></li>
                              @elseif($user->status=='inactive')
                              <li><a class="dropdown-item text-success"
                                  href="{{ route('admin.users.activate',$user->id) }}">Activate</a></li>
                              @elseif($user->status=='banned')
                              <li><a class="dropdown-item text-success"
                                  href="{{ route('admin.users.unban',$user->id) }}">Unban</a></li>
                              @endif

                              <li><a class="dropdown-item text-danger"
                                  href="">Ban</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>

                  </table>
                </div>

                <!-- Pagination -->
                <nav class="mt-4">
                  <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                      <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#">Next</a>
                    </li>
                  </ul>
                </nav>
              </div>

              <!-- Other tab contents would go here -->
              <div class="tab-pane" id="active" role="tabpanel">
                <p class="text-center py-4">Active users content...</p>
              </div>
              <div class="tab-pane" id="inactive" role="tabpanel">
                <p class="text-center py-4">Inactive users content...</p>
              </div>
              <div class="tab-pane" id="banned" role="tabpanel">
                <p class="text-center py-4">Banned users content...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection