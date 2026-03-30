<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<!-- Core CSS (includes Bootstrap and theme styles) -->
<link rel="stylesheet" href="{{ asset('assets/vendor/scss/core.css') }}">

<!-- Fonts Icons -->
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify/iconify.css') }}">

<!-- Demo CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

<!-- Vendor Styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .toast-success { background-color: #696cff !important; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4) !important; border-radius: 8px !important; }
    .toast-error { background-color: #ff3e1d !important; box-shadow: 0 4px 12px rgba(255, 62, 29, 0.4) !important; border-radius: 8px !important; }
    
    /* Select2 Premium Styling */
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        min-height: calc(1.53em + 0.844rem + 2px);
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        color: #697a8d;
        padding-left: 0.875rem;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #d9dee3;
        box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
    }
</style>
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<!-- END: app CSS-->
