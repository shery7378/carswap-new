@extends('layouts/contentNavbarLayout')

@section('title', __('Newsletter Subscribers'))

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ __('Newsletter') }} /</span> {{ __('Subscribers') }}</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible shadow-xs mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center py-3">
    <h5 class="mb-0 fw-bold">{{ __('Subscribers List') }}</h5>
  </div>
  <div class="card-body">
    <!-- TABLE VIEW -->
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle border-top" id="newsletter-table">
        <thead class="bg-light bg-opacity-50">
            <tr>
            <th>ID</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Subscribed At') }}</th>
            <th class="text-center">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($subscribers as $subscriber)
            <tr>
            <td>{{ $subscriber->id }}</td>
            <td>{{ $subscriber->name ?? __('N/A') }}</td>
            <td><strong>{{ $subscriber->email }}</strong></td>
            <td>{{ $subscriber->created_at->formatDateTime() }}</td>
            <td class="text-center">
                <div class="action-container">
                    <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-action delete delete-confirmation" 
                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                data-confirm-text="{{ __('Are you sure you want to delete this subscriber?') }}">
                            <i class="bx bx-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function () {
        $('#newsletter-table').DataTable({
            order: [[3, "desc"]],
            pageLength: 25,
            dom:
                "<'row align-items-center mb-3'<'col-md-6 d-flex align-items-center'l><'col-md-6 d-flex justify-content-end'f>>" +
                "t" +
                "<'row mt-3'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",
            language: {
                searchPlaceholder: "{{ __('Quick Search Newsletter Subscribers…') }}"
            },
            columnDefs: [
                { orderable: false, targets: [4] }
            ]
        });
    });
</script>

<style>
    /* Search box */
    .dataTables_filter input {
        width: 220px !important;
        border-radius: 8px;
        padding: 6px 10px;
        border: 1px solid #d9dee3;
    }

    /* Align header */
    .dataTables_wrapper .dataTables_filter {
        display: flex;
        justify-content: flex-end;
    }

    .dataTables_wrapper .dataTables_length {
        display: flex;
        align-items: center;
    }

    .dataTables_length select {
        padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
        border-radius: 6px !important;
        border: 1px solid #d9dee3 !important;
        min-width: 80px !important;
    }

    /* Pagination */
    .dataTables_paginate {
        display: flex;
        justify-content: flex-end;
    }

    /* Table header */
    #newsletter-table thead th {
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 0.6rem 0.5rem !important;
        position: relative;
        padding-right: 30px !important;
        white-space: normal;
        line-height: 1.2;
    }

    #newsletter-table tbody td {
        padding: 0.5rem 0.5rem !important;
        font-size: 0.82rem;
    }

    .table-responsive {
        overflow-x: auto !important;
    }

    /* Premium Action Buttons */
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        padding: 0 !important;
        border: none !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-action.edit { background-color: #e0f7fa !important; color: #00bcd4 !important; }
    .btn-action.delete { background-color: #ffebee !important; color: #f44336 !important; }
    .btn-action.view { background-color: #f3e5f5 !important; color: #9c27b0 !important; }
    .btn-action i { font-size: 1.15rem !important; }
    
    .action-container {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    /* Shadow */
    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    /* Mobile fix */
    @media (max-width: 768px) {
        .dataTables_filter {
            justify-content: start !important;
            margin-top: 10px;
        }
    }
</style>
@endsection
