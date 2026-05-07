@extends('layouts/contentNavbarLayout')

@section('title', __('Contact Us Requests'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="py-3 mb-0">
        <span class="text-muted fw-light">{{ __('App') }} /</span> {{ __('Contact Us') }}
    </h4>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">{{ __('Contact Requests') }}</h5>
    <!-- ─── DESKTOP TABLE ─── -->
    <div class="table-responsive text-nowrap d-none d-md-block">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
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
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-icon btn-label-primary me-2 shadow-none" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger shadow-none delete-confirmation" data-bs-toggle="tooltip" title="{{ __('Delete') }}" data-confirm-text="{{ __('Are you sure you want to delete this request?') }}">
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

    <!-- ─── MOBILE CARD LIST ─── -->
    <div class="d-md-none p-3">
        @forelse($contacts as $contact)
            <div class="card mb-3 shadow-none border rounded-3 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $contact->name }}</h6>
                            <small class="text-muted">{{ $contact->email }}</small>
                        </div>
                        @php
                            $statusClass = match($contact->status) {
                                'unread' => 'bg-label-danger',
                                'read' => 'bg-label-info',
                                'replied' => 'bg-label-success',
                                default => 'bg-label-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ __($contact->status) }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block text-uppercase smaller fw-semibold">{{ __('Subject') }}</small>
                        <p class="mb-1 text-dark">{{ $contact->subject }}</p>
                        <small class="text-muted"><i class="bx bx-calendar me-1"></i>{{ $contact->created_at->formatDateTime() }}</small>
                    </div>
                    <div class="d-flex gap-2 border-top pt-2">
                        <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-label-primary flex-grow-1 shadow-none">
                            <i class="bx bx-show me-1"></i> {{ __('View') }}
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-label-danger w-100 shadow-none delete-confirmation" data-confirm-text="{{ __('Are you sure you want to delete this request?') }}">
                                <i class="bx bx-trash me-1"></i> {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                <i class="bx bx-envelope display-4 d-block mb-2"></i>
                {{ __('No contact requests found.') }}
            </div>
        @endforelse
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
