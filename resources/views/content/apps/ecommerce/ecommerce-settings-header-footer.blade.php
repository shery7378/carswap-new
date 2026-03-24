@extends('layouts/contentNavbarLayout')

@section('title', 'Header & Footer Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Header & Footer Settings</h5>
                    <small class="text-muted float-end">Advanced WordPress Style Styling</small>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('app-ecommerce-settings-header-footer-store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Appearance Section -->
                        <div class="mb-5 p-3 border rounded shadow-sm bg-light-subtle">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-palette me-1"></i> Style & Appearance
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Header BG Color</label>
                                    <input type="color" name="header_bg_color" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['header_bg_color'] ?? '#ffffff' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Header Text Color</label>
                                    <input type="color" name="header_text_color" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['header_text_color'] ?? '#333333' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Footer BG Color</label>
                                    <input type="color" name="footer_bg_color" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_bg_color'] ?? '#000000' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Footer Text Color</label>
                                    <input type="color" name="footer_text_color" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_text_color'] ?? '#ffffff' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Primary Theme Color</label>
                                    <input type="color" name="theme_primary_color" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['theme_primary_color'] ?? '#696cff' }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Global Font Size (px)</label>
                                    <input type="number" name="body_font_size" class="form-control shadow-sm"
                                        value="{{ $settings['body_font_size'] ?? '16' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Global Font Family</label>
                                    <select name="body_font_family" class="form-select shadow-sm">
                                        <option value="Inter" {{ (isset($settings['body_font_family']) && $settings['body_font_family'] == 'Inter') ? 'selected' : '' }}>Inter</option>
                                        <option value="Roboto" {{ (isset($settings['body_font_family']) && $settings['body_font_family'] == 'Roboto') ? 'selected' : '' }}>Roboto</option>
                                        <option value="Outfit" {{ (isset($settings['body_font_family']) && $settings['body_font_family'] == 'Outfit') ? 'selected' : '' }}>Outfit</option>
                                        <option value="Public Sans" {{ (isset($settings['body_font_family']) && $settings['body_font_family'] == 'Public Sans') ? 'selected' : '' }}>Public Sans</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Footer Styling -->
                        <div class="mb-5 p-3 border rounded shadow-sm bg-light-subtle">
                            <h6 class="fw-bold mb-3 text-secondary"><i class="bx bx-paint-roll me-1"></i> Footer Advanced Styling
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mailing List Card BG</label>
                                    <input type="color" name="footer_mailing_list_bg" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_mailing_list_bg'] ?? '#ffffff' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mailing List Button</label>
                                    <input type="color" name="footer_mailing_list_btn_bg" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_mailing_list_btn_bg'] ?? '#D2B48C' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mailing List Btn Text</label>
                                    <input type="color" name="footer_mailing_list_btn_text" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_mailing_list_btn_text'] ?? '#ffffff' }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Bottom Bar BG</label>
                                    <input type="color" name="footer_bottom_bar_bg" class="form-control form-control-color w-100 shadow-sm"
                                        value="{{ $settings['footer_bottom_bar_bg'] ?? '#000000' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Footer Widget Title Font Size (px)</label>
                                    <input type="number" name="footer_widget_title_font_size" class="form-control shadow-sm"
                                        value="{{ $settings['footer_widget_title_font_size'] ?? '18' }}" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Footer Content Font Size (px)</label>
                                    <input type="number" name="footer_text_font_size" class="form-control shadow-sm"
                                        value="{{ $settings['footer_text_font_size'] ?? '14' }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Header Section -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-heading me-1"></i> Header Settings</h6>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="header_logo">Header Logo</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        <img src="{{ isset($settings['header_logo']) ? asset($settings['header_logo']) : asset('assets/img/avatars/1.png') }}"
                                            alt="header-logo" class="d-block rounded bg-dark p-2" height="60"
                                            id="headerLogoPreview" />
                                        <div class="button-wrapper">
                                            <input type="file" id="header_logo" name="header_logo" class="form-control shadow-sm"
                                                accept="image/png, image/jpeg" />
                                            <div class="text-muted small mt-1">Allowed JPG, GIF or PNG. Max 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="header_sticky">Sticky Header</label>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="header_sticky"
                                            name="header_sticky" value="1"
                                            {{ (isset($settings['header_sticky']) && $settings['header_sticky']) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="header_contact_number">Contact Number</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control shadow-sm" id="header_contact_number"
                                        name="header_contact_number"
                                        value="{{ $settings['header_contact_number'] ?? '' }}"
                                        placeholder="+1 234 567 8900" />
                                </div>
                                <label class="col-sm-2 col-form-label" for="header_email">Email Address</label>
                                <div class="col-sm-4">
                                    <input type="email" class="form-control shadow-sm" id="header_email" name="header_email"
                                        value="{{ $settings['header_email'] ?? '' }}" placeholder="info@carswap.com" />
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-share-alt me-1"></i> Social Media Links
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="social_facebook">Facebook</label>
                                    <div class="input-group input-group-merge shadow-sm">
                                        <span class="input-group-text"><i class="bx bxl-facebook"></i></span>
                                        <input type="text" name="social_facebook"
                                            class="form-control" value="{{ $settings['social_facebook'] ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="social_instagram">Instagram</label>
                                    <div class="input-group input-group-merge shadow-sm">
                                        <span class="input-group-text"><i class="bx bxl-instagram"></i></span>
                                        <input type="text" name="social_instagram"
                                            class="form-control" value="{{ $settings['social_instagram'] ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="social_linkedin">LinkedIn</label>
                                    <div class="input-group input-group-merge shadow-sm">
                                        <span class="input-group-text"><i class="bx bxl-linkedin"></i></span>
                                        <input type="text" name="social_linkedin"
                                            class="form-control" value="{{ $settings['social_linkedin'] ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="social_youtube">YouTube</label>
                                    <div class="input-group input-group-merge shadow-sm">
                                        <span class="input-group-text"><i class="bx bxl-youtube"></i></span>
                                        <input type="text" name="social_youtube"
                                            class="form-control" value="{{ $settings['social_youtube'] ?? '' }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mailing List Section -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-envelope me-1"></i> Footer Mailing List
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_mailing_list_title">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm" name="footer_mailing_list_title"
                                        value="{{ $settings['footer_mailing_list_title'] ?? 'Join our mailing list!' }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"
                                    for="footer_mailing_list_description">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control shadow-sm" name="footer_mailing_list_description"
                                        rows="2">{{ $settings['footer_mailing_list_description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-dock-bottom me-1"></i> Footer Core Settings
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_logo">Footer Logo</label>
                                <div class="col-sm-10">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        <img src="{{ isset($settings['footer_logo']) ? asset($settings['footer_logo']) : asset('assets/img/avatars/1.png') }}"
                                            alt="footer-logo" class="d-block rounded bg-dark p-2" height="60"
                                            id="footerLogoPreview" />
                                        <div class="button-wrapper">
                                            <input type="file" id="footer_logo" name="footer_logo" class="form-control shadow-sm"
                                                accept="image/png, image/jpeg" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"
                                    for="footer_description">About/Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control shadow-sm" name="footer_description"
                                        rows="3">{{ $settings['footer_description'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_address">Office Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm" name="footer_address"
                                        value="{{ $settings['footer_address'] ?? '' }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_phone">Footer Phone</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control shadow-sm" name="footer_phone"
                                        value="{{ $settings['footer_phone'] ?? '' }}" />
                                </div>
                                <label class="col-sm-2 col-form-label" for="footer_email">Footer Email</label>
                                <div class="col-sm-4">
                                    <input type="email" class="form-control shadow-sm" name="footer_email"
                                        value="{{ $settings['footer_email'] ?? '' }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_copyright">Copyright Text</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm"
                                        name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Footer Links Section -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-link me-1"></i> Footer Links (URLs)
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Contact URL</label>
                                    <input type="text" name="footer_link_contact"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_contact'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">FAQ URL</label>
                                    <input type="text" name="footer_link_faq" class="form-control shadow-sm"
                                        value="{{ $settings['footer_link_faq'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Useful Tips URL</label>
                                    <input type="text" name="footer_link_tips"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_tips'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Our Offer URL</label>
                                    <input type="text" name="footer_link_offer"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_offer'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Partners URL</label>
                                    <input type="text" name="footer_link_partners"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_partners'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Admins URL</label>
                                    <input type="text" name="footer_link_admins"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_admins'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Subscriptions URL</label>
                                    <input type="text" name="footer_link_subscriptions"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_subscriptions'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Terms URL</label>
                                    <input type="text" name="footer_link_terms"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_terms'] ?? '' }}" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Privacy Policy URL</label>
                                    <input type="text" name="footer_link_privacy"
                                        class="form-control shadow-sm" value="{{ $settings['footer_link_privacy'] ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10 text-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <i class="bx bx-save me-1"></i> UPDATE ALL SETTINGS
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function (e) {
        let headerLogo = document.getElementById('header_logo');
        let footerLogo = document.getElementById('footer_logo');
        
        if (headerLogo) {
            headerLogo.onchange = evt => {
                const [file] = headerLogo.files;
                if (file) {
                    document.getElementById('headerLogoPreview').src = URL.createObjectURL(file);
                }
            }
        }
        
        if (footerLogo) {
            footerLogo.onchange = evt => {
                const [file] = footerLogo.files;
                if (file) {
                    document.getElementById('footerLogoPreview').src = URL.createObjectURL(file);
                }
            }
        }
    });
</script>
@endsection
@endsection
