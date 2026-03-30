@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Management')

@section('content')

  <style>
    /* ============================= */
    /* CARD FIX (IMPORTANT) */
    /* ============================= */
    .card,
    .card-body {
      overflow: visible !important;
    }

    /* ============================= */
    /* DATATABLE FIX */
    /* ============================= */
    .dataTables_wrapper {
      width: 100% !important;
      overflow: visible !important;
    }

    /* FIX ROW SPACING */
    .dataTables_wrapper .row {
      margin-left: 0 !important;
      margin-right: 0 !important;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* LEFT SIDE */
    .dataTables_length {
      display: flex;
      align-items: center;
      white-space: nowrap;
    }

    /* RIGHT SIDE */
    .dataTables_filter {
      display: flex;
      justify-content: flex-end;
    }

    /* SEARCH BOX */
    .dataTables_filter input {
      width: 220px !important;
      border-radius: 6px;
      padding: 6px 10px;
      border: 1px solid #d9dee3;
    }

    /* DROPDOWN */
    .dataTables_length select {
      border-radius: 6px;
      padding: 4px 1.5rem 4px 8px !important;
      border: 1px solid #d9dee3;
      min-width: 80px !important;
      background-position: right 8px center !important;
    }

    /* PAGINATION */
    .dataTables_paginate {
      display: flex;
      justify-content: flex-end;
    }

    /* TABLE WRAPPER FIX */
    .table-responsive {
      overflow-x: auto !important;
    }

    /* MOBILE FIX */
    @media (max-width: 768px) {
      .dataTables_wrapper .row {
        flex-direction: column;
        align-items: stretch;
      }

      .dataTables_filter {
        justify-content: flex-start !important;
        margin-top: 10px;
      }
    }

    /* ============================= */
    /* YOUR UI DESIGN (UNCHANGED) */
    /* ============================= */
    .admin-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
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
      margin-right: 12px;
    }

    .badge-premium {
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 11px;
    }

    .action-btn {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
    }
  </style>

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h4 class="mb-0">
      <span class="text-muted">Access Control /</span>
      <span class="fw-bold text-primary">Admin Users</span>
    </h4>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
      <i class="bx bx-plus"></i> Add Admin User
    </a>
  </div>

  <div class="card admin-card">
    <div class="card-header">
      <h5 class="mb-0">Admin Users List</h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="admins-table">

          <thead>
            <tr>
              <th>Admin User</th>
              <th>Roles</th>
              <th>Permissions</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            @foreach($users as $user)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="me-3">
                      @if($user->profile_picture)
                        <div class="avatar">
                          <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        </div>
                      @else
                        <div class="avatar-text">
                          {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                      @endif
                    </div>
                    <div>
                      <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                      <small class="text-muted">{{ $user->email }}</small>
                    </div>
                  </div>
                </td>

                <td>
                  @foreach($user->roles as $role)
                    <span class="badge bg-label-primary badge-premium">{{ $role->name }}</span>
                  @endforeach
                </td>

                <td>
                  @forelse($user->permissions as $permission)
                    <span class="badge bg-label-warning badge-premium">{{ $permission->name }}</span>
                  @empty
                    <span class="text-muted small">From role</span>
                  @endforelse
                </td>

                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn bg-label-info text-info" data-bs-toggle="tooltip" title="Edit Admin User">
                      <i class="bx bx-edit"></i>
                    </a>

                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="action-btn bg-label-danger text-danger border-0 delete-confirmation"
                        data-confirm-text="Delete this admin user permanently?" data-bs-toggle="tooltip" title="Delete Admin User">
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
  </div>

@endsection

@section('page-script')
  <script>
    $(document).ready(function () {

      $('#admins-table').DataTable({
        order: [[0, "asc"]],
        pageLength: 10,
        autoWidth: false,

        // CLEAN STRUCTURE
        dom:
          "<'row'<'col-md-6'l><'col-md-6'f>>" +
          "t" +
          "<'row'<'col-md-6'i><'col-md-6'p>>",

        language: {
          search: "",
          searchPlaceholder: "Search Admin Users..."
        }
      });

    });
  </script>
@endsection