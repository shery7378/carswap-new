@extends('layouts/contentNavbarLayout')

@section('title', 'Contact Request Details')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Contact Us /</span> View Request #{{ $contact->id }}
</h4>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                Request Details
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back
                </a>
            </h5>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Name:</div>
                    <div class="col-sm-9">{{ $contact->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Email:</div>
                    <div class="col-sm-9">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Phone:</div>
                    <div class="col-sm-9">{{ $contact->phone ?? 'N/A' }}</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Subject:</div>
                    <div class="col-sm-9 fw-bold">{{ $contact->subject }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 fw-bold mb-2">Message:</div>
                    <div class="col-sm-12 bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $contact->message }}</div>
                </div>
            </div>
            <div class="card-footer text-muted small">
                Submitted on: {{ $contact->created_at->format('M d, Y H:i:s') }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <h5 class="card-header">Manage Status</h5>
            <div class="card-body">
                <form action="{{ route('admin.contacts.update-status', $contact->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label" for="status">Current Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>

                <hr>

                <div class="mt-3">
                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this requests?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bx bx-trash me-1"></i> Delete Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">Quick Actions</h5>
            <div class="card-body">
                <a href="mailto:{{ $contact->email }}?subject=RE: {{ $contact->subject }}" class="btn btn-info w-100 mb-2">
                    <i class="bx bx-reply me-1"></i> Send Reply Email
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
