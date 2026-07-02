@extends('layouts/contentNavbarLayout')

@section('title', __('Contact Request Details'))

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">{{ __('Contact Us') }} /</span> {{ __('View Request') }} #{{ $contact->id }}
</h4>

<div class="row">
    <div class="col-md-8">
        <!-- Sender Information -->
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                {{ __('Sender Information') }}
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('Back') }}
                </a>
            </h5>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-3 fw-bold">{{ __('Name') }}:</div>
                    <div class="col-sm-9">{{ $contact->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-3 fw-bold">{{ __('Email') }}:</div>
                    <div class="col-sm-9">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-3 fw-bold">{{ __('Phone') }}:</div>
                    <div class="col-sm-9">{{ $contact->phone ?? __('N/A') }}</div>
                </div>
            </div>
        </div>

        <!-- Request Details -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Request Details') }}</h5>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">{{ __('Subject') }}:</div>
                    <div class="col-sm-9 fw-bold">{{ $contact->subject }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 fw-bold mb-2">{{ __('Message') }}:</div>
                    <div class="col-sm-12 bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $contact->message }}</div>
                </div>
            </div>
            <div class="card-footer text-muted small">
                {{ __('Submitted on') }}: {{ $contact->created_at->formatDateTime() ?? $contact->created_at }}
            </div>
        </div>

        <!-- Related Vehicle -->
        @if($contact->vehicle)
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Related Vehicle') }}</h5>
            <div class="card-body">
                <div class="d-flex align-items-start">
                    @if($contact->vehicle->main_image)
                        <img src="{{ Storage::url($contact->vehicle->main_image) }}" alt="Thumbnail" class="rounded me-3" style="width: 120px; height: 90px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 90px;">
                            <i class="bx bx-car fs-2 text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-1">{{ $contact->vehicle->brand->name ?? '' }} {{ $contact->vehicle->vehicleModel->name ?? '' }}</h6>
                        <p class="mb-2 fw-bold">{{ $contact->vehicle->title }}</p>
                        <a href="{{ route('admin.vehicles.show', $contact->vehicle->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            <i class="bx bx-link-external me-1"></i> {{ __('View Vehicle') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Reply History -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Válasz előzmények') }}</h5>
            <div class="card-body">
                @forelse($contact->replies as $reply)
                    <div class="mb-3 border-bottom pb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>{{ __('Subject') }}: {{ $reply->subject }}</strong>
                            <small class="text-muted">{{ $reply->created_at->formatDateTime() ?? $reply->created_at }}</small>
                        </div>
                        <div class="bg-light p-2 rounded small text-break" style="white-space: pre-wrap;">{{ $reply->message }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">{{ __('No replies sent yet.') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Status History -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Állapot változások') }}</h5>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($contact->statusHistories as $history)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-secondary">{{ __($history->old_status ?? 'N/A') }}</span> 
                                <i class="bx bx-right-arrow-alt mx-1"></i> 
                                <span class="badge bg-primary">{{ __($history->new_status) }}</span>
                            </div>
                            <small class="text-muted">{{ $history->created_at->formatDateTime() ?? $history->created_at }}</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">{{ __('No status changes recorded.') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Manage Status') }}</h5>
            <div class="card-body">
                <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label" for="status">{{ __('Current Status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>{{ __('Unread') }}</option>
                            <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>{{ __('Read') }}</option>
                            <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>{{ __('Replied') }}</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">{{ __('Update Status') }}</button>
                </form>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">{{ __('Quick Reply') }}</h5>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Subject') }}</label>
                        <input type="text" name="subject" class="form-control" value="RE: {{ $contact->subject }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Message') }}</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="{{ __('Type your reply here...') }}" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-info w-100">
                        <i class="bx bx-send me-1"></i> {{ __('Send Reply') }}
                    </button>
                </form>

                <hr>

                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this request?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bx bx-trash me-1"></i> {{ __('Delete Request') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
