@extends('layouts/contentNavbarLayout')

@section('title', 'Roles List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Roles List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Add Role
                    </button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row table">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th>Users Count</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-crown"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Super Admin</span>
                                    </div>
                                </div>
                            </td>
                            <td>Full system access with all permissions</td>
                            <td><span class="badge bg-label-primary">2</span></td>
                            <td>Jan 15, 2024</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-shield me-1"></i> Permissions</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-info">
                                            <i class="bx bx-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Admin</span>
                                    </div>
                                </div>
                            </td>
                            <td>Administrative access with limited permissions</td>
                            <td><span class="badge bg-label-info">5</span></td>
                            <td>Jan 20, 2024</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-shield me-1"></i> Permissions</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-warning">
                                            <i class="bx bx-edit"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Editor</span>
                                    </div>
                                </div>
                            </td>
                            <td>Content editing and publishing permissions</td>
                            <td><span class="badge bg-label-warning">8</span></td>
                            <td>Feb 1, 2024</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-shield me-1"></i> Permissions</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-user-voice"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Viewer</span>
                                    </div>
                                </div>
                            </td>
                            <td>Read-only access to view content</td>
                            <td><span class="badge bg-label-secondary">15</span></td>
                            <td>Feb 5, 2024</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-shield me-1"></i> Permissions</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
