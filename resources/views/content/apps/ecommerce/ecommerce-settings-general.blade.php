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
              <h5 class="card-title mb-0">{{ __('General Settings') }}</h5>
            </div>
            <div class="card-body">
              <form id="generalSettingsForm" method="POST" action="{{ route('app-ecommerce-settings-general-store') }}" enctype="multipart/form-data">
                @csrf
                
                @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif
                
                <!-- Store Information -->
                <div class="mb-4 border-bottom pb-4">
                  <h6 class="mb-3">{{ __('Store Information') }}</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="storeName" class="form-label">{{ __('Store Name') }}</label>
                      <input type="text" class="form-control" name="storeName" id="storeName" value="{{ $settings['storeName'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="storeEmail" class="form-label">Bolt E-mail</label>
                      <input type="email" class="form-control" name="storeEmail" id="storeEmail" value="{{ $settings['storeEmail'] ?? '' }}" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="storePhone" class="form-label">Bolt telefonszám</label>
                      <input type="tel" class="form-control" name="storePhone" id="storePhone" value="{{ $settings['storePhone'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="storeWebsite" class="form-label">Bolt weboldal</label>
                      <input type="url" class="form-control" name="storeWebsite" id="storeWebsite" value="{{ $settings['storeWebsite'] ?? '' }}">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="storeAddress" class="form-label">Bolt cím</label>
                    <textarea class="form-control" name="storeAddress" id="storeAddress" rows="3">{{ $settings['storeAddress'] ?? '' }}</textarea>
                  </div>
                </div>

                <!-- Branding / Graphics -->
                <div class="mb-4 border-bottom pb-4">
                  <h6 class="mb-3">Márkaépítési lehetőségek</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="storeLogo" class="form-label">Bolt Logó (Elsődleges)</label>
                      <input class="form-control" type="file" name="storeLogo" id="storeLogo" accept="image/*">
                      @if(isset($settings['storeLogo']))
                        <div class="mt-2">
                          <img src="{{ asset('storage/' . $settings['storeLogo']) }}" alt="Store Logo" height="50">
                        </div>
                      @endif
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="storeFavicon" class="form-label">Favicon (Böngésző lap ikonra)</label>
                      <input class="form-control" type="file" name="storeFavicon" id="storeFavicon" accept="image/*">
                      @if(isset($settings['storeFavicon']))
                        <div class="mt-2 d-flex align-items-center">
                          <img src="{{ asset('storage/' . $settings['storeFavicon']) }}" alt="Favicon" width="32" height="32" class="border p-1 rounded">
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- Currency Settings -->
                <div class="mb-4 border-bottom pb-4">
                  <h6 class="mb-3">Pénznem beállítások</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="defaultCurrency" class="form-label">Alapértelmezett pénznem</label>
                      <select class="form-select" name="defaultCurrency" id="defaultCurrency">
                        <option value="Ft" {{ ($settings['defaultCurrency'] ?? 'Ft') == 'Ft' ? 'selected' : '' }}>Ft - Hungarian Forint</option>
                        <option value="USD" {{ ($settings['defaultCurrency'] ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ ($settings['defaultCurrency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ ($settings['defaultCurrency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="JPY" {{ ($settings['defaultCurrency'] ?? '') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                        <option value="CAD" {{ ($settings['defaultCurrency'] ?? '') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                        <option value="AUD" {{ ($settings['defaultCurrency'] ?? '') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="currencyPosition" class="form-label">Pénznem pozíciója</label>
                      <select class="form-select" name="currencyPosition" id="currencyPosition">
                        <option value="left" {{ ($settings['currencyPosition'] ?? '') == 'left' ? 'selected' : '' }}>Balra ($100.00)</option>
                        <option value="right" {{ ($settings['currencyPosition'] ?? '') == 'right' ? 'selected' : '' }}>Jobbra (100.00$)</option>
                        <option value="left_space" {{ ($settings['currencyPosition'] ?? '') == 'left_space' ? 'selected' : '' }}>Balra, szóközzel ($ 100.00)</option>
                        <option value="right_space" {{ ($settings['currencyPosition'] ?? 'right_space') == 'right_space' ? 'selected' : '' }}>Jobbra, szóközzel (100.00 $)</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="thousandSeparator" class="form-label">Ezres elválasztó</label>
                      <input type="text" class="form-control" name="thousandSeparator" id="thousandSeparator" value="{{ $settings['thousandSeparator'] ?? ' ' }}" maxlength="1">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="decimalSeparator" class="form-label">Tizedes elválasztó</label>
                      <input type="text" class="form-control" name="decimalSeparator" id="decimalSeparator" value="{{ $settings['decimalSeparator'] ?? ',' }}" maxlength="1">
                    </div>
                  </div>
                </div>

                <!-- Localization Settings -->
                <div class="mb-4 border-bottom pb-4">
                  <h6 class="mb-3">Lokalizáció</h6>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="timezone" class="form-label">Időzóna</label>
                      <select class="form-select" name="timezone" id="timezone">
                        <option value="Europe/Budapest" {{ ($settings['timezone'] ?? 'Europe/Budapest') == 'Europe/Budapest' ? 'selected' : '' }}>Európa/Budapest (CET/CEST)</option>
                        <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="Europe/London" {{ ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Európa/London (GMT)</option>
                        <option value="Asia/Tokyo" {{ ($settings['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>Ázsia/Tokió (JST)</option>
                        <option value="Asia/Dubai" {{ ($settings['timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Ázsia/Dubai (GST)</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="dateFormat" class="form-label">Dátumformátum</label>
                      <select class="form-select" name="dateFormat" id="dateFormat">
                        <option value="Y-m-d" {{ ($settings['dateFormat'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>2024-01-15 (Y-m-d)</option>
                        <option value="m/d/Y" {{ ($settings['dateFormat'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>01/15/2024 (m/d/Y)</option>
                        <option value="d/m/Y" {{ ($settings['dateFormat'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>15/01/2024 (d/m/Y)</option>
                        <option value="M d, Y" {{ ($settings['dateFormat'] ?? '') == 'M d, Y' ? 'selected' : '' }}>Jan 15, 2024 (M d, Y)</option>
                        <option value="F d, Y" {{ ($settings['dateFormat'] ?? '') == 'F d, Y' ? 'selected' : '' }}>January 15, 2024 (F d, Y)</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="language" class="form-label">Alapértelmezett nyelv</label>
                      <select class="form-select" name="language" id="language">
                        <option value="hu" {{ ($settings['language'] ?? 'hu') == 'hu' ? 'selected' : '' }}>Magyar</option>
                        <option value="en" {{ ($settings['language'] ?? '') == 'en' ? 'selected' : '' }}>Angol</option>
                        <option value="es" {{ ($settings['language'] ?? '') == 'es' ? 'selected' : '' }}>Spanyol</option>
                        <option value="fr" {{ ($settings['language'] ?? '') == 'fr' ? 'selected' : '' }}>Francia</option>
                        <option value="de" {{ ($settings['language'] ?? '') == 'de' ? 'selected' : '' }}>Német</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- SEO Settings -->
                <div class="mb-4 border-bottom pb-4">
                  <h6 class="mb-3">SEO Beállítások elemre</h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="metaTitle" class="form-label">Alapértelmezett metacím</label>
                      <input type="text" class="form-control" name="metaTitle" id="metaTitle"
                        value="{{ $settings['metaTitle'] ?? '' }}">
                      <small class="text-muted">Oldalak alapértelmezett címe, ha nincs beállítva konkrét cím</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="metaDescription" class="form-label">Alapértelmezett meta leírás</label>
                      <textarea class="form-control" name="metaDescription" id="metaDescription" rows="3">{{ $settings['metaDescription'] ?? '' }}</textarea>
                      <small class="text-muted">Oldalak alapértelmezett meta leírása, ha nincs beállítva konkrét leírás</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="metaKeywords" class="form-label">Alapértelmezett meta kulcsszavak</label>
                      <input type="text" class="form-control" name="metaKeywords" id="metaKeywords"
                        value="{{ $settings['metaKeywords'] ?? '' }}">
                      <small class="text-muted">Vesszővel elválasztott kulcsszavak</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="googleAnalytics" class="form-label">Google Analytics azonosító</label>
                      <input type="text" class="form-control" name="googleAnalytics" id="googleAnalytics" value="{{ $settings['googleAnalytics'] ?? '' }}" placeholder="G-XXXXXXXXXX">
                      <small class="text-muted">Google Analytics 4 Mérés azonosító</small>
                    </div>
                  </div>
                </div>

                <!-- Maintenance Mode -->
                <div class="mb-4 pb-2">
                  <h6 class="mb-3">Karbantartási mód</h6>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="maintenanceMode" id="maintenanceMode" value="1" {{ ($settings['maintenanceMode'] ?? '0') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="maintenanceMode">
                          Karbantartási mód engedélyezése
                        </label>
                      </div>
                      <small class="text-muted">Ha engedélyezve van, a látogatók egy karbantartási oldalt fognak látni, míg az adminisztrátorok továbbra is hozzáférhetnek a webhelyhez.</small>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label for="maintenanceMessage" class="form-label">Karbantartási üzenet</label>
                      <textarea class="form-control" name="maintenanceMessage" id="maintenanceMessage" rows="3">{{ $settings['maintenanceMessage'] ?? "We are currently performing maintenance. We'll be back shortly!" }}</textarea>
                    </div>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end">
                  <button type="reset" class="btn btn-outline-secondary me-2">{{ __('Cancel') }}</button>
                  <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> {{ __('Save changes') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Settings Sidebar -->
        <div class="col-lg-4">
          <!-- System Information -->
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">Rendszerinformációk</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>PHP Változat:</span>
                  <span class="badge bg-primary">8.3.28</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Laravel Változat:</span>
                  <span class="badge bg-success">12.32.0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Database:</span>
                  <span class="badge bg-info">MySQL</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Utolsó biztonsági mentés:</span>
                  <span class="badge bg-warning">2 napja</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
