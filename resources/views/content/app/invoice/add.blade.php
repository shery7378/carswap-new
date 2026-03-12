@extends('layouts/contentNavbarLayout')

@section('title', 'Add Invoice')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New Invoice</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Invoice Details</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="invoiceNumber">Invoice Number</label>
                            <input type="text" class="form-control" id="invoiceNumber" value="#INV-004" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="invoiceDate">Invoice Date</label>
                            <input type="date" class="form-control" id="invoiceDate">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="dueDate">Due Date</label>
                            <input type="date" class="form-control" id="dueDate">
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Client Information</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="clientName">Client Name</label>
                            <input type="text" class="form-control" id="clientName" placeholder="Enter client name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="clientEmail">Client Email</label>
                            <input type="email" class="form-control" id="clientEmail" placeholder="Enter client email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="clientAddress">Address</label>
                            <input type="text" class="form-control" id="clientAddress" placeholder="Enter client address">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="clientCity">City</label>
                            <input type="text" class="form-control" id="clientCity" placeholder="City">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="clientZip">ZIP Code</label>
                            <input type="text" class="form-control" id="clientZip" placeholder="ZIP code">
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-uppercase fw-medium mb-0">Invoice Items</h6>
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i> Add Item
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" placeholder="Item name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" placeholder="Description">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-end" value="1" min="1">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-end" placeholder="0.00" step="0.01" min="0">
                                            </td>
                                            <td class="text-end fw-medium">$0.00</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase fw-medium mb-3">Tax & Discounts</h6>
                            <div class="mb-3">
                                <label class="form-label" for="taxRate">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="taxRate" value="8.25" step="0.01" min="0" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="discountAmount">Discount Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="discountAmount" value="0" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase fw-medium mb-3">Summary</h6>
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="fw-medium">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span class="fw-medium">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span class="fw-medium">-$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span class="h5 text-primary">$0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Add any notes or terms..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                <button type="button" class="btn btn-outline-primary">Preview</button>
                                <button type="submit" class="btn btn-primary">Create Invoice</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
