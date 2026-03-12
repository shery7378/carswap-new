@extends('layouts.app')

@section('title', 'eCommerce Dashboard')

@section('content')
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-12 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Congratulations! 🎉</h5>
                <p class="mb-4">You have sold <span class="fw-bold">$12,473</span> worth of products this month.</p>
                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Sales</a>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                  alt="View Sales">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Revenue -->
      <div class="col-12 col-lg-8 order-1 order-md-0">
        <div class="card">
          <div class="row row-bordered g-0">
            <div class="col-md-8">
              <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
              <div id="totalRevenueChart" class="px-2"></div>
            </div>
            <div class="col-md-4">
              <div class="card-body">
                <div class="text-center">
                  <div class="border rounded p-3 pt-2 pb-2">
                    <div class="avatar avatar-lg bg-label-primary">
                      <i class="bx bx-trending-up avatar-icon"></i>
                    </div>
                    <div class="pt-2">
                      <h6 class="mb-0">Total Revenue</h6>
                      <h4 class="mb-0">$12,473</h4>
                      <small class="text-success"><i class='bx bx-chevron-up'></i> 15.8% </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Total Revenue -->
      <!-- Statistics -->
      <div class="col-12 col-lg-4 order-2 order-md-1">
        <div class="row">
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="avatar avatar-lg bg-label-success rounded">
                  <i class="bx bx-shopping-bag avatar-icon"></i>
                </div>
                <div class="pt-2">
                  <h6 class="mb-0">Total Orders</h6>
                  <h4 class="mb-0">1,245</h4>
                  <small class="text-success"><i class='bx bx-chevron-up'></i> 12.5% </small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="avatar avatar-lg bg-label-info rounded">
                  <i class="bx bx-user avatar-icon"></i>
                </div>
                <div class="pt-2">
                  <h6 class="mb-0">Total Customers</h6>
                  <h4 class="mb-0">8,549</h4>
                  <small class="text-danger"><i class='bx bx-chevron-down'></i> 2.4% </small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="avatar avatar-lg bg-label-danger rounded">
                  <i class="bx bx-package avatar-icon"></i>
                </div>
                <div class="pt-2">
                  <h6 class="mb-0">Total Products</h6>
                  <h4 class="mb-0">456</h4>
                  <small class="text-success"><i class='bx bx-chevron-up'></i> 8.1% </small>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="avatar avatar-lg bg-label-warning rounded">
                  <i class="bx bx-dollar avatar-icon"></i>
                </div>
                <div class="pt-2">
                  <h6 class="mb-0">Total Revenue</h6>
                  <h4 class="mb-0">$12,473</h4>
                  <small class="text-success"><i class='bx bx-chevron-up'></i> 15.8% </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Statistics -->
    </div>
  </div>
  <!-- / Content -->
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/dashboards-ecommerce.js') }}"></script>
@endpush
