@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Invoice List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Add Invoice
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
                            <th>Invoice #</th>
                            <th>Client</th>
                            <th>Total Due</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>#INV-001</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Acme Corporation</span>
                                        <br>
                                        <small class="text-muted">contact@acme.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>$2,450.00</td>
                            <td>Mar 15, 2024</td>
                            <td><span class="badge bg-label-success">Paid</span></td>
                            <td>$0.00</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-eye me-1"></i> Preview</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-printer me-1"></i> Print</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><code>#INV-002</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/2.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Tech Solutions Inc.</span>
                                        <br>
                                        <small class="text-muted">billing@techsolutions.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>$1,890.00</td>
                            <td>Mar 20, 2024</td>
                            <td><span class="badge bg-label-warning">Pending</span></td>
                            <td>$1,890.00</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-eye me-1"></i> Preview</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-printer me-1"></i> Print</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><code>#INV-003</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar" class="rounded-circle">
                                    </div>
                                    <div>
                                        <span class="fw-medium">Global Enterprises</span>
                                        <br>
                                        <small class="text-muted">accounts@global.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>$3,250.00</td>
                            <td>Mar 10, 2024</td>
                            <td><span class="badge bg-label-danger">Overdue</span></td>
                            <td>$3,250.00</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-eye me-1"></i> Preview</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-envelope me-1"></i> Send Reminder</a>
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
