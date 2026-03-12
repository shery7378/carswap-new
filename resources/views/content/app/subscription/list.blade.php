@extends('layouts/contentNavbarLayout')

@section('title', 'Subscription List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Subscription List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Create Subscription
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
                            <th>Customer</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Next Billing</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
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
                            <td>$29.99/month</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>Mar 15, 2024</td>
                            <td>3 months</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-pause me-1"></i> Pause</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-x-circle me-1"></i> Cancel</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
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
                            <td>$9.99/month</td>
                            <td><span class="badge bg-label-success">Active</span></td>
                            <td>Mar 20, 2024</td>
                            <td>12 months</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-pause me-1"></i> Pause</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-x-circle me-1"></i> Cancel</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
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
                            <td>$99.99/month</td>
                            <td><span class="badge bg-label-warning">Paused</span></td>
                            <td>Apr 1, 2024</td>
                            <td>6 months</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-play me-1"></i> Resume</a>
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
