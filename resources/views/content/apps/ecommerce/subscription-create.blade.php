@extends('layouts/contentNavbarLayout')

@section('title', 'Create Subscription')

@section('content')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <!-- Create Subscription Form -->
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Create New Subscription</h5>
            </div>
            <div class="card-body">
              <form id="createSubscriptionForm">
                <!-- Customer Information -->
                <div class="mb-4">
                  <h6 class="mb-3">Customer Information</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="customerName" class="form-label">Customer Name</label>
                      <input type="text" class="form-control" id="customerName" placeholder="Enter customer name"
                        required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="customerEmail" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="customerEmail" placeholder="customer@example.com"
                        required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="customerPhone" class="form-label">Phone Number</label>
                      <input type="tel" class="form-control" id="customerPhone" placeholder="+1 234 567 8900">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="customerCompany" class="form-label">Company</label>
                      <input type="text" class="form-control" id="customerCompany"
                        placeholder="Company name (optional)">
                    </div>
                  </div>
                </div>

                <!-- Plan Selection -->
                <div class="mb-4">
                  <h6 class="mb-3">Select Plan</h6>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <div class="card border-primary">
                        <div class="card-body text-center">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="plan" id="basicPlan" value="basic">
                            <label class="form-check-label" for="basicPlan">
                              <h5 class="card-title">Basic</h5>
                              <h3 class="text-primary">$9.99<span class="text-muted fs-6">/month</span></h3>
                            </label>
                          </div>
                          <ul class="list-unstyled text-start mt-3">
                            <li><i class="bx bx-check text-success me-2"></i>10 Products</li>
                            <li><i class="bx bx-check text-success me-2"></i>Basic Support</li>
                            <li><i class="bx bx-check text-success me-2"></i>1 GB Storage</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <div class="card border-success">
                        <div class="card-body text-center">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="plan" id="proPlan"
                              value="professional" checked>
                            <label class="form-check-label" for="proPlan">
                              <h5 class="card-title">Professional</h5>
                              <h3 class="text-success">$29.99<span class="text-muted fs-6">/month</span></h3>
                            </label>
                          </div>
                          <ul class="list-unstyled text-start mt-3">
                            <li><i class="bx bx-check text-success me-2"></i>Unlimited Products</li>
                            <li><i class="bx bx-check text-success me-2"></i>Priority Support</li>
                            <li><i class="bx bx-check text-success me-2"></i>10 GB Storage</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <div class="card border-warning">
                        <div class="card-body text-center">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="plan" id="enterprisePlan"
                              value="enterprise">
                            <label class="form-check-label" for="enterprisePlan">
                              <h5 class="card-title">Enterprise</h5>
                              <h3 class="text-warning">$99.99<span class="text-muted fs-6">/month</span></h3>
                            </label>
                          </div>
                          <ul class="list-unstyled text-start mt-3">
                            <li><i class="bx bx-check text-success me-2"></i>Unlimited Everything</li>
                            <li><i class="bx bx-check text-success me-2"></i>24/7 Support</li>
                            <li><i class="bx bx-check text-success me-2"></i>Unlimited Storage</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Billing Information -->
                <div class="mb-4">
                  <h6 class="mb-3">Billing Information</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="billingCycle" class="form-label">Billing Cycle</label>
                      <select class="form-select" id="billingCycle">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly (10% off)</option>
                        <option value="yearly">Yearly (20% off)</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="startDate" class="form-label">Start Date</label>
                      <input type="date" class="form-control" id="startDate" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="billingAddress" class="form-label">Billing Address</label>
                      <textarea class="form-control" id="billingAddress" rows="3" placeholder="Enter billing address"></textarea>
                    </div>
                  </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                  <h6 class="mb-3">Payment Method</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="paymentMethod" class="form-label">Payment Method</label>
                      <select class="form-select" id="paymentMethod">
                        <option value="credit-card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank-transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="cardNumber" class="form-label">Card Number</label>
                      <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="expiryDate" class="form-label">Expiry Date</label>
                      <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="cvv" class="form-label">CVV</label>
                      <input type="text" class="form-control" id="cvv" placeholder="123">
                    </div>
                  </div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-4">
                  <label for="notes" class="form-label">Additional Notes</label>
                  <textarea class="form-control" id="notes" rows="3"
                    placeholder="Any additional notes or special requirements"></textarea>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                  <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Create Subscription
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Plan:</span>
                  <span id="summaryPlan">Professional</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Billing Cycle:</span>
                  <span id="summaryCycle">Monthly</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Unit Price:</span>
                  <span id="summaryPrice">$29.99</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Discount:</span>
                  <span id="summaryDiscount" class="text-success">-$0.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                  <strong>Total:</strong>
                  <strong id="summaryTotal">$29.99</strong>
                </div>
              </div>

              <div class="alert alert-info">
                <i class="bx bx-info-circle me-2"></i>
                <small>Subscription will be automatically renewed based on the selected billing cycle.</small>
              </div>
            </div>
          </div>

          <!-- Quick Stats -->
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between mb-3">
                <span>Total Subscriptions:</span>
                <span class="badge bg-primary">142</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span>Active This Month:</span>
                <span class="badge bg-success">28</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>Revenue This Month:</span>
                <span class="badge bg-success">$2,458.90</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
