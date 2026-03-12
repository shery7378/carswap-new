@extends('layouts/contentNavbarLayout')

@section('title', 'Tax Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tax Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">General Tax Configuration</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="enableTax" checked>
                                <label class="form-check-label" for="enableTax">Enable Tax Calculation</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="taxIncluded" checked>
                                <label class="form-check-label" for="taxIncluded">Display Prices Including Tax</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="taxExempt">
                                <label class="form-check-label" for="taxExempt">Allow Tax Exempt Customers</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="digitalTax" checked>
                                <label class="form-check-label" for="digitalTax">Apply Tax to Digital Products</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Default Tax Rates</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="defaultRate">Default Tax Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="defaultRate" value="8.25" step="0.01" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="reducedRate">Reduced Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="reducedRate" value="4.50" step="0.01" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="zeroRate">Zero Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="zeroRate" value="0" step="0.01" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Regional Tax Rules</h6>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Region</th>
                                            <th>Country</th>
                                            <th>Tax Rate</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>California</td>
                                            <td>United States</td>
                                            <td>8.75%</td>
                                            <td><span class="badge bg-label-success">Active</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">Edit</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>New York</td>
                                            <td>United States</td>
                                            <td>8.00%</td>
                                            <td><span class="badge bg-label-success">Active</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">Edit</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Texas</td>
                                            <td>United States</td>
                                            <td>6.25%</td>
                                            <td><span class="badge bg-label-success">Active</span></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary">Edit</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-3">
                                <i class="bx bx-plus me-1"></i> Add Tax Rule
                            </button>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Tax Exemptions</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="exemptCategories">Exempt Categories</label>
                            <select class="form-select" id="exemptCategories" multiple>
                                <option value="books" selected>Books</option>
                                <option value="food">Food & Beverages</option>
                                <option value="medical">Medical Supplies</option>
                                <option value="education">Educational Materials</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="exemptCustomers">Exempt Customer Types</label>
                            <select class="form-select" id="exemptCustomers" multiple>
                                <option value="nonprofit" selected>Non-Profit Organizations</option>
                                <option value="government">Government Agencies</option>
                                <option value="reseller">Resellers</option>
                                <option value="international">International Customers</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
