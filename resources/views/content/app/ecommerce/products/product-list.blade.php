@extends('layouts/contentNavbarLayout')

@section('title', 'Product List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Product List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Add Product
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
                            <th></th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar me-3">
                                            <img src="{{ asset('assets/img/elements/08.jpg') }}" alt="Product" class="rounded-2">
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Apple iPhone 15 Pro</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">Electronics</span></td>
                            <td>45</td>
                            <td>IP15-PRO-256</td>
                            <td>$999.00</td>
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkbox2">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar me-3">
                                            <img src="{{ asset('assets/img/elements/09.jpg') }}" alt="Product" class="rounded-2">
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Samsung Galaxy S24</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-primary">Electronics</span></td>
                            <td>32</td>
                            <td>SG-S24-128</td>
                            <td>$799.00</td>
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
