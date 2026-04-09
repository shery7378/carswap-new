@extends('layouts/contentNavbarLayout')

@section('title', 'Footer Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Footer Settings</h5>
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
                        

                        <!-- Mailing List Section -->
                        <div class="mb-5" id="mailing-list">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-envelope me-1"></i> Mailing List Content
                            </h6>
                            <hr>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_mailing_list_title">Main Heading</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm" name="footer_mailing_list_title"
                                        value="{{ $settings['footer_mailing_list_title'] ?? '' }}" placeholder="Csatlakozz a levelezőlistánkhoz!" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_mailing_list_subtitle">Subheading</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm" name="footer_mailing_list_subtitle"
                                        value="{{ $settings['footer_mailing_list_subtitle'] ?? '' }}" placeholder="Join our mailing list! Stay updated with our latest offers." />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="footer_mailing_list_note">Bottom Note</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control shadow-sm" name="footer_mailing_list_note"
                                        value="{{ $settings['footer_mailing_list_note'] ?? '' }}" placeholder="Hetente néhány levél, semmi felesleges." />
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10 text-end">
                                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                                    <i class="bx bx-save me-1"></i> SAVE CHANGES
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
    // Preview script removed as logos are removed
</script>
@endsection
@endsection
