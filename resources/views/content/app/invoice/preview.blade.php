@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice Preview')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Invoice #INV-001</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bx bx-download me-1"></i> Download
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="bx bx-printer me-1"></i> Print
                        </button>
                        <button type="button" class="btn btn-primary">
                            <i class="bx bx-envelope me-1"></i> Send
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="invoice-preview">
                    <div class="d-flex justify-content-between flex-md-row flex-column mb-4">
                        <div>
                            <h4 class="text-primary fw-bold mb-2">INVOICE</h4>
                            <div class="mb-2">
                                <span class="text-muted">Invoice No:</span>
                                <span class="fw-medium">#INV-001</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Date:</span>
                                <span class="fw-medium">Feb 15, 2024</span>
                            </div>
                            <div>
                                <span class="text-muted">Due Date:</span>
                                <span class="fw-medium">Mar 15, 2024</span>
                            </div>
                        </div>
                        <div class="mt-md-0 mt-4">
                            <div class="mb-2">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-label-success ms-1">Paid</span>
                            </div>
                            <div>
                                <span class="text-muted">Balance Due:</span>
                                <span class="fw-medium h4 text-success">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-4">
                        <div class="col-md-6 mb-md-0 mb-4">
                            <h6 class="text-uppercase fw-medium mb-3">Invoice From:</h6>
                            <div class="mb-2">
                                <strong>Your Company Name</strong>
                            </div>
                            <div class="mb-2">123 Business Street</div>
                            <div class="mb-2">New York, NY 10001</div>
                            <div class="mb-2">United States</div>
                            <div class="mb-2">+1 (555) 123-4567</div>
                            <div>contact@yourcompany.com</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase fw-medium mb-3">Bill To:</h6>
                            <div class="mb-2">
                                <strong>Acme Corporation</strong>
                            </div>
                            <div class="mb-2">Attention: John Doe</div>
                            <div class="mb-2">456 Client Avenue</div>
                            <div class="mb-2">Los Angeles, CA 90001</div>
                            <div class="mb-2">United States</div>
                            <div>contact@acme.com</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Web Development</td>
                                    <td>Custom website development with responsive design</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">$1,500.00</td>
                                    <td class="text-end">$1,500.00</td>
                                </tr>
                                <tr>
                                    <td>SEO Optimization</td>
                                    <td>Search engine optimization for better rankings</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">$500.00</td>
                                    <td class="text-end">$500.00</td>
                                </tr>
                                <tr>
                                    <td>Hosting Setup</td>
                                    <td>Server configuration and deployment</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">$300.00</td>
                                    <td class="text-end">$300.00</td>
                                </tr>
                                <tr>
                                    <td>Support Package</td>
                                    <td>3 months technical support and maintenance</td>
                                    <td class="text-end">1</td>
                                    <td class="text-end">$150.00</td>
                                    <td class="text-end">$150.00</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Subtotal:</td>
                                    <td class="text-end">$2,450.00</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end">Tax (8.25%):</td>
                                    <td class="text-end">$201.13</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold h5">$2,651.13</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase fw-medium mb-3">Payment Details:</h6>
                            <div class="mb-2">
                                <span class="text-muted">Payment Method:</span>
                                <span class="fw-medium">Bank Transfer</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Account Name:</span>
                                <span class="fw-medium">Your Company Name</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Account Number:</span>
                                <span class="fw-medium">1234567890</span>
                            </div>
                            <div>
                                <span class="text-muted">Routing Number:</span>
                                <span class="fw-medium">987654321</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase fw-medium mb-3">Notes:</h6>
                            <p class="text-muted">Thank you for your business! Please make payment within 30 days of invoice date. For any questions regarding this invoice, please contact our billing department.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
