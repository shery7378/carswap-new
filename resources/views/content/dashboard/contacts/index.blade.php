@extends('layouts/contentNavbarLayout')

@section('title', __('Contact Us Requests'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="py-3 mb-0">
        <span class="text-muted fw-light">{{ __('App') }} /</span> {{ __('Contact Us') }}
    </h4>
</div>

<style>
    .table-responsive {
        overflow-x: auto !important;
    }
    .table th, .table td {
        padding: 0.55rem 0.5rem !important;
        font-size: 0.85rem !important;
        vertical-align: middle;
    }
    .table th {
        font-size: 0.75rem !important;
        text-transform: uppercase;
        letter-spacing: .5px;
        position: relative;
        padding-right: 30px !important;
        white-space: normal;
        line-height: 1.2;
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
        justify-content: flex-end;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">{{ __('Contact Requests') }}</h5>
    <!-- TABLE VIEW -->
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email Address') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($contact->subject, 30) }}</td>
                        <td>
                            @php
                                $statusClass = match($contact->status) {
                                    'unread' => 'bg-label-danger',
                                    'read' => 'bg-label-info',
                                    'replied' => 'bg-label-success',
                                    default => 'bg-label-secondary'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ __($contact->status) }}</span>
                        </td>
                        <td>{{ $contact->created_at->formatDateTime() }}</td>
                        <td class="text-end">
                            <div class="action-container">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-action view shadow-none" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action delete shadow-none delete-confirmation" data-bs-toggle="tooltip" title="{{ __('Delete') }}" data-confirm-text="{{ __('Are you sure you want to delete this request?') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">{{ __('No contact requests found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    <div class="card-footer">
        {{ $contacts->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
