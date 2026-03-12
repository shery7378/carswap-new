@extends('layouts/contentNavbarLayout')

@section('title', 'Category List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Category List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Add Category
                    </button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row table">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Product Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-mobile-alt"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Electronics</span>
                                    </div>
                                </div>
                            </td>
                            <td>Mobile phones, laptops, tablets and other electronic devices</td>
                            <td>156</td>
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
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-info">
                                            <i class="bx bx-shirt"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Clothing</span>
                                    </div>
                                </div>
                            </td>
                            <td>Men's and women's clothing, shoes and accessories</td>
                            <td>89</td>
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
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-initial rounded bg-label-warning">
                                            <i class="bx bx-book"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="fw-medium">Books</span>
                                    </div>
                                </div>
                            </td>
                            <td>Fiction, non-fiction, educational and children's books</td>
                            <td>234</td>
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
