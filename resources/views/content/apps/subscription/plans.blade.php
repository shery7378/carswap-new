@extends('layouts/contentNavbarLayout')

@section('title', 'Subscription Plans')

@section('content')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Subscription Plans</h4>
              <div class="card-actions">
                <button class="btn btn-primary">
                  <i class="bx bx-plus me-1"></i> Add New Plan
                </button>
              </div>
            </div>
            <div class="card-body">
              <!-- Pricing Cards -->
              <div class="row">
                <!-- Basic Plan -->
                <div class="col-lg-4 col-md-6 mb-4">
                  <div class="card border-primary">
                    <div class="card-body text-center">
                      <h3 class="card-title">Basic</h3>
                      <div class="py-3">
                        <h2 class="text-primary">$9.99<span class="text-muted fs-6">/month</span></h2>
                      </div>
                      <ul class="list-unstyled">
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>10 Products</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Basic Support</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>1 GB Storage</li>
                        <li class="py-2 text-muted"><i class="bx bx-x text-danger me-2"></i>Analytics</li>
                        <li class="py-2 text-muted"><i class="bx bx-x text-danger me-2"></i>API Access</li>
                      </ul>
                      <button class="btn btn-outline-primary w-100">Choose Plan</button>
                    </div>
                  </div>
                </div>

                <!-- Pro Plan -->
                <div class="col-lg-4 col-md-6 mb-4">
                  <div class="card border-success">
                    <div class="card-body text-center">
                      <span class="badge bg-success position-absolute top-0 end-0 m-2">Popular</span>
                      <h3 class="card-title">Professional</h3>
                      <div class="py-3">
                        <h2 class="text-success">$29.99<span class="text-muted fs-6">/month</span></h2>
                      </div>
                      <ul class="list-unstyled">
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Unlimited Products</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Priority Support</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>10 GB Storage</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Advanced Analytics</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>API Access</li>
                      </ul>
                      <button class="btn btn-success w-100">Choose Plan</button>
                    </div>
                  </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="col-lg-4 col-md-6 mb-4">
                  <div class="card border-warning">
                    <div class="card-body text-center">
                      <h3 class="card-title">Enterprise</h3>
                      <div class="py-3">
                        <h2 class="text-warning">$99.99<span class="text-muted fs-6">/month</span></h2>
                      </div>
                      <ul class="list-unstyled">
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Unlimited Everything</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>24/7 Support</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Unlimited Storage</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Custom Analytics</li>
                        <li class="py-2"><i class="bx bx-check text-success me-2"></i>Advanced API</li>
                      </ul>
                      <button class="btn btn-warning w-100">Choose Plan</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Active Plans Table -->
              <div class="mt-5">
                <h5 class="mb-3">Active Plans Management</h5>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Plan Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Basic Plan</td>
                        <td>$9.99/month</td>
                        <td>Monthly</td>
                        <td>10 Products, Basic Support</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary">Edit</button>
                          <button class="btn btn-sm btn-outline-danger">Disable</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Professional Plan</td>
                        <td>$29.99/month</td>
                        <td>Monthly</td>
                        <td>Unlimited Products, Priority Support</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary">Edit</button>
                          <button class="btn btn-sm btn-outline-danger">Disable</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
