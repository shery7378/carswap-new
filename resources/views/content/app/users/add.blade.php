@extends('layouts/contentNavbarLayout')

@section('title', 'Add User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New User</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">User Information</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Enter first name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Enter last name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email address">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter username">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="department">Department</label>
                            <select class="form-select" id="department">
                                <option selected>Select department</option>
                                <option value="sales">Sales</option>
                                <option value="marketing">Marketing</option>
                                <option value="development">Development</option>
                                <option value="support">Support</option>
                                <option value="hr">Human Resources</option>
                                <option value="finance">Finance</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Account Settings</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="role">User Role</label>
                            <select class="form-select" id="role">
                                <option selected>Select role</option>
                                <option value="admin">Administrator</option>
                                <option value="editor">Editor</option>
                                <option value="viewer">Viewer</option>
                                <option value="moderator">Moderator</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Account Status</label>
                            <select class="form-select" id="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Security</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" placeholder="Enter password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="bx bx-show"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="confirmPassword">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirmPassword')">
                                    <i class="bx bx-show"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="sendEmail" checked>
                                <label class="form-check-label" for="sendEmail">Send welcome email to user</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="requirePasswordChange" checked>
                                <label class="form-check-label" for="requirePasswordChange">Require password change on first login</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                <label class="form-check-label" for="twoFactorAuth">Enable two-factor authentication</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Permissions</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Module Access</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="dashboardAccess" checked>
                                <label class="form-check-label" for="dashboardAccess">Dashboard Access</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="productsAccess" checked>
                                <label class="form-check-label" for="productsAccess">Products Management</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="ordersAccess">
                                <label class="form-check-label" for="ordersAccess">Orders Management</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="customersAccess">
                                <label class="form-check-label" for="customersAccess">Customers Management</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">System Permissions</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="userManagement">
                                <label class="form-check-label" for="userManagement">User Management</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="roleManagement">
                                <label class="form-check-label" for="roleManagement">Role Management</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="systemSettings">
                                <label class="form-check-label" for="systemSettings">System Settings</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="reportsAccess">
                                <label class="form-check-label" for="reportsAccess">Reports Access</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notes">Additional Notes</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Add any additional notes about this user..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                <button type="button" class="btn btn-outline-primary">Save as Draft</button>
                                <button type="submit" class="btn btn-primary">Create User</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    } else {
        field.type = 'password';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    }
}
</script>
@endsection
