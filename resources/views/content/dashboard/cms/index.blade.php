@extends('layouts/contentNavbarLayout')

@section('title', 'CMS Management')

@section('page-style')
<style>
    /* Minimalist CMS Dashboard Styling */
    .cms-card {
        background: #ffffff;
        border: 1px solid #eef0f7;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .cms-card:hover {
        border-color: #696cff;
        box-shadow: 0 10px 20px -10px rgba(105, 108, 255, 0.15);
    }

    .cms-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f8f9ff;
        background: #ffffff;
    }

    .cms-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f0f2ff;
        color: #696cff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .cms-card-header.legal-section .cms-card-icon {
        background: #fff0ed;
        color: #ff3e1d;
    }

    .cms-item-count {
        font-size: 0.8rem;
        font-weight: 500;
        color: #acb1c6;
    }

    .cms-btn-primary {
        background: #696cff;
        color: #fff;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1rem;
        border: none;
    }

    .cms-btn-secondary {
        background: #f0f2ff;
        color: #696cff;
        border-radius: 8px;
        font-weight: 600;
        border: none;
    }

    .cms-btn-secondary:hover {
        background: #696cff;
        color: #fff;
    }

    .cms-status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    .status-active { background: #71dd37; }
    .status-inactive { background: #e0e2e8; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h4 class="mb-1 fw-bold text-dark">Website Sections</h4>
        <p class="text-muted small mb-0">Control the dynamic content and legal notices across your platform.</p>
    </div>
    <a href="{{ route('admin.cms.create') }}" class="btn cms-btn-primary d-flex align-items-center gap-2">
        <i class="bx bx-plus"></i> New Section
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center border-0 shadow-none bg-label-success mb-5" role="alert">
        <i class="bx bx-check me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    @forelse($sections as $section)
        @php
            $isLegal = in_array($section->slug, ['general-terms-and-conditions', 'data-protection-notice']);
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 cms-card shadow-none">
                <div class="cms-card-header {{ $isLegal ? 'legal-section' : '' }}">
                    <div class="cms-card-icon">
                        <i class="bx {{ $isLegal ? 'bx-shield-alt-2' : 'bx-layer' }} fs-4"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0 text-dark">{{ $section->name }}</h6>
                        <div class="d-flex align-items-center">
                            <span class="cms-status-indicator {{ $section->status ? 'status-active' : 'status-inactive' }}"></span>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $section->status ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                    <small class="cms-item-count">{{ $section->items_count }} components included</small>
                </div>
                <div class="card-body p-4 pt-3">
                    <p class="text-muted small mb-4" style="line-height: 1.6; height: 3.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $section->description ?: 'No description provided.' }}
                    </p>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.cms.edit', $section->id) }}" class="btn cms-btn-secondary flex-grow-1">
                            Modify Content
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-light text-muted p-2 border-0 shadow-none" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                <li>
                                    <form action="{{ route('admin.cms.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Remove section?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="bx bx-trash me-2"></i> Delete Section
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No sections found.</p>
        </div>
    @endforelse
</div>
@endsection
