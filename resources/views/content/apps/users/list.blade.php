@extends('layouts/contentNavbarLayout')

@section('title', __('Web Users'))

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
	                                    <th>{{ __('Phone') }}</th>
	                                    <th>{{ __('Role') }}</th>
                                    <th class="text-center">{{ __('Trader') }}</th>
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
	                                        <td>
	                                            <span class="text-muted small">{{ $user->phone ?? 'N/A' }}</span>
	                                        </td>
	                                        <td>
	                                            <span class="badge bg-label-secondary small text-capitalize">{{ __($user->role ?? 'Web User') }}</span>
	                                        </td>
                                        <td class="text-center">
                                            @if($user->is_trader)
                                                <span class="badge bg-label-info pb-1"><i class="bx bx-check me-1 small"></i>{{ __('Yes') }}</span>
                                            @else
                                                <span class="badge bg-label-secondary pb-1">{{ __('No') }}</span>
                                            @endif
                                        </td>
	                                        <td class="text-center td-status">
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
	                                                    <span class="badge bg-{{ $statusClass }}">{{ __(ucfirst($status)) }}</span>
	                                                </button>
	                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
	                                                    @foreach(['active' => 'success', 'inactive' => 'warning', 'banned' => 'danger'] as $val => $cls)
	                                                        <li>
	                                                            <button type="button" class="dropdown-item d-flex align-items-center py-2 change-status" data-id="{{ $user->id }}" data-status="{{ $val }}">
	                                                                <span class="badge badge-dot bg-{{ $cls }} me-2"></span>
	                                                                {{ __(ucfirst($val)) }}
	                                                            </button>
	                                                        </li>
	                                                    @endforeach
	                                                </ul>
	                                            </div>
	                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $user->created_at->formatDate() }}
                                        </td>
	                                        <td class="text-end pe-4">
	                                            <div class="dropdown users-actions">
	                                                <button type="button" class="btn btn-icon btn-sm btn-label-secondary border-0 shadow-none dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
	                                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
	                                                </button>
		                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
		                                                    <li>
		                                                        <button type="button" class="dropdown-item d-flex align-items-center action-view show-details-btn" data-id="{{ $user->id }}">
		                                                            <i class="bx bx-show me-2"></i>{{ __('View Details') }}
		                                                        </button>
		                                                    </li>
		                                                    <li>
		                                                        <a href="{{ route('admin.web-users.edit', $user->id) }}" class="dropdown-item d-flex align-items-center action-edit">
		                                                            <i class="bx bx-edit-alt me-2"></i>{{ __('Edit Profile') }}
		                                                        </a>
		                                                    </li>
		                                                    <li>
		                                                        <button type="button" class="dropdown-item d-flex align-items-center action-password change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">
		                                                            <i class="bx bx-key me-2"></i>{{ __('Change Password') }}
		                                                        </button>
		                                                    </li>
	                                                    <li><hr class="dropdown-divider"></li>
	                                                    <li>
	                                                        <form action="{{ route('admin.web-users.destroy', $user->id) }}" method="POST" class="m-0 delete-form">
	                                                            @csrf @method('DELETE')
	                                                            <button type="button" class="dropdown-item d-flex align-items-center delete-confirmation">
	                                                                <i class="bx bx-trash me-2"></i>{{ __('Delete User') }}
	                                                            </button>
	                                                        </form>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">{{ __('No web users found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- ─── MOBILE CARD LIST ─── -->
                    <div class="d-md-none" id="users-mobile-list">
                        <div class="mb-3">
                            <input type="text" id="mobile-search" class="form-control form-control-sm" placeholder="{{ __('Quick search users…') }}">
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
                                                    <div class="mt-1">
                                                        @if($user->is_trader)
                                                            <span class="badge bg-label-info small me-1">{{ __('Trader') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="dropdown status-dropdown">
                                                    <button class="btn btn-sm dropdown-toggle hide-arrow p-0" type="button" data-bs-toggle="dropdown">
                                                        <span class="badge bg-{{ $statusClass }}">{{ __(ucfirst($status)) }}</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        @foreach(['active' => 'success', 'inactive' => 'warning', 'banned' => 'danger'] as $val => $cls)
                                                            <li>
                                                                <button type="button" class="dropdown-item d-flex align-items-center py-2 change-status" data-id="{{ $user->id }}" data-status="{{ $val }}">
                                                                    <span class="badge badge-dot bg-{{ $cls }} me-2"></span>{{ __(ucfirst($val)) }}
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <small class="text-muted">{{ __('Joined') }} {{ $user->created_at->formatDate() }}</small>
	                                        <div class="d-flex gap-1 users-actions">
	                                            <button type="button" class="btn btn-icon btn-sm btn-label-secondary border-0 show-details-btn" data-id="{{ $user->id }}" data-bs-toggle="tooltip" title="{{ __('View Details') }}" aria-label="{{ __('View Details') }}"><i class="icon-base bx bx-show"></i></button>
	                                            <a href="{{ route('admin.web-users.edit', $user->id) }}" class="btn btn-icon btn-sm btn-label-info border-0" data-bs-toggle="tooltip" title="{{ __('Edit Profile') }}" aria-label="{{ __('Edit Profile') }}"><i class="icon-base bx bx-edit-alt"></i></a>
	                                            <button type="button" class="btn btn-icon btn-sm btn-label-warning border-0 change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}" data-bs-toggle="tooltip" title="{{ __('Change Password') }}" aria-label="{{ __('Change Password') }}"><i class="icon-base bx bx-key"></i></button>
	                                            <form action="{{ route('admin.web-users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
	                                                @csrf @method('DELETE')
	                                                <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 delete-confirmation" data-bs-toggle="tooltip" title="{{ __('Delete User') }}" aria-label="{{ __('Delete User') }}"><i class="icon-base bx bx-trash"></i></button>
	                                            </form>
	                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">{{ __('No web users found.') }}</div>
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
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                        <p class="mt-3 text-muted outfit-font">{{ __('Loading User Profile...') }}</p>
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
                    <h5 class="modal-title fw-bold outfit-font"><i class="bx bx-key me-2 text-warning"></i> {{ __('Change User Password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <form id="changePasswordForm">
                    @csrf
                    <input type="hidden" id="cp_user_id" name="user_id">
                    <div class="modal-body p-4">
                        <div class="mb-3 text-center bg-label-info p-3 rounded border border-light border-opacity-50">
                            <small class="text-dark d-block">{{ __('Updating password for:') }}</small>
                            <span class="fw-bold fs-5" id="cp_user_name">User Name</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('New Password') }}</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" id="new_password" class="form-control" placeholder="{{ __('Enter at least 8 characters') }}" required>
                                <span class="input-group-text cursor-pointer" onclick="togglePassword('new_password')"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Confirm New Password') }}</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation" id="new_password_confirmation" class="form-control" placeholder="{{ __('Confirm the password') }}" required>
                                <span class="input-group-text cursor-pointer" onclick="togglePassword('new_password_confirmation')"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top p-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">{{ __('Save New Password') }}</button>
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
    function openUserDetailsModal(id) {
        if(!id) return;
        $('#userDetailsModal').modal('show');
        $('#modal-content-area').html('<div class="text-center py-5 my-5"><div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem;"></div><p class="mt-4 text-muted outfit-font fs-5">{{ __('Fetching profile data...') }}</p></div>');
        $.ajax({
            url: `{{ url('/app/users') }}/${id}`,
            method: 'GET',
            success: function(html) {
                $('#modal-content-area').html(html);
            },
            error: function() {
                $('#modal-content-area').html('<div class="alert alert-danger m-5 d-flex align-items-center"><i class="bx bx-error-circle me-3 fs-3"></i> {{ __('Failed to pull user details.') }}</div>');
            }
        });
    }

    // ─── INITIALIZE DATATABLE ───
    if ($.fn.DataTable) {
        $('#users-table').DataTable({
            order: [[1, 'asc']],
	            pageLength: 25,
	            autoWidth: false,
            dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>t<'row mt-3'<'col-sm-6'i><'col-sm-6'p>>",
            language: { 
                search: '', 
                searchPlaceholder: '{{ __('Quick Search Users…') }}'
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
                    toastr.success('{{ __('User status updated successfully') }}');
                    setTimeout(() => location.reload(), 500);
                }
            }
        });
    });

    // Keep status dropdown above other table rows
    $(document).on('shown.bs.dropdown', '.status-dropdown', function () {
        $(this).closest('tr').addClass('status-dropdown-open');
    });
    $(document).on('hide.bs.dropdown', '.status-dropdown', function () {
        $(this).closest('tr').removeClass('status-dropdown-open');
    });

    // View details (works from actions dropdown too)
    $(document).on('click', '.show-details-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).data('id') || $(this).closest('tr,[data-id]').data('id');
        openUserDetailsModal(id);
    });

    // Keep actions dropdown above other table rows
    $(document).on('shown.bs.dropdown', '.users-actions.dropdown', function () {
        $(this).closest('tr').addClass('actions-dropdown-open');
    });
    $(document).on('hide.bs.dropdown', '.users-actions.dropdown', function () {
        $(this).closest('tr').removeClass('actions-dropdown-open');
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
    $(document).on('click', '#users-table tbody tr, .user-mobile-card', function (e) {
        if ($(e.target).closest('form, a:not(.show-details-btn), button, .dropdown, .social-btn').length) return;
        
        e.preventDefault();
        const id = $(this).data('id');
        if(!id) return;

        openUserDetailsModal(id);
    });

    // ─── DELETE CONFIRMATION ───
    $(document).on('click', '.delete-confirmation', function(e) {
        e.stopPropagation();
        Swal.fire({
            title: '{{ __('Permanent Delete?') }}',
            text: "{{ __('This user and all their listings will be removed!') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, Delete!') }}',
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

		    /* Status dropdown should open above other table rows */
		    .table-responsive { overflow-y: visible; }
		    #users-table tbody tr.status-dropdown-open { position: relative; z-index: 1065; }
		    .td-status { position: relative; }
		    #users-table tbody tr.status-dropdown-open .td-status { z-index: 1066; }
		    .status-dropdown { position: relative; }
		    .status-dropdown.show { z-index: 1066; }
		    .status-dropdown .dropdown-menu { z-index: 1067; min-width: 140px; }

		    /* Actions dropdown should open above other table rows */
		    #users-table tbody tr.actions-dropdown-open { position: relative; z-index: 1070; }
		    #users-table tbody tr.actions-dropdown-open td:last-child { position: relative; z-index: 1071; }
		    .users-actions.dropdown.show { z-index: 1072; }
		    .users-actions.dropdown .dropdown-menu { z-index: 1073; }

		    /* Actions icons visibility */
		    .users-actions .btn.btn-icon {
		        width: 38px;
		        height: 38px;
		        display: inline-flex;
		        align-items: center;
		        justify-content: center;
		    }
		    .users-actions .btn.btn-icon i {
		        font-size: 1.25rem;
		        line-height: 1;
		    }
		    .users-actions .btn.btn-label-secondary:hover i,
		    .users-actions .btn.btn-label-secondary:focus i { color: var(--bs-secondary) !important; }
		    .users-actions .btn.btn-label-info:hover i,
		    .users-actions .btn.btn-label-info:focus i { color: var(--bs-info) !important; }
		    .users-actions .btn.btn-label-warning:hover i,
		    .users-actions .btn.btn-label-warning:focus i { color: var(--bs-warning) !important; }
		    .users-actions .btn.btn-label-danger:hover i,
		    .users-actions .btn.btn-label-danger:focus i { color: var(--bs-danger) !important; }
		    .users-actions .show-details-btn:hover i,
		    .users-actions .show-details-btn:focus i { color: var(--bs-primary) !important; }
		    .users-actions .dropdown-item.delete-confirmation { color: inherit; }
		    .users-actions .dropdown-item { transition: color .15s ease; }
		    .users-actions .dropdown-item i { transition: color .15s ease; }
		    .users-actions .dropdown-item.action-view:hover,
		    .users-actions .dropdown-item.action-view:focus,
		    .users-actions .dropdown-item.action-view:hover i,
		    .users-actions .dropdown-item.action-view:focus i { color: var(--bs-primary) !important; }
		    .users-actions .dropdown-item.action-edit:hover,
		    .users-actions .dropdown-item.action-edit:focus,
		    .users-actions .dropdown-item.action-edit:hover i,
		    .users-actions .dropdown-item.action-edit:focus i { color: var(--bs-info) !important; }
		    .users-actions .dropdown-item.action-password:hover,
		    .users-actions .dropdown-item.action-password:focus,
		    .users-actions .dropdown-item.action-password:hover i,
		    .users-actions .dropdown-item.action-password:focus i { color: var(--bs-warning) !important; }
		    .users-actions .dropdown-item.delete-confirmation:hover,
		    .users-actions .dropdown-item.delete-confirmation:focus,
		    .users-actions .dropdown-item.delete-confirmation:hover i,
		    .users-actions .dropdown-item.delete-confirmation:focus i { color: var(--bs-danger) !important; }

		    /* Fit all columns on screen (no bottom scrollbar) */
		    .table-responsive.d-none.d-md-block { overflow-x: visible !important; overflow-y: visible !important; }
		    #users-table_wrapper { overflow-x: visible !important; }
		    #users-table { width: 100% !important; table-layout: fixed; }
		    #users-table th, #users-table td { padding: .55rem .35rem !important; font-size: .875rem; vertical-align: middle; }
		    #users-table th { font-size: .82rem; white-space: normal; line-height: 1.15; }
		    #users-table td { word-break: break-word; }
		    #users-table td:nth-child(2) { white-space: normal; }
		    #users-table td:nth-child(2) .fw-bold,
		    #users-table td:nth-child(2) small { display: block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
		    #users-table td:nth-child(1) { width: 70px; }
		    #users-table td:nth-child(8) { width: 90px; }
		    #users-table .avatar.avatar-md img { width: 34px !important; height: 34px !important; }
		</style>
		@endsection
