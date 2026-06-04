@extends('layouts/contentNavbarLayout')

@section('title', __('Admin Management'))

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

  .dataTables_length label {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
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
    margin-top: 0.25rem;
  }

  .dataTables_info {
    white-space: normal !important;
  }

  @media (min-width: 768px) {
    .dataTables_paginate {
      margin-top: 0;
    }
  }

  /* TABLE WRAPPER FIX */
  .table-responsive {
    overflow-x: auto !important;
  }
  
  #admins-table th, #admins-table td {
    padding: .5rem .5rem !important;
    font-size: .85rem;
    vertical-align: middle;
  }
  
  #admins-table th {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    position: relative;
    padding-right: 30px !important;
    white-space: normal;
    line-height: 1.2;
  }

  /* MOBILE FIX */
  @media (max-width: 768px) {
    .dataTables_wrapper .row {
      flex-direction: column;
      align-items: stretch;
    }

    .admin-card .dataTables_length {
      justify-content: flex-start;
      margin-left: 0.75rem;
      margin-bottom: 0.5rem;
      width: calc(100% - 1.5rem);
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

  /* Premium Action Buttons */
  .btn-action {
      width: 32px !important;
      height: 32px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      border-radius: 8px !important;
      padding: 0 !important;
      border: none !important;
      transition: all 0.2s ease !important;
      text-decoration: none !important;
  }
  .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }
  .btn-action.edit { background-color: #e0f7fa !important; color: #00bcd4 !important; }
  .btn-action.delete { background-color: #ffebee !important; color: #f44336 !important; }
  .btn-action.view { background-color: #f3e5f5 !important; color: #9c27b0 !important; }
  .btn-action i { font-size: 1.15rem !important; }
  
  .action-container {
      display: flex;
      gap: 8px;
      justify-content: flex-start;
  }
</style>

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
  <h4 class="mb-0">
    <span class="text-muted">{{ __('Access Control') }} /</span>
    <span class="fw-bold text-primary">{{ __('Admin Users') }}</span>
  </h4>

  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    <i class="bx bx-plus"></i> {{ __('Add Admin User') }}
  </a>
</div>

<div class="card admin-card">
  <div class="card-header">
    <h5 class="mb-0">{{ __('Admin Users List') }}</h5>
  </div>

  <div class="card-body">
    <!-- TABLE VIEW -->
    <div class="table-responsive">
      <table class="table table-hover" id="admins-table">

        <thead>
          <tr>
            <th>{{ __('Admin User') }}</th>
            <th>{{ __('Roles') }}</th>
            <th>{{ __('Permissions') }}</th>
            <th>{{ __('Actions') }}</th>
          </tr>
        </thead>

        <tbody>
          @foreach($users as $user)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <div class="avatar">
                    <img src="{{ $user->getAvatarUrl() }}" alt="avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                  </div>
                </div>
                <div>
                  <div class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                  <small class="text-muted">{{ $user->email }}</small>
                </div>
              </div>
            </td>

            <td>
              @foreach($user->roles as $role)
              <span class="badge bg-label-primary badge-premium">{{ __($role->name) }}</span>
              @endforeach
            </td>

            <td>
              @forelse($user->permissions as $permission)
              <span class="badge bg-label-warning badge-premium">{{ __($permission->name) }}</span>
              @empty
              <span class="text-muted small italic">{{ __('No Direct Permissions') }}</span>
              @endforelse
            </td>

            <td>
              <div class="action-container">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action edit" data-bs-toggle="tooltip" title="{{ __('Edit Admin User') }}">
                  <i class="bx bx-edit"></i>
                </a>

                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn-action delete delete-confirmation"
                    data-confirm-text="{{ __('Delete this admin user permanently?') }}" data-bs-toggle="tooltip" title="{{ __('Delete Admin User') }}">
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
  $(document).ready(function() {

    $('#admins-table').DataTable({
      order: [
        [0, "asc"]
      ],
      pageLength: 10,
      autoWidth: false,

      // CLEAN STRUCTURE
      dom: "<'row align-items-center mb-3'<'col-md-auto mb-2 mb-md-0'l><'col-md d-flex justify-content-md-end'f>>" +
        "t" +
        "<'row mt-3 d-flex align-items-center justify-content-between flex-wrap'<'col-12 col-md-auto mb-2 mb-md-0 text-center text-md-start'i><'col-12 col-md-auto d-flex justify-content-center justify-content-md-end'p>>",

      language: {
        search: "",
        searchPlaceholder: "{{ __('Quick Search Admins…') }}"
      }
    });

  });
</script>
@endsection
