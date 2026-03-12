@extends('layouts/contentNavbarLayout')

@section('title', 'General Settings')

@section('content')
  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        <!-- General Settings Form -->
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">General Settings</h5>
            </div>
            <div class="card-body">
              <form id="generalSettingsForm">
                <!-- Store Information -->
                <div class="mb-4">
                  <h6 class="mb-3">Store Information</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="storeName" class="form-label">Store Name</label>
                      <input type="text" class="form-control" id="storeName" value="My eCommerce Store" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="storeEmail" class="form-label">Store Email</label>
                      <input type="email" class="form-control" id="storeEmail" value="store@example.com" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="storePhone" class="form-label">Store Phone</label>
                      <input type="tel" class="form-control" id="storePhone" value="+1 234 567 8900">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="storeWebsite" class="form-label">Store Website</label>
                      <input type="url" class="form-control" id="storeWebsite" value="https://mystore.com">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="storeAddress" class="form-label">Store Address</label>
                    <textarea class="form-control" id="storeAddress" rows="3">123 Main Street, City, State 12345</textarea>
                  </div>
                </div>

                <!-- Currency Settings -->
                <div class="mb-4">
                  <h6 class="mb-3">Currency Settings</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="defaultCurrency" class="form-label">Default Currency</label>
                      <select class="form-select" id="defaultCurrency">
                        <option value="USD" selected>USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                        <option value="JPY">JPY - Japanese Yen</option>
                        <option value="CAD">CAD - Canadian Dollar</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="currencyPosition" class="form-label">Currency Position</label>
                      <select class="form-select" id="currencyPosition">
                        <option value="left" selected>Left ($100.00)</option>
                        <option value="right">Right (100.00$)</option>
                        <option value="left_space">Left with space ($ 100.00)</option>
                        <option value="right_space">Right with space (100.00 $)</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="thousandSeparator" class="form-label">Thousand Separator</label>
                      <input type="text" class="form-control" id="thousandSeparator" value="," maxlength="1">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="decimalSeparator" class="form-label">Decimal Separator</label>
                      <input type="text" class="form-control" id="decimalSeparator" value="." maxlength="1">
                    </div>
                  </div>
                </div>

                <!-- Localization Settings -->
                <div class="mb-4">
                  <h6 class="mb-3">Localization</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="timezone" class="form-label">Timezone</label>
                      <select class="form-select" id="timezone">
                        <option value="UTC">UTC</option>
                        <option value="America/New_York" selected>America/New_York (EST)</option>
                        <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                        <option value="Europe/London">Europe/London (GMT)</option>
                        <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="dateFormat" class="form-label">Date Format</label>
                      <select class="form-select" id="dateFormat">
                        <option value="Y-m-d" selected>2024-01-15</option>
                        <option value="m/d/Y">01/15/2024</option>
                        <option value="d/m/Y">15/01/2024</option>
                        <option value="M d, Y">Jan 15, 2024</option>
                        <option value="F d, Y">January 15, 2024</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="language" class="form-label">Default Language</label>
                      <select class="form-select" id="language">
                        <option value="en" selected>English</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                        <option value="zh">Chinese</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="weightUnit" class="form-label">Weight Unit</label>
                      <select class="form-select" id="weightUnit">
                        <option value="kg" selected>Kilograms (kg)</option>
                        <option value="g">Grams (g)</option>
                        <option value="lbs">Pounds (lbs)</option>
                        <option value="oz">Ounces (oz)</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- SEO Settings -->
                <div class="mb-4">
                  <h6 class="mb-3">SEO Settings</h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="metaTitle" class="form-label">Default Meta Title</label>
                      <input type="text" class="form-control" id="metaTitle"
                        value="My eCommerce Store - Best Products Online">
                      <small class="text-muted">Default title for pages when no specific title is set</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="metaDescription" class="form-label">Default Meta Description</label>
                      <textarea class="form-control" id="metaDescription" rows="3">Shop the best products online at My eCommerce Store. Quality items, great prices, and fast shipping.</textarea>
                      <small class="text-muted">Default meta description for pages</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="metaKeywords" class="form-label">Default Meta Keywords</label>
                      <input type="text" class="form-control" id="metaKeywords"
                        value="ecommerce, online store, shopping, products">
                      <small class="text-muted">Comma-separated keywords</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="googleAnalytics" class="form-label">Google Analytics ID</label>
                      <input type="text" class="form-control" id="googleAnalytics" placeholder="GA_MEASUREMENT_ID">
                      <small class="text-muted">Google Analytics 4 Measurement ID</small>
                    </div>
                  </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="mb-4">
                  <h6 class="mb-3">Maintenance Mode</h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="maintenanceMode">
                        <label class="form-check-label" for="maintenanceMode">
                          Enable Maintenance Mode
                        </label>
                      </div>
                      <small class="text-muted">When enabled, visitors will see a maintenance page while administrators
                        can still access the site.</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="maintenanceMessage" class="form-label">Maintenance Message</label>
                      <textarea class="form-control" id="maintenanceMessage" rows="3">We are currently performing maintenance. We'll be back shortly!</textarea>
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end">
                  <button type="button" class="btn btn-outline-secondary me-2">Reset</button>
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
          <!-- Quick Actions -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <button class="btn btn-outline-primary">
                  <i class="bx bx-download me-2"></i>Export Settings
                </button>
                <button class="btn btn-outline-secondary">
                  <i class="bx bx-upload me-2"></i>Import Settings
                </button>
                <button class="btn btn-outline-warning">
                  <i class="bx bx-refresh me-2"></i>Clear Cache
                </button>
              </div>
            </div>
          </div>

          <!-- System Information -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">System Information</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>PHP Version:</span>
                  <span class="badge bg-primary">8.3.28</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Laravel Version:</span>
                  <span class="badge bg-success">12.32.0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Database:</span>
                  <span class="badge bg-info">MySQL</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Last Backup:</span>
                  <span class="badge bg-warning">2 days ago</span>
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
                  <i class="bx bx-book me-2"></i>Documentation
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
