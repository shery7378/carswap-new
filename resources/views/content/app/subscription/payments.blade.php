@extends('layouts/contentNavbarLayout')

@section('title', 'Payment History')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Payment History</h5>
                <div class="d-flex gap-2 mt-3">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Search payments...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-outline-secondary waves-effect">
                        <i class="bx bx-filter me-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary waves-effect">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Customer</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>#TRX001234</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">John Doe</span>
                                        <br>
                                        <small class="text-muted">john@example.com</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">Premium</span></td>
                            <td>$29.99</td>
                            <td><span class="badge bg-label-success">Completed</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2"></i>
                                    <span>Credit Card</span>
                                </div>
                            </td>
                            <td>Feb 15, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-receipt me-1"></i> View Receipt</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download Invoice</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-refresh me-1"></i> Refund</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><code>#TRX001235</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/2.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Jane Smith</span>
                                        <br>
                                        <small class="text-muted">jane@example.com</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-info">Basic</span></td>
                            <td>$9.99</td>
                            <td><span class="badge bg-label-success">Completed</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bxl-paypal me-2"></i>
                                    <span>PayPal</span>
                                </div>
                            </td>
                            <td>Feb 14, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-receipt me-1"></i> View Receipt</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download Invoice</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-refresh me-1"></i> Refund</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><code>#TRX001236</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Mike Johnson</span>
                                        <br>
                                        <small class="text-muted">mike@example.com</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-warning">Enterprise</span></td>
                            <td>$99.99</td>
                            <td><span class="badge bg-label-danger">Failed</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2"></i>
                                    <span>Credit Card</span>
                                </div>
                            </td>
                            <td>Feb 13, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-refresh me-1"></i> Retry Payment</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Update Payment Method</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><code>#TRX001237</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/4.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Sarah Wilson</span>
                                        <br>
                                        <small class="text-muted">sarah@example.com</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">Premium</span></td>
                            <td>$29.99</td>
                            <td><span class="badge bg-label-warning">Pending</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-bank me-2"></i>
                                    <span>Bank Transfer</span>
                                </div>
                            </td>
                            <td>Feb 12, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-check me-1"></i> Mark as Paid</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-x-circle me-1"></i> Cancel</a>
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
