@extends('layouts/contentNavbarLayout')

@section('title', 'Email Template Manager')

@section('page-style')
<style>
    :root {
        --primary: #696cff;
        --primary-gradient: linear-gradient(135deg, #696cff 0%, #8487f5 100%);
        --primary-hover: #5f61e6;
        --surface: #ffffff;
        --background: #f5f5f9;
        --text-main: #32475c;
        --text-muted: #8592a3;
        --border-color: #d9dee3;
    }

    /* Overall Layout */
    .template-manager-wrapper {
        font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }

    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h2 {
        font-weight: 700;
        color: var(--text-main);
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Premium Cards */
    .premium-card {
        background: var(--surface);
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        padding: 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Sidebar Navigation */
    .template-nav-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 1.5rem;
        max-height: 550px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .template-nav-list::-webkit-scrollbar {
        width: 6px;
    }

    .template-nav-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .template-nav-list::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .template-nav-list::-webkit-scrollbar-thumb:hover {
        background: var(--text-muted);
    }

    .template-nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-radius: 12px;
        color: var(--text-main);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        background: transparent;
    }

    .template-nav-item:hover {
        background: rgba(105, 108, 255, 0.08);
        color: var(--primary);
        transform: translateX(4px);
    }

    .template-nav-item.active {
        background: var(--primary-gradient);
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(105, 108, 255, 0.4);
    }
    
    .template-nav-item.active i {
        color: #ffffff;
    }

    /* Editor Inputs */
    .modern-input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .modern-input-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .modern-input {
        width: 100%;
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 15px;
        color: var(--text-main);
        transition: all 0.3s ease;
        outline: none;
    }

    .modern-input:focus {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.15);
    }

    /* Editor Header & Tabs */
    .editor-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .glass-tabs {
        display: inline-flex;
        background: #f1f1f5;
        border-radius: 30px;
        padding: 4px;
    }

    .glass-tab-btn {
        background: transparent;
        border: none;
        border-radius: 30px;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .glass-tab-btn.active {
        background: #ffffff;
        color: var(--primary);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    /* Summernote Container */
    .editor-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #ebedf2;
        transition: border-color 0.3s ease;
    }
    
    .note-editor.note-frame {
        border: none !important;
    }
    
    .editor-wrapper:focus-within {
        border-color: var(--primary);
    }

    #email-body {
        width: 100%;
        min-height: 400px;
        border: none;
        padding: 20px;
        font-family: 'Fira Code', 'Consolas', monospace;
        font-size: 14px;
        color: var(--text-main);
        background: #fafafa;
        outline: none;
        resize: vertical;
    }

    /* Shortcodes */
    .shortcodes-section {
        margin-top: 2rem;
        background: #f8f9fc;
        border-radius: 16px;
        padding: 20px;
        border: 1px dashed #ced4da;
    }

    .shortcodes-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shortcode-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .shortcode-pill {
        background: #ffffff;
        color: var(--primary);
        border: 1px solid rgba(105, 108, 255, 0.2);
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
        display: inline-flex;
        align-items: center;
    }

    .shortcode-pill:hover {
        background: var(--primary-gradient);
        color: #ffffff;
        border-color: transparent;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(105, 108, 255, 0.3);
    }

    /* Buttons */
    .btn-gradient {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(105, 108, 255, 0.3);
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(105, 108, 255, 0.4);
        color: white;
    }

    /* Dev Settings Box */
    .dev-settings-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 template-manager-wrapper pt-4">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h2>
            <div class="avatar avatar-md bg-label-primary rounded d-flex align-items-center justify-content-center me-2">
                <i class="bx bx-mail-send fs-3"></i>
            </div>
            Email Templates
        </h2>
    </div>

    @if(session('success'))
        <div class="alert alert-solid-success alert-dismissible mb-4 rounded-3 shadow-sm d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 col-md-4">
            
            <!-- We link the main form early so sidebar buttons can use it -->
            <form action="{{ route('admin.email-templates.update', $selectedTemplate->id ?? 0) }}" method="POST" id="mainTemplateForm">
                @csrf
                @method('PUT')
            </form>

            <div class="premium-card mb-4" style="padding: 15px;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 px-3 mt-2" style="font-size: 11px; letter-spacing: 1px;">Categories</h6>
                <div class="template-nav-list">
                    @foreach($templates as $category => $categoryTemplates)
                        @foreach($categoryTemplates as $template)
                            <a href="{{ route('admin.email-templates.index', ['template' => $template->id]) }}" 
                               class="template-nav-item {{ isset($selectedTemplate) && $selectedTemplate->id == $template->id ? 'active' : '' }}">
                                <span>{{ $template->name }}</span>
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        @endforeach
                    @endforeach
                </div>
                
                <div class="px-2 pb-2">
                    <button type="submit" form="mainTemplateForm" class="btn-gradient">
                        <i class="bx bx-save"></i> Save Changes
                    </button>
                </div>
            </div>



        </div>

        <!-- Main Editor Area -->
        <div class="col-lg-9 col-md-8">
            @if(isset($selectedTemplate) && $selectedTemplate)
                <div class="premium-card">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="modern-input-group">
                                <label><i class="bx bx-tag-alt me-1"></i> Template Name</label>
                                <input type="text" name="name" form="mainTemplateForm" class="modern-input" value="{{ old('name', $selectedTemplate->name) }}" placeholder="e.g. Welcome Email" required>
                                @error('name') <span class="text-danger small mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="modern-input-group">
                                <label><i class="bx bx-text me-1"></i> Subject Line</label>
                                <input type="text" name="subject" form="mainTemplateForm" class="modern-input" value="{{ old('subject', $selectedTemplate->subject) }}" placeholder="e.g. Welcome to CarSwap!" required>
                                @error('subject') <span class="text-danger small mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="editor-header-bar">
                        <label class="text-uppercase text-muted fw-bold" style="font-size: 13px; letter-spacing: 0.5px;">
                            <i class="bx bx-edit-alt me-1"></i> Email Body
                        </label>
                        <div class="glass-tabs">
                            <button type="button" id="visual-btn" class="glass-tab-btn active">Visual</button>
                            <button type="button" id="code-btn" class="glass-tab-btn">Code</button>
                        </div>
                    </div>
                    
                    <div class="editor-wrapper">
                        <textarea name="body" id="email-body" form="mainTemplateForm">{{ old('body', $selectedTemplate->body) }}</textarea>
                    </div>
                    @error('body') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

                    <!-- Shortcodes -->
                    <div class="shortcodes-section">
                        <div class="shortcodes-title">
                            <i class="bx bx-code-curly text-primary fs-5"></i>
                            Dynamic Shortcodes
                        </div>
                        <p class="text-muted small mb-3">Click any pill below to instantly copy it to your clipboard. Paste it into the subject or body to display dynamic user data.</p>
                        
                        <div class="shortcode-pills">
                            @php 
                                $shortcodesStr = $selectedTemplate->shortcodes ?? '';
                                $displayShortcodes = is_array($shortcodesStr) ? $shortcodesStr : explode(',', $shortcodesStr);
                            @endphp
                            @foreach($displayShortcodes as $code)
                                @if(trim($code) != "")
                                <div class="shortcode-pill" onclick="copyToClipboard('[{{ trim($code) }}]')">
                                    [{{ trim($code) }}]
                                </div>
                                @endif
                            @endforeach
                            @if(empty($displayShortcodes) || count(array_filter($displayShortcodes, 'trim')) == 0)
                                <span class="text-muted small italic">No specific variables available for this template.</span>
                            @endif
                        </div>
                    </div>

                </div>
            @else
                <div class="premium-card text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 60vh;">
                    <div class="avatar avatar-xl bg-label-secondary rounded-circle mb-4">
                        <i class="bx bx-layout fs-1"></i>
                    </div>
                    <h3 class="fw-bold text-dark">No Template Selected</h3>
                    <p class="text-muted" style="max-width: 300px;">Please select an email template from the sidebar to begin customizing your emails.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('page-script')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visualBtn = document.getElementById('visual-btn');
        const codeBtn = document.getElementById('code-btn');
        const bodyTextarea = $('#email-body');

        if (bodyTextarea.length) {
            bodyTextarea.summernote({
                placeholder: 'Type your email content...',
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'image']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onChange: function(contents) {
                        bodyTextarea.val(contents);
                    }
                }
            });

            visualBtn.addEventListener('click', function() {
                bodyTextarea.summernote('codeview.deactivate');
                visualBtn.classList.add('active');
                codeBtn.classList.remove('active');
            });

            codeBtn.addEventListener('click', function() {
                bodyTextarea.summernote('codeview.activate');
                codeBtn.classList.add('active');
                visualBtn.classList.remove('active');
            });
        }
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.createElement('div');
            toast.style.position = 'fixed';
            toast.style.bottom = '40px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%) translateY(20px)';
            toast.style.backgroundColor = '#2c3338';
            toast.style.color = 'white';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '30px';
            toast.style.zIndex = '100000';
            toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
            toast.style.fontSize = '14px';
            toast.style.fontWeight = '600';
            toast.style.display = 'flex';
            toast.style.alignItems = 'center';
            toast.style.gap = '8px';
            toast.style.opacity = '0';
            toast.style.transition = 'all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1)';
            
            toast.innerHTML = `<i class='bx bx-check-circle fs-5 text-success'></i> Copied: ${text}`;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) translateY(0)';
            }, 10);

            // Animate out
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-20px)';
            }, 2500);
            
            setTimeout(() => toast.remove(), 2800);
        });
    }
</script>
@endsection
