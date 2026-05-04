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
            <td>{{ $subscriber->created_at->format('Y-m-d H:i') }}</td>
            <td class="text-center">
                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this subscriber?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger shadow-none" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                        <i class="bx bx-trash"></i>
                    </button>
                </form>
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
                search: "",
                searchPlaceholder: "{{ __('Search subscribers...') }}",
                paginate: {
                    next: '<i class="bx bx-chevron-right"></i>',
                    previous: '<i class="bx bx-chevron-left"></i>'
                },
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                zeroRecords: "{{ __('No matching records found') }}"
            }
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
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
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
