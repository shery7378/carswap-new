@extends('layouts/contentNavbarLayout')

@section('title', 'Notification Settings')

@section('content')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <!-- Notification Settings Form -->
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Notification Settings</h5>
            </div>
            <div class="card-body">
              <form id="notificationSettingsForm">
                <!-- Email Notifications -->
                <div class="mb-4">
                  <h6 class="mb-3">Email Notifications</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enableEmailNotifications" checked>
                        <label class="form-check-label" for="enableEmailNotifications">
                          Enable Email Notifications
                        </label>
                      </div>
                      <small class="text-muted">Send notifications via email</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="fromEmail" class="form-label">From Email Address</label>
                      <input type="email" class="form-control" id="fromEmail" value="noreply@mystore.com">
                      <small class="text-muted">Email address to send notifications from</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="fromName" class="form-label">From Name</label>
                      <input type="text" class="form-control" id="fromName" value="My eCommerce Store">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="replyTo" class="form-label">Reply-To Email</label>
                      <input type="email" class="form-control" id="replyTo" value="support@mystore.com">
                    </div>
                  </div>
                </div>

                <!-- Customer Notifications -->
                <div class="mb-4">
                  <h6 class="mb-3">Customer Notifications</h6>

                  <!-- Order Confirmation -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="orderConfirmation" checked>
                        <label class="form-check-label" for="orderConfirmation">
                          <strong>Order Confirmation</strong>
                        </label>
                      </div>
                      <i class="bx bx-package text-primary fs-4"></i>
                    </div>
                    <small class="text-muted">Send order confirmation emails to customers</small>
                  </div>

                  <!-- Shipping Confirmation -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shippingConfirmation" checked>
                        <label class="form-check-label" for="shippingConfirmation">
                          <strong>Shipping Confirmation</strong>
                        </label>
                      </div>
                      <i class="bx bx-truck text-primary fs-4"></i>
                    </div>
                    <small class="text-muted">Notify customers when orders are shipped</small>
                  </div>

                  <!-- Delivery Confirmation -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="deliveryConfirmation" checked>
                        <label class="form-check-label" for="deliveryConfirmation">
                          <strong>Delivery Confirmation</strong>
                        </label>
                      </div>
                      <i class="bx bx-check-circle text-primary fs-4"></i>
                    </div>
                    <small class="text-muted">Notify customers when orders are delivered</small>
                  </div>

                  <!-- Payment Failed -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="paymentFailed" checked>
                        <label class="form-check-label" for="paymentFailed">
                          <strong>Payment Failed</strong>
                        </label>
                      </div>
                      <i class="bx bx-x-circle text-primary fs-4"></i>
                    </div>
                    <small class="text-muted">Notify customers when payments fail</small>
                  </div>

                  <!-- Account Updates -->
                  <div class="border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="accountUpdates" checked>
                        <label class="form-check-label" for="accountUpdates">
                          <strong>Account Updates</strong>
                        </label>
                      </div>
                      <i class="bx bx-user text-primary fs-4"></i>
                    </div>
                    <small class="text-muted">Notify customers about account changes</small>
                  </div>
                </div>

                <!-- Admin Notifications -->
                <div class="mb-4">
                  <h6 class="mb-3">Admin Notifications</h6>

                  <!-- New Order -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="adminNewOrder" checked>
                        <label class="form-check-label" for="adminNewOrder">
                          <strong>New Order</strong>
                        </label>
                      </div>
                      <i class="bx bx-bell text-primary fs-4"></i>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-6">
                        <label for="adminOrderEmail" class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="adminOrderEmail" value="admin@mystore.com">
                      </div>
                      <div class="col-md-6">
                        <label for="adminOrderSms" class="form-label">SMS Number</label>
                        <input type="tel" class="form-control" id="adminOrderSms" placeholder="+1234567890">
                      </div>
                    </div>
                  </div>

                  <!-- Low Stock -->
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="lowStockAlert" checked>
                        <label class="form-check-label" for="lowStockAlert">
                          <strong>Low Stock Alert</strong>
                        </label>
                      </div>
                      <i class="bx bx-package text-warning fs-4"></i>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-6">
                        <label for="lowStockThreshold" class="form-label">Stock Threshold</label>
                        <input type="number" class="form-control" id="lowStockThreshold" value="10"
                          min="1">
                      </div>
                      <div class="col-md-6">
                        <label for="lowStockEmail" class="form-label">Alert Email</label>
                        <input type="email" class="form-control" id="lowStockEmail" value="inventory@mystore.com">
                      </div>
                    </div>
                  </div>

                  <!-- System Errors -->
                  <div class="border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="systemErrors" checked>
                        <label class="form-check-label" for="systemErrors">
                          <strong>System Errors</strong>
                        </label>
                      </div>
                      <i class="bx bx-error text-danger fs-4"></i>
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-12">
                        <label for="errorEmail" class="form-label">Error Report Email</label>
                        <input type="email" class="form-control" id="errorEmail" value="techsupport@mystore.com">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- SMS Notifications -->
                <div class="mb-4">
                  <h6 class="mb-3">SMS Notifications</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enableSms">
                        <label class="form-check-label" for="enableSms">
                          Enable SMS Notifications
                        </label>
                      </div>
                      <small class="text-muted">Send notifications via SMS</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="smsProvider" class="form-label">SMS Provider</label>
                      <select class="form-select" id="smsProvider">
                        <option value="twilio">Twilio</option>
                        <option value="nexmo">Nexmo</option>
                        <option value="aws-sns">AWS SNS</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="smsApiKey" class="form-label">API Key</label>
                      <input type="password" class="form-control" id="smsApiKey" placeholder="SMS API Key">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="smsFromNumber" class="form-label">From Number</label>
                      <input type="tel" class="form-control" id="smsFromNumber" placeholder="+1234567890">
                    </div>
                  </div>
                </div>

                <!-- Push Notifications -->
                <div class="mb-4">
                  <h6 class="mb-3">Push Notifications</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enablePush">
                        <label class="form-check-label" for="enablePush">
                          Enable Push Notifications
                        </label>
                      </div>
                      <small class="text-muted">Send browser push notifications</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="pushService" class="form-label">Push Service</label>
                      <select class="form-select" id="pushService">
                        <option value="firebase">Firebase Cloud Messaging</option>
                        <option value="onesignal">OneSignal</option>
                        <option value="pusher">Pusher</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="pushPublicKey" class="form-label">Public Key</label>
                      <textarea class="form-control" id="pushPublicKey" rows="2" placeholder="VAPID Public Key"></textarea>
                    </div>
                  </div>
                </div>

                <!-- Notification Templates -->
                <div class="mb-4">
                  <h6 class="mb-3">Notification Templates</h6>
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>Template Name</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Order Confirmation</td>
                          <td>Email</td>
                          <td><span class="badge bg-success">Active</span></td>
                          <td>
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-secondary">Preview</button>
                          </td>
                        </tr>
                        <tr>
                          <td>Shipping Update</td>
                          <td>Email + SMS</td>
                          <td><span class="badge bg-success">Active</span></td>
                          <td>
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-secondary">Preview</button>
                          </td>
                        </tr>
                        <tr>
                          <td>Low Stock Alert</td>
                          <td>Email</td>
                          <td><span class="badge bg-warning">Draft</span></td>
                          <td>
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-secondary">Preview</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <button class="btn btn-outline-primary btn-sm">Add New Template</button>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-outline-secondary me-2">Test Notification</button>
                  <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Save Changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Settings Sidebar -->
        <div class="col-lg-4">
          <!-- Notification Statistics -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Notification Statistics</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Total Sent:</span>
                  <span class="badge bg-primary">12,456</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>This Month:</span>
                  <span class="badge bg-success">1,234</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Email Sent:</span>
                  <span class="badge bg-info">10,234</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>SMS Sent:</span>
                  <span class="badge bg-warning">1,890</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Push Sent:</span>
                  <span class="badge bg-secondary">332</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Notifications -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Recent Notifications</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small>Order #1234 - Confirmed</small>
                  <span class="badge bg-success">Email</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small>Low Stock - Product XYZ</small>
                  <span class="badge bg-warning">Email</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small>Order #1235 - Shipped</small>
                  <span class="badge bg-info">SMS</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small>System Error - Payment</small>
                  <span class="badge bg-danger">Email</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Help & Support -->
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Help & Support</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="#" class="btn btn-outline-info">
                  <i class="bx bx-book me-2"></i>Notification Documentation
                </a>
                <a href="#" class="btn btn-outline-success">
                  <i class="bx bx-support me-2"></i>Get Support
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
