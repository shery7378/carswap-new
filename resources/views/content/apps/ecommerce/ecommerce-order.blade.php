@extends('layouts/contentNavbarLayout')

@section('title', 'eCommerce Orders')

@section('content')
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">eCommerce /</span> Orders</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Order List</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <tr>
                <td><strong>#ORD-001</strong></td>
                <td>John Doe</td>
                <td>Jan 15, 2024</td>
                <td>$1,299</td>
                <td><span class="badge bg-label-success">Delivered</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-show me-2"></i> View</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><strong>#ORD-002</strong></td>
                <td>Jane Smith</td>
                <td>Jan 16, 2024</td>
                <td>$899</td>
                <td><span class="badge bg-label-warning">Processing</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-show me-2"></i> View</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
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
  <!-- / Content -->
@endsection
