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
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Actions') }}</th>
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
                            <span class="badge {{ $statusClass }}">{{ __(ucfirst($contact->status)) }}</span>
                        </td>
                        <td>{{ $contact->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="bx bx-show me-1"></i> {{ __('View') }}
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash me-1"></i> {{ __('Delete') }}
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
@endsection
