@extends('layouts/contentNavbarLayout')

@section('title', __('Contact Request Details'))

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">{{ __('Contact Us') }} /</span> {{ __('View Request') }} #{{ $contact->id }}
</h4>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                {{ __('Request Details') }}
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('Back') }}
                </a>
            </h5>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">{{ __('Name') }}:</div>
                    <div class="col-sm-9">{{ $contact->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">{{ __('Email') }}:</div>
                    <div class="col-sm-9">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">{{ __('Phone') }}:</div>
                    <div class="col-sm-9">{{ $contact->phone ?? __('N/A') }}</div>
                </div>
                <hr>
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
                {{ __('Submitted on') }}: {{ $contact->created_at->format('Y-m-d H:i:s') }}
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
