@extends('layouts/contentNavbarLayout')

@section('title', 'User Details')

@section('content')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <!-- User Profile Card -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body text-center">
              <div class="avatar avatar-xl mb-3">
                <img src="https://picsum.photos/seed/user1/150/150.jpg" alt="User Avatar" class="rounded-circle">
              </div>
              <h4 class="card-title">John Doe</h4>
              <p class="card-text text-muted">Administrator</p>
              <div class="d-flex justify-content-center gap-2 mb-3">
                <span class="badge bg-success">Active</span>
                <span class="badge bg-primary">Verified</span>
              </div>
              <div class="d-grid gap-2">
                <button class="btn btn-primary">
                  <i class="bx bx-edit me-1"></i> Edit Profile
                </button>
                <button class="btn btn-outline-secondary">
                  <i class="bx bx-lock me-1"></i> Reset Password
                </button>
              </div>
            </div>
          </div>

          <!-- Quick Stats -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Total Orders:</span>
                  <span class="badge bg-primary">45</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Total Spent:</span>
                  <span class="badge bg-success">$2,345.67</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Average Order:</span>
                  <span class="badge bg-info">$52.13</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Member Since:</span>
                  <span class="badge bg-secondary">Jan 2024</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
              <div class="timeline">
                <div class="timeline-item">
                  <div class="timeline-point timeline-point-primary"></div>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                      <small class="text-muted">2 hours ago</small>
                    </div>
                    <p class="mb-0">Logged in from New York</p>
                  </div>
                </div>
                <div class="timeline-item">
                  <div class="timeline-point timeline-point-success"></div>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                      <small class="text-muted">1 day ago</small>
                    </div>
                    <p class="mb-0">Placed order #1234</p>
                  </div>
                </div>
                <div class="timeline-item">
                  <div class="timeline-point timeline-point-info"></div>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                      <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-0">Updated profile information</p>
                  </div>
                </div>
                <div class="timeline-item">
                  <div class="timeline-point timeline-point-warning"></div>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between">
                      <small class="text-muted">1 week ago</small>
                    </div>
                    <p class="mb-0">Password reset request</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
          <!-- Personal Information -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" value="John" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" value="Doe" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" value="john.doe@example.com" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" class="form-control" value="+1 234 567 8900" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" class="form-control" value="1990-01-15" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Gender</label>
                  <input type="text" class="form-control" value="Male" readonly>
                </div>
                <div class="col-12 mb-3">
                  <label class="form-label">Address</label>
                  <textarea class="form-control" rows="2" readonly>123 Main Street, Apt 4B
New York, NY 10001</textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Account Information -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Account Information</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">User ID</label>
                  <input type="text" class="form-control" value="#USR001" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control" value="johndoe" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Role</label>
                  <input type="text" class="form-control" value="Administrator" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Status</label>
                  <input type="text" class="form-control" value="Active" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Member Since</label>
                  <input type="date" class="form-control" value="2024-01-15" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Last Login</label>
                  <input type="text" class="form-control" value="2024-01-20 14:30:00" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email Verified</label>
                  <input type="text" class="form-control" value="Yes" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Two-Factor Auth</label>
                  <input type="text" class="form-control" value="Enabled" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Order History -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><code>#ORD1234</code></td>
                      <td>2024-01-20</td>
                      <td>$89.99</td>
                      <td><span class="badge bg-success">Delivered</span></td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary">View</button>
                      </td>
                    </tr>
                    <tr>
                      <td><code>#ORD1233</code></td>
                      <td>2024-01-18</td>
                      <td>$45.67</td>
                      <td><span class="badge bg-info">Shipped</span></td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary">View</button>
                      </td>
                    </tr>
                    <tr>
                      <td><code>#ORD1232</code></td>
                      <td>2024-01-15</td>
                      <td>$123.45</td>
                      <td><span class="badge bg-warning">Processing</span></td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary">View</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="text-center">
                <button class="btn btn-outline-primary">View All Orders</button>
              </div>
            </div>
          </div>

          <!-- Security Settings -->
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Security Settings</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="twoFactor" checked disabled>
                    <label class="form-check-label" for="twoFactor">
                      Two-Factor Authentication
                    </label>
                  </div>
                  <small class="text-muted">Enhanced security with 2FA</small>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="emailAlerts" checked disabled>
                    <label class="form-check-label" for="emailAlerts">
                      Email Alerts
                    </label>
                  </div>
                  <small class="text-muted">Receive security alerts via email</small>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="sessionTimeout" checked disabled>
                    <label class="form-check-label" for="sessionTimeout">
                      Session Timeout
                    </label>
                  </div>
                  <small class="text-muted">Auto-logout after inactivity</small>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="ipRestriction" disabled>
                    <label class="form-check-label" for="ipRestriction">
                      IP Restriction
                    </label>
                  </div>
                  <small class="text-muted">Restrict access to specific IPs</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .timeline {
      position: relative;
      padding-left: 30px;
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 8px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #e9ecef;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 20px;
    }

    .timeline-point {
      position: absolute;
      left: -22px;
      top: 5px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      border: 2px solid #fff;
    }

    .timeline-point-primary {
      background: #696cff;
    }

    .timeline-point-success {
      background: #71dd37;
    }

    .timeline-point-info {
      background: #03c3ec;
    }

    .timeline-point-warning {
      background: #ffab00;
    }

    .timeline-content {
      background: #f8f8f8;
      padding: 10px;
      border-radius: 5px;
      margin-left: 10px;
    }
  </style>
@endsection
