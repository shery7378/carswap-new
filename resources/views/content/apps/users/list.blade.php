@extends('layouts/contentNavbarLayout')

@section('title', 'Users List')

@section('content')
  <style>
    /* DataTable overrides to look premium */
    .dataTables_filter {
      display: none;
      /* We use our own search bar */
    }

    .dataTables_paginate .pagination {
      justify-content: flex-end !important;
    }

    .dataTables_length {
      margin-bottom: 1rem;
    }

    .dataTables_length select {
      border-radius: 0.5rem;
      padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
      border: 1px solid #d9dee3;
      margin: 0 5px;
      min-width: 80px !important;
    }

    .nav-tabs .nav-link.active {
      border-bottom: 2px solid #696cff;
      color: #696cff !important;
    }
  </style>

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
                      <h5 class="card-title text-white">Total Users</h5>
                      <h3 class="text-white">{{ $users->total() }}</h3>
                    </div>
                    <div class="avatar avatar-xl">
                      <i class="bx bx-user-plus bx-tada"></i>
                    </div>
                  </div>
                  <small>{{ $users->count() }} users loaded</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-success text-white">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5 class="card-title text-white">Active</h5>
                      <h3 class="text-white">{{ $users->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="avatar avatar-xl">
                      <i class="bx bx-check-circle bx-tada"></i>
                    </div>
                  </div>
                  <small>Active users</small>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-warning text-white">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5 class="card-title text-white">New Month</h5>
                      <h3 class="text-white">
                        {{ $users->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count() }}
                      </h3>
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
                      <h5 class="card-title text-white">Inactive</h5>
                      <h3 class="text-white">{{ $users->where('status', 'inactive')->count() }}</h3>
                    </div>
                    <div class="avatar avatar-xl">
                      <i class="bx bx-user-x bx-tada"></i>
                    </div>
                  </div>
                  <small>Inactive users</small>
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
                  <input type="text" id="user-search" class="form-control" placeholder="Search users...">
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
              <ul class="nav nav-tabs mb-4" role="tablist" id="user-status-tabs">
                <li class="nav-item">
                  <a class="nav-link active" data-status="" data-bs-toggle="tab" href="javascript:void(0);">
                    All ({{ $users->total() }})
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-status="Active" data-bs-toggle="tab" href="javascript:void(0);">
                    Active ({{ $users->where('status', 'active')->count() }})
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-status="Inactive" data-bs-toggle="tab" href="javascript:void(0);">
                    Inactive ({{ $users->where('status', 'inactive')->count() }})
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-status="Banned" data-bs-toggle="tab" href="javascript:void(0);">
                    Banned ({{ $users->where('status', 'banned')->count() }})
                  </a>
                </li>
              </ul>

              <div class="table-responsive">
                <table class="table table-hover" id="users-table">
                  <thead>
                    <tr>
                      <th>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="checkAll">
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
                                            <a href="{{ route('admin.users.view', $user->id) }}">
                                              <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture)
                      : 'https://ui-avatars.com/api/?name=' . $user->name 
                        }}" alt="{{ $user->name }}" class="rounded-circle" width="40">
                                            </a>
                                          </div>
                                          <div>
                                            <a href="{{ route('admin.users.view', $user->id) }}" class="text-body fw-medium">
                                              {{ $user->name }}
                                            </a><br>
                                            <small class="text-muted">ID: #USR{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</small>
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
                                          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                          </button>

                                          <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.users.view', $user->id) }}">View</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Reset Password</a></li>
                                            <li>
                                              <hr class="dropdown-divider">
                                            </li>

                                            @if($user->status == 'active')
                                              <li><a class="dropdown-item text-warning" href="javascript:void(0);">Suspend</a></li>
                                            @elseif($user->status == 'inactive')
                                              <li><a class="dropdown-item text-success" href="javascript:void(0);">Activate</a></li>
                                            @elseif($user->status == 'banned')
                                              <li><a class="dropdown-item text-success" href="javascript:void(0);">Unban</a></li>
                                            @endif

                                            <li><a class="dropdown-item text-danger" href="javascript:void(0);">Ban</a></li>
                                          </ul>
                                        </div>
                                      </td>
                                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- User Details Modal -->
  <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3" id="u-modal-loader-content">
        <div class="modal-body text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-3 text-muted fw-bold">Connecting to user secure data...</p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    $(document).ready(function () {
      var table = $('#users-table').DataTable({
        "order": [[5, "desc"]], // Sort by Joined date desc
        "pageLength": 10,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        "language": {
          "search": "",
          "searchPlaceholder": "Search users...",
          "paginate": {
            "next": '<i class="bx bx-chevron-right"></i>',
            "previous": '<i class="bx bx-chevron-left"></i>'
          }
        }
      });

      // Custom search binding
      $('#user-search').on('keyup', function () {
        table.search(this.value).draw();
      });

      // Custom status filter tabs
      $('#user-status-tabs a').on('click', function () {
        var status = $(this).data('status');
        table.column(4).search(status).draw();
      });

      // Check all functionality
      $('#checkAll').on('change', function () {
        $('tbody input[type="checkbox"]').prop('checked', $(this).prop('checked'));
      });

      // ✅ MODAL TRIGGER ON ROW CLICK
      $(document).on('click', '#users-table tbody tr', function (e) {
        // IGNORE CHECKBOXES, DROPDOWNS OR LINKS
        if ($(e.target).closest('.form-check, .dropdown, a, button').length) return;

        const userId = $(this).find('input[type="checkbox"]').val();
        if (!userId) return;

        const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        const container = document.getElementById('u-modal-loader-content');

        container.innerHTML = `
              <div class="modal-body text-center py-5">
                  <div class="spinner-grow text-primary" role="status"></div>
                  <p class="mt-3 text-muted small">Loading user profile...</p>
              </div>
          `;

        modal.show();

        // FETCH AJAX
        fetch(`{{ url('/app/apps/user/view') }}/${userId}`)
          .then(res => res.text())
          .then(html => {
            container.innerHTML = html;
          });
      });
    });
  </script>

  <style>
    #users-table tbody tr {
      cursor: pointer;
      transition: all 0.2s ease;
    }

    #users-table tbody tr:hover {
      background-color: rgba(105, 108, 255, 0.05) !important;
    }

    .shadow-xs {
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }
  </style>
@endsection