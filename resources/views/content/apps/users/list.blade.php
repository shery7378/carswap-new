@extends('layouts/contentNavbarLayout')

@section('title', 'Web Users')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">{{ __('Web Users List') }}</h5>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <!-- Status Filter -->
                        <form action="{{ route('admin.web-users.index') }}" method="GET">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>{{ __('Banned') }}</option>
                            </select>
                        </form>

                        <a href="{{ route('admin.web-users.create') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i>{{ __('Add Web User') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- ─── DESKTOP TABLE ─── -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover" id="users-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Avatar') }}</th>
                                    <th>{{ __('User Info') }}</th>
                                    <th class="d-none d-lg-table-cell">{{ __('Phone') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('Role') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Joined') }}</th>
                                    <th class="text-end pe-4">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td>
                                            <div class="avatar avatar-md border border-light shadow-sm bg-white rounded-circle">
                                                <img src="{{ $user->getAvatarUrl() }}" alt="avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $user->first_name }} {{ $user->last_name }}</span>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <span class="text-muted small">{{ $user->phone ?? 'N/A' }}</span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-label-secondary small text-capitalize">{{ $user->role ?? 'Web User' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $status = $user->status ?: 'active';
                                                $statusClass = match ($status) {
                                                    'active' => 'success',
                                                    'inactive' => 'warning',
                                                    'banned' => 'danger',
                                                    default => 'primary',
                                                };
                                            @endphp
                                            <div class="dropdown status-dropdown">
                                                <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button" data-bs-toggle="dropdown">
                                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    @foreach(['active' => 'success', 'inactive' => 'warning', 'banned' => 'danger'] as $val => $cls)
                                                        <li>
                                                            <button type="button" class="dropdown-item d-flex align-items-center py-2 change-status" data-id="{{ $user->id }}" data-status="{{ $val }}">
                                                                <span class="badge badge-dot bg-{{ $cls }} me-2"></span>
                                                                {{ ucfirst($val) }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end align-items-center gap-1 flex-nowrap">
                                                <button type="button" class="btn btn-icon btn-sm btn-label-secondary border-0 shadow-none show-details-btn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <a href="{{ route('admin.web-users.edit', $user->id) }}" class="btn btn-icon btn-sm btn-label-info border-0 shadow-none" data-bs-toggle="tooltip" title="Edit Profile">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-icon btn-sm btn-label-warning border-0 shadow-none change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}" data-bs-toggle="tooltip" title="Change Password">
                                                    <i class="bx bx-key"></i>
                                                </button>
                                                <form action="{{ route('admin.web-users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-confirmation" data-bs-toggle="tooltip" title="Delete User">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No web users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- ─── MOBILE CARD LIST ─── -->
                    <div class="d-md-none" id="users-mobile-list">
                        <div class="mb-3">
                            <input type="text" id="mobile-search" class="form-control form-control-sm" placeholder="Quick search users…">
                        </div>
                        @forelse($users as $user)
                            @php
                                $status = $user->status ?: 'active';
                                $statusClass = match ($status) {
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'banned' => 'danger',
                                    default => 'primary',
                                };
                            @endphp
                            <div class="user-mobile-card card mb-3 shadow-sm border-0" data-id="{{ $user->id }}" data-title="{{ strtolower($user->first_name . ' ' . $user->last_name . ' ' . $user->email) }}">
                                <div class="card-body p-3">
                                    <div class="d-flex gap-3 align-items-start">
                                        <div class="avatar avatar-md border border-light shadow-sm bg-white rounded-circle flex-shrink-0">
                                            <img src="{{ $user->getAvatarUrl() }}" alt="avatar" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong class="d-block text-truncate">{{ $user->first_name }} {{ $user->last_name }}</strong>
                                                    <small class="text-muted d-block text-truncate">{{ $user->email }}</small>
                                                </div>
                                                <div class="dropdown status-dropdown">
                                                    <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button" data-bs-toggle="dropdown">
                                                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($status) }}</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        @foreach(['active' => 'success', 'inactive' => 'warning', 'banned' => 'danger'] as $val => $cls)
                                                            <li>
                                                                <button type="button" class="dropdown-item d-flex align-items-center py-2 change-status" data-id="{{ $user->id }}" data-status="{{ $val }}">
                                                                    <span class="badge badge-dot bg-{{ $cls }} me-2"></span>{{ ucfirst($val) }}
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-icon btn-sm btn-label-secondary border-0 show-details-btn" data-id="{{ $user->id }}"><i class="bx bx-show"></i></button>
                                            <a href="{{ route('admin.web-users.edit', $user->id) }}" class="btn btn-icon btn-sm btn-label-info border-0"><i class="bx bx-edit-alt"></i></a>
                                            <button type="button" class="btn btn-icon btn-sm btn-label-warning border-0 change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}"><i class="bx bx-key"></i></button>
                                            <form action="{{ route('admin.web-users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 delete-confirmation"><i class="bx bx-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">No web users found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- USER DETAILS POPUP MODAL -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-0 border-0 shadow-lg rounded-4 overflow-hidden">
                <div id="modal-content-area">
                    <div class="text-center p-5 my-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted outfit-font">Loading User Profile...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHANGE PASSWORD MODAL -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom p-4">
                    <h5 class="modal-title fw-bold outfit-font"><i class="bx bx-key me-2 text-warning"></i> Change User Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="changePasswordForm">
                    @csrf
                    <input type="hidden" id="cp_user_id" name="user_id">
                    <div class="modal-body p-4">
                        <div class="mb-3 text-center bg-label-info p-3 rounded border border-light border-opacity-50">
                            <small class="text-dark d-block">Updating password for:</small>
                            <span class="fw-bold fs-5" id="cp_user_name">User Name</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" id="new_password" class="form-control" placeholder="Enter at least 8 characters" required>
                                <span class="input-group-text cursor-pointer" onclick="togglePassword('new_password')"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Confirm the password" required>
                                <span class="input-group-text cursor-pointer" onclick="togglePassword('new_password_confirmation')"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Save New Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bx-hide', 'bx-show');
    } else {
        input.type = "password";
        icon.classList.replace('bx-show', 'bx-hide');
    }
}

$(document).ready(function () {
    // ─── INITIALIZE DATATABLE ───
    if ($.fn.DataTable) {
        $('#users-table').DataTable({
            order: [[1, 'asc']],
            pageLength: 25,
            dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>t<'row mt-3'<'col-sm-6'i><'col-sm-6'p>>",
            language: { 
                search: '', 
                searchPlaceholder: 'Quick Search Users…',
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            }
        });
    }

    // ─── AJAX STATUS UPDATE ───
    $(document).on('click', '.change-status', function(e) {
        e.stopPropagation();
        const userId = $(this).data('id');
        const status = $(this).data('status');
        $.ajax({
            url: `{{ url('/app/users') }}/${userId}/status`,
            method: 'PATCH',
            data: { _token: '{{ csrf_token() }}', status: status },
            success: function(res) {
                if(res.success) {
                    toastr.success('User status updated successfully');
                    setTimeout(() => location.reload(), 500);
                }
            }
        });
    });

    // ─── CHANGE PASSWORD LOGIC ───
    $(document).on('click', '.change-password-btn', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#cp_user_id').val(id);
        $('#cp_user_name').text(name);
        $('#changePasswordForm')[0].reset();
        $('#changePasswordModal').modal('show');
    });

    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#cp_user_id').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: `{{ url('/app/users') }}/${id}/change-password`,
            method: 'POST',
            data: formData,
            success: function(res) {
                if(res.success) {
                    $('#changePasswordModal').modal('hide');
                    toastr.success(res.message);
                }
            },
            error: function(err) {
                const msg = err.responseJSON?.message || 'Failed to update password.';
                toastr.error(msg);
            }
        });
    });

    // ─── ROW CLICK & SHOW BUTTON -> OPEN POPUP MODAL ───
    $(document).on('click', '#users-table tbody tr, .user-mobile-card, .show-details-btn', function (e) {
        if ($(e.target).closest('form, a:not(.show-details-btn), button, .dropdown, .social-btn').length) return;
        
        e.preventDefault();
        const id = $(this).data('id');
        if(!id) return;

        $('#userDetailsModal').modal('show');
        $('#modal-content-area').html('<div class="text-center py-5 my-5"><div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem;"></div><p class="mt-4 text-muted outfit-font fs-5">Fetching profile data...</p></div>');
        
        $.ajax({
            url: `{{ url('/app/users') }}/${id}`,
            method: 'GET',
            success: function(html) {
                $('#modal-content-area').html(html);
            },
            error: function() {
                $('#modal-content-area').html('<div class="alert alert-danger m-5 d-flex align-items-center"><i class="bx bx-error-circle me-3 fs-3"></i> Failed to pull user details.</div>');
            }
        });
    });

    // ─── DELETE CONFIRMATION ───
    $(document).on('click', '.delete-confirmation', function(e) {
        e.stopPropagation();
        Swal.fire({
            title: 'Permanent Delete?',
            text: "This user and all their listings will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete!',
            customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary' },
            buttonsStyling: false
        }).then((result) => { if (result.value) $(this).closest('form').submit(); });
    });

    // ─── MOBILE SEARCH ───
    $('#mobile-search').on('input', function () {
        const q = $(this).val().toLowerCase();
        $('.user-mobile-card').each(function () {
            $(this).toggle($(this).data('title').includes(q));
        });
    });

    // Initialize Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
    .dataTables_filter input { border-radius: 12px; padding: 8px 12px; border: 1px solid #e2e8f0; width: 250px; background: #f8fafc; transition: all 0.2s; }
    .dataTables_filter input:focus { width: 300px; border-color: #696cff; outline: none; box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1); }
    
    #users-table tbody tr, .user-mobile-card { cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    #users-table tbody tr:hover { background-color: rgba(105, 108, 255, 0.05) !important; transform: scale(1.002); }
    .user-mobile-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
    
    .outfit-font { font-family: 'Outfit', sans-serif !important; }
    
    .bg-label-info { background-color: rgba(3, 195, 236, 0.1) !important; color: #03c3ec !important; }
</style>
@endsection