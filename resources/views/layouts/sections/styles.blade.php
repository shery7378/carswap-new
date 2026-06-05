<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Core CSS (includes Bootstrap and theme styles) -->
<link rel="stylesheet" href="{{ asset('assets/vendor/scss/core.css') }}">

<!-- Fonts Icons -->
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify/iconify.css') }}">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
        z-index: 1085;
    }
    .select2-container--open {
        z-index: 1085;
    }
    .swal2-container {
        z-index: 2000 !important;
    }
    /* Global Premium Pagination Styling */
    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 3px;
        border: none !important;
        background-color: #f0f2f5;
        color: #566a7f;
        padding: 6px 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
    }
    .pagination .page-item.active .page-link {
        background-color: #696cff !important;
        color: #fff !important;
        box-shadow: 0 3px 8px rgba(105, 108, 255, 0.3) !important;
    }
    .pagination .page-item:not(.active) .page-link:hover {
        background-color: #e2e5e9;
        color: #696cff;
    }
    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        color: #c4ccd4;
        opacity: 0.7;
    }
    .dataTables_info {
        font-size: 0.82rem;
        color: #697a8d;
        font-weight: 500;
        margin-bottom: 12px;
    }

    /* Mobile Responsive Optimizations for Pagination */
    @media (max-width: 767.98px) {
        .pagination .page-item .page-link {
            padding: 6px 10px !important;
            min-width: 32px !important;
            margin: 0 2px !important;
            font-size: 0.78rem !important;
        }
        .dataTables_wrapper .row {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 5px;
            padding: 10px 0 !important;
            margin: 0 !important;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            justify-content: flex-start !important;
            text-align: left !important;
            width: 100% !important;
            padding: 0 !important;
            margin-bottom: 8px !important;
        }
        .dataTables_wrapper .dataTables_paginate .pagination {
            justify-content: flex-start !important;
            flex-wrap: wrap !important;
        }
    }
</style>
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<!-- END: app CSS-->
