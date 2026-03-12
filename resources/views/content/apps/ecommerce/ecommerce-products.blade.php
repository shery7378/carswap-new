@extends('layouts/contentNavbarLayout')

@section('title', 'eCommerce Products')

@section('content')
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">eCommerce /</span> Products</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product List</h5>
        <div>
          <button type="button" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Add Product
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Stock</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/img/elements/01.png')" alt="Product" class="rounded me-3" width="50">
                    <div>
                      <h6 class="mb-0">Apple iPhone 13 Pro</h6>
                      <small class="text-muted">Electronics</small>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-label-primary">Electronics</span></td>
                <td>45</td>
                <td>IP13P001</td>
                <td>$999</td>
                <td><span class="badge bg-label-success">Active</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/img/elements/02.png')" alt="Product" class="rounded me-3" width="50">
                    <div>
                      <h6 class="mb-0">Samsung Galaxy S21</h6>
                      <small class="text-muted">Electronics</small>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-label-primary">Electronics</span></td>
                <td>32</td>
                <td>SGS21001</td>
                <td>$799</td>
                <td><span class="badge bg-label-success">Active</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/img/elements/03.png')" alt="Product" class="rounded me-3" width="50">
                    <div>
                      <h6 class="mb-0">Nike Air Max 90</h6>
                      <small class="text-muted">Footwear</small>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-label-info">Footwear</span></td>
                <td>78</td>
                <td>NAM90001</td>
                <td>$120</td>
                <td><span class="badge bg-label-success">Active</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i> Delete</a>
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
