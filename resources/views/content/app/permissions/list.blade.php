@extends('layouts/contentNavbarLayout')

@section('title', 'Permissions List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Permissions List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Add Permission
                    </button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row table">
                    <thead>
                        <tr>
                            <th>Permission Name</th>
                            <th>Group</th>
                            <th>Description</th>
                            <th>Roles</th>
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
                                            <i class="bx bx-cog"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">user.create</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">User Management</span></td>
                            <td>Create new users in the system</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-label-primary">Super Admin</span>
                                    <span class="badge bg-label-info">Admin</span>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
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
                                            <i class="bx bx-edit"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">user.edit</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">User Management</span></td>
                            <td>Edit existing user information</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-label-primary">Super Admin</span>
                                    <span class="badge bg-label-info">Admin</span>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
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
                                            <i class="bx bx-file"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">content.create</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-warning">Content Management</span></td>
                            <td>Create new content and articles</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-label-primary">Super Admin</span>
                                    <span class="badge bg-label-info">Admin</span>
                                    <span class="badge bg-label-warning">Editor</span>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
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
                                            <i class="bx bx-show"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">content.view</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-warning">Content Management</span></td>
                            <td>View content and articles</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-label-primary">Super Admin</span>
                                    <span class="badge bg-label-info">Admin</span>
                                    <span class="badge bg-label-warning">Editor</span>
                                    <span class="badge bg-label-secondary">Viewer</span>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-success">
                                            <i class="bx bx-dollar"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">invoice.create</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Billing</span></td>
                            <td>Create and generate invoices</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-label-primary">Super Admin</span>
                                    <span class="badge bg-label-info">Admin</span>
                                </div>
                            </td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
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
