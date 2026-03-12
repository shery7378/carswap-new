@extends('layouts/contentNavbarLayout')

@section('title', 'Add Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Product</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productSKU">SKU</label>
                            <input type="text" class="form-control" id="productSKU" placeholder="Enter SKU">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productCategory">Category</label>
                            <select class="form-select" id="productCategory">
                                <option selected>Select category</option>
                                <option value="1">Electronics</option>
                                <option value="2">Clothing</option>
                                <option value="3">Books</option>
                                <option value="4">Home & Garden</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productBrand">Brand</label>
                            <input type="text" class="form-control" id="productBrand" placeholder="Enter brand name">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="productDescription">Description</label>
                            <textarea class="form-control" id="productDescription" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productPrice">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="productPrice" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productStock">Stock Quantity</label>
                            <input type="number" class="form-control" id="productStock" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productStatus">Status</label>
                            <select class="form-select" id="productStatus">
                                <option selected>Select status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="productImage">Product Images</label>
                            <div class="dropzone border-dashed bg-light rounded-3 p-4 text-center">
                                <i class="bx bx-cloud-upload display-4 text-muted"></i>
                                <p class="mt-2 mb-0">Drop files here or click to upload</p>
                                <input type="file" class="d-none" id="productImage" multiple accept="image/*">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
