@extends('layouts/contentNavbarLayout')

@section('title', 'Notification Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header with Stats Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-none border-0 overflow-hidden" 
                 style="background: linear-gradient(135deg, #696cff 0%, #3f4191 100%) !important;">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h4 class="fw-bold mb-1 text-white">Email Server Configuration</h4>
                        <p class="mb-0 opacity-75">Configure and monitor your system-wide email delivery service.</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bx bx-mail-send fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="notificationSettingsForm">
        <div class="row g-4">
            <!-- Left Column: Master Connection -->
            <div class="col-lg-7">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                        <div class="avatar avatar-sm me-3 bg-label-primary p-2">
                            <i class="bx bx-server"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">SMTP Connection</h5>
                            <small class="text-muted">Protocol & authentication settings</small>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Delivery Protocol</label>
                                <div class="d-flex gap-3 mt-1 mb-2">
                                    <div class="form-check custom-radio">
                                        <input name="mail_mailer" class="form-check-input" type="radio" value="smtp" id="protocolSmtp" checked>
                                        <label class="form-check-label" for="protocolSmtp">SMTP (Recommended)</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input name="mail_mailer" class="form-check-input" type="radio" value="mailgun" id="protocolMailgun">
                                        <label class="form-check-label" for="protocolMailgun">Mailgun</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label for="mail_host" class="form-label">SMTP Host</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-globe"></i></span>
                                    <input type="text" class="form-control" name="mail_host" id="mail_host" 
                                           placeholder="smtp.example.com" value="{{ $settings['mail_host'] ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="mail_port" class="form-label">Port</label>
                                <input type="number" class="form-control text-center" name="mail_port" id="mail_port" 
                                       placeholder="587" value="{{ $settings['mail_port'] ?? '587' }}">
                            </div>

                            <div class="col-md-12">
                                <label for="mail_encryption" class="form-label">Security Protocol</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="mail_encryption" id="enc-tls" value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="enc-tls">TLS</label>
                                    
                                    <input type="radio" class="btn-check" name="mail_encryption" id="enc-ssl" value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>
                                    <label class="btn btn-outline-primary" for="enc-ssl">SSL</label>
                                    
                                    <input type="radio" class="btn-check" name="mail_encryption" id="enc-none" value="none" {{ ($settings['mail_encryption'] ?? '') == 'none' ? 'selected' : '' }}>
                                    <label class="btn btn-outline-primary" for="enc-none">NONE</label>
                                </div>
                            </div>

                            <div class="col-md-6 mt-4">
                                <label for="mail_username" class="form-label">Auth Username</label>
                                <input type="text" class="form-control" name="mail_username" id="mail_username" 
                                       placeholder="Username" value="{{ $settings['mail_username'] ?? '' }}">
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label for="mail_password" class="form-label">Auth Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="mail_password" id="mail_password" 
                                           placeholder="••••••••" value="{{ $settings['mail_password'] ?? '' }}">
                                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Identity & Activation -->
            <div class="col-lg-5">
                <div class="row g-4">
                    <!-- Status Switch -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3 bg-label-success p-2">
                                        <i class="bx bx-power-off"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Service Status</h6>
                                        <small class="text-muted">Global switch for emails</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="enable_email_notifications" 
                                           id="enableEmailNotifications" {{ ($settings['enable_email_notifications'] ?? '1') == '1' ? 'checked' : '' }} 
                                           style="width: 2.8rem; height: 1.4rem;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- From Configuration -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm overflow-hidden h-100">
                             <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                                <div class="avatar avatar-sm me-3 bg-label-info p-2">
                                    <i class="bx bx-user-circle"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Sender Identity</h5>
                                    <small class="text-muted">How users see your emails</small>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <div class="mb-3">
                                    <label for="mail_from_name" class="form-label">Display Name</label>
                                    <input type="text" class="form-control" name="mail_from_name" id="mail_from_name" 
                                           value="{{ $settings['mail_from_name'] ?? 'My eCommerce Store' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="mail_from_address" class="form-label">Sender Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" class="form-control" name="mail_from_address" id="mail_from_address" 
                                               value="{{ $settings['mail_from_address'] ?? 'noreply@mystore.com' }}">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label for="mail_reply_to" class="form-label">Reply-To Address</label>
                                    <input type="email" class="form-control bg-light" name="mail_reply_to" id="mail_reply_to" 
                                           value="{{ $settings['mail_reply_to'] ?? '' }}" placeholder="Default: Same as sender">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5 text-end">
                <div class="d-flex align-items-center justify-content-end p-3 bg-lighter rounded">
                    <div class="d-flex gap-2">
                        <button type="button" id="testConnectivityBtn" class="btn btn-outline-secondary waves-effect d-flex align-items-center">
                            <i class="bx bx-test-tube me-2"></i> Test Connectivity
                        </button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center shadow-primary px-5">
                            <i class="bx bx-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .bg-lighter { background-color: #f8f9fa; }
    .shadow-primary { box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4); }
    .card { transition: all 0.2s ease; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .form-control:focus { border-color: #696cff !important; box-shadow: 0 0 0 0.2rem rgba(105,108,255,.1) !important; }
    .btn-group .btn-check:checked + .btn-outline-primary { background-color: #696cff; color: #fff; border-color: #696cff; }
</style>
@endsection


@section('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#testConnectivityBtn').on('click', function() {
        let formData = $('#notificationSettingsForm').serialize();
        let testBtn = $(this);
        let originalText = testBtn.html();
        
        testBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Testing...');
        
        $.ajax({
            url: "{{ route('app-ecommerce-settings-notifications-test') }}",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(response.message || 'Connection successful!');
            },
            error: function(xhr) {
                let error = xhr.responseJSON ? xhr.responseJSON.message : 'Connection failed';
                toastr.error(error);
            },
            complete: function() {
                testBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    $('#notificationSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        let submitBtn = $(this).find('button[type="submit"]');
        let originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
        
        $.ajax({
            url: "{{ route('app-ecommerce-settings-notifications-store') }}",
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(response.message || 'Settings saved successfully!');
            },
            error: function(xhr) {
                let error = xhr.responseJSON ? xhr.responseJSON.message : 'Something went wrong';
                toastr.error(error);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Toggle Password Visibility
    $('#togglePassword').on('click', function() {
        const input = $('#mail_password');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bx-hide').addClass('bx-show');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bx-show').addClass('bx-hide');
        }
    });
});
</script>
@endsection
