@extends('layouts/contentNavbarLayout')

@section('title', __('CMS Editor - ') . $section->name)

@section('page-style')
	    <style>
	        .date-picker-btn {
	            width: 46px;
	            min-width: 46px;
	            display: inline-flex;
	            align-items: center;
	            justify-content: center;
	            padding: 0;
	            background: #fff;
	            border-color: #d9dee3;
	            border-top-left-radius: 0;
	            border-bottom-left-radius: 0;
	            border-top-right-radius: 0.375rem;
	            border-bottom-right-radius: 0.375rem;
	            line-height: 1;
	        }

	        .date-picker-btn i {
	            font-size: 1.1rem;
	            margin: 0;
	        }

	        .ymd-picker-group {
	            border-radius: 0.375rem;
	            overflow: hidden;
	        }

	        .ymd-picker-group .form-control {
	            border-top-right-radius: 0;
	            border-bottom-right-radius: 0;
	            border-right: 0;
	        }

	        .ymd-picker-group .date-picker-btn {
	            border-top-right-radius: 0;
	            border-bottom-right-radius: 0;
	            border-left: 0;
	        }

	        .edit-panel {
	            background: #ffffff;
	            border: 1px solid #eef0f7;
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .nav-breadcrumb {
            font-size: 0.85rem;
            color: #acb1c6;
        }

        .nav-breadcrumb a {
            color: #696cff;
            text-decoration: none;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #32325d;
        }

        .btn-save {
            background: #696cff;
            color: #fff;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #5f61e6;
            color: #fff;
            transform: translateY(-1px);
        }

        .component-card {
            background: #fcfdfe;
            border: 1px solid #f0f2ff;
            border-radius: 8px;
            padding: 1.25rem;
        }

        .component-card:hover {
            border-color: #696cff;
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
        .btn-action i { font-size: 1.15rem !important; }

        /* Hide TinyMCE Promotion & Branding */
        .tox-promotion, .tox-statusbar__branding {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    @php
        $isDocumentMode = in_array($section->slug, ['general-terms-and-conditions', 'data-protection-notice', 'home-hero']);
        $isFaqSection = in_array($section->slug, ['faq', 'faq-section']);

        // Dynamic item label based on section slug
        $itemLabel = match ($section->slug) {
            'faq-section', 'faq' => __('FAQ elem'),
            'mailing-list', 'mailing-list-info' => __('Rács elem'),
            'home-services' => __('Szolgáltatás elem'),
            'home-headings' => __('Címsor elem'),
            'blog-posts' => __('Blog elem'),
            'general-terms-and-conditions', 'data-protection-notice' => __('Dokumentum elem'),
            default => __('FAQ elem'),
        };
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <div class="nav-breadcrumb mb-1"><a href="{{ route('admin.cms.index') }}">@lang('CMS Dashboard')</a> / @lang('Editor')</div>
            <h3 class="section-title mb-0">{{ $section->name }}</h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary px-4">@lang('Cancel')</a>
            <button type="submit" form="section-main-form" class="btn btn-save shadow-sm">@lang('Update Section')</button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card edit-panel border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <i class="bx bx-cog text-primary fs-4 me-2"></i>
                        <h6 class="mb-0 fw-bold">@lang('Configuration')</h6>
                    </div>
                    <form id="section-main-form" action="{{ route('admin.cms.update', $section->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">@lang('Internal Name')</label>
                            <input type="text" name="name" class="form-control border-1" value="{{ $section->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">@lang('Web Slug')</label>
                            <input type="text" name="slug" class="form-control border-1 bg-light"
                                value="{{ $section->slug }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">@lang('Status')</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $section->status ? 'selected' : '' }}>@lang('Publicly Visible')</option>
                                <option value="0" {{ !$section->status ? 'selected' : '' }}>@lang('Hidden / Draft')</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right column: Main Editor -->
        <div class="col-md-8">
            <div class="card edit-panel border-1 shadow-none">
                @if($isDocumentMode)
                    <div class="card-header bg-light-template border-bottom p-4">
                        <div class="d-flex align-items-center">
                            <i class="bx bxs-file-doc text-warning fs-3 me-2"></i>
                            <h6 class="mb-0 fw-bold">@lang('Professional Document Editor')</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.cms.items.update-direct') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="section_id" value="{{ $section->id }}">
                            @php $mainItem = $section->items->first(); @endphp
                            <input type="hidden" name="item_id" value="{{ $mainItem->id ?? '' }}">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">@lang('Icon Class (e.g. bx-car)')</label>
                                    <input type="text" name="icon" class="form-control" value="{{ $mainItem->icon ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">@lang('Main Image Overlay')</label>
                                    <input type="file" name="image" class="form-control">
                                    @if(isset($mainItem) && $mainItem->image)
                                        <small class="text-success mt-1 d-block"><i class='bx bx-check-circle'></i> @lang('Image is attached')</small>
                                    @endif
                                </div>
                            </div>

                            <div class="doc-editor-wrapper mb-4 border rounded">
                                <textarea id="document-editor" name="description"
                                    class="form-control border-0">{{ $mainItem->description ?? '' }}</textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-save px-5">
                                    @lang('Save Document Content')
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-header p-4 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list-ul text-primary fs-3 me-2"></i>
                            <h6 class="mb-0 fw-bold">@lang('Section Components')</h6>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addItemModal">
                            @lang('Add New')
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($section->items->sortBy('order') as $item)
                                <div class="col-12">
                                    <div class="component-card d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-label-primary p-2 rounded me-3">
                                                @if($item->icon)
                                                    <i class="bx {{ $item->icon }} fs-4"></i>
                                                @else
                                                    <i class="bx bx-circle fs-4"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $item->title }}</h6>
                                                <small class="text-muted">@lang('Index: ') {{ $item->order }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn-action edit edit-item"
                                                data-item="{{ json_encode($item) }}" data-bs-toggle="modal"
                                                data-bs-target="#editItemModal" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                                                <i class="icon-base bx bx-edit-alt"></i>
                                            </button>
                                            <form action="{{ route('admin.cms.items.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action delete"
                                                    onclick="return confirm('{{ __('Remove?') }}')" title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}">
                                                    <i class="icon-base bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted mb-0">@lang('No components defined.')</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin.cms.items.store', $section->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add :item', ['item' => $itemLabel]) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">@lang('Item Title')</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('e.g. Easy Exchange') }}" required>
                    </div>
                        @if (!$isFaqSection)
	                        <div class="mb-3">
	                            <label class="form-label">@lang('Date (Optional)')</label>
	                            <div class="input-group position-relative ymd-picker-group">
	                                <input type="text" class="form-control" id="cms-item-date-display" placeholder="yyyy/mm/dd" readonly>
	                                <button class="btn btn-outline-secondary date-picker-btn" type="button" id="cms-item-date-btn" aria-label="Open date picker">
	                                    <i class="bx bx-calendar"></i>
	                                </button>
	                                <input type="date" name="date" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;" id="cms-item-date-picker">
	                            </div>
	                        </div>
                        @endif
                    <div class="mb-3">
                        <label class="form-label">@lang('Description')</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
	                    <div class="row">
                            @if (!$isFaqSection && $section->id != 13)
	                            <div class="col-md-6 mb-3">
	                                <label class="form-label">@lang('Icon Class (Boxicons)')</label>
	                                <input type="text" name="icon" class="form-control" placeholder="bx-car">
	                            </div>
                                <div class="col-md-6 mb-3">
                            @elseif (!$isFaqSection)
                                <div class="col-md-6 mb-3">
                            @else
                                <div class="col-md-12 mb-3">
                            @endif
	                            <label class="form-label">@lang('Display Order')</label>
	                            <input type="number" name="order" class="form-control" value="0">
	                            @if ($section->id == 13)
	                                <small class="text-muted">Lower numbers appear first on the page.</small>
	                            @endif
	                        </div>
	                    </div>
                        @if (!$isFaqSection)
	                        <div class="mb-3">
	                            <label class="form-label">@lang('Custom Image (Optional)')</label>
	                            <input type="file" name="image" class="form-control">
	                        </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save :item', ['item' => $itemLabel]) }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal (Simple implementation) -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="editItemForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit :item', ['item' => $itemLabel]) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">@lang('Item Title')</label>
                        <input type="text" id="edit-item-title" name="title" class="form-control" required>
                    </div>
                        @if (!$isFaqSection)
	                        <div class="mb-3">
	                            <label class="form-label">@lang('Date (Optional)')</label>
	                            <div class="input-group position-relative ymd-picker-group">
	                                <input type="text" class="form-control" id="edit-item-date-display" placeholder="yyyy/mm/dd" readonly>
	                                <button class="btn btn-outline-secondary date-picker-btn" type="button" id="edit-item-date-btn" aria-label="Open date picker">
	                                    <i class="bx bx-calendar"></i>
	                                </button>
	                                <input type="date" id="edit-item-date" name="date" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor:pointer;">
	                            </div>
	                        </div>
                        @endif
                    <div class="mb-3">
                        <label class="form-label">@lang('Description')</label>
                        <textarea id="edit-item-description" name="description" class="form-control" rows="3"
                            required></textarea>
                    </div>
	                    <div class="row">
                            @if (!$isFaqSection && $section->id != 13)
	                            <div class="col-md-6 mb-3">
	                                <label class="form-label">@lang('Icon Class')</label>
	                                <input type="text" id="edit-item-icon" name="icon" class="form-control">
	                            </div>
                                <div class="col-md-6 mb-3">
                            @elseif (!$isFaqSection)
                                <div class="col-md-6 mb-3">
                            @else
                                <div class="col-md-12 mb-3">
                            @endif
	                            <label class="form-label">@lang('Display Order')</label>
	                            <input type="number" id="edit-item-order" name="order" class="form-control">
	                            @if ($section->id == 13)
	                                <small class="text-muted">Lower numbers appear first on the page.</small>
	                            @endif
	                        </div>
	                    </div>
                        @if (!$isFaqSection)
	                        <div class="mb-3">
	                            <label class="form-label">@lang('Custom Image (Optional)')</label>
	                            <input type="file" id="edit-item-image" name="image" class="form-control">
	                            <small class="text-muted">@lang('Leave empty to keep current image.')</small>
	                        </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update :item', ['item' => $itemLabel]) }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('page-script')
    <!-- TinyMCE Rich Text Editor -->
    @php
        $tinyMceKey = \App\Models\Setting::where('key', 'tinymce_api_key')->first()?->value ?? 'no-api-key';
    @endphp
    <script src="https://cdn.tiny.cloud/1/{{ $tinyMceKey }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

	    <script>
	        document.addEventListener('DOMContentLoaded', function () {
	            function formatToYmdSlashes(value) {
	                if (!value) return '';
	                const parts = value.split('-');
	                if (parts.length !== 3) return value;
	                return `${parts[0]}/${parts[1]}/${parts[2]}`;
	            }
	
	            function wireYmdDatePicker(pickerEl, displayEl, buttonEl) {
	                if (!pickerEl || !displayEl || !buttonEl) return;
	
	                function syncFromPicker() {
	                    displayEl.value = formatToYmdSlashes(pickerEl.value);
	                }
	
	                buttonEl.addEventListener('click', function () {
	                    if (pickerEl.showPicker) pickerEl.showPicker();
	                    else {
	                        pickerEl.focus();
	                        pickerEl.click();
	                    }
	                });
	
	                pickerEl.addEventListener('change', syncFromPicker);
	                syncFromPicker();
	            }
	
	            // Add item modal date
	            wireYmdDatePicker(
	                document.getElementById('cms-item-date-picker'),
	                document.getElementById('cms-item-date-display'),
	                document.getElementById('cms-item-date-btn')
	            );
	
	            // Edit item modal date
	            wireYmdDatePicker(
	                document.getElementById('edit-item-date'),
	                document.getElementById('edit-item-date-display'),
	                document.getElementById('edit-item-date-btn')
	            );

	            // Shared TinyMCE Init
	            function initTinyMCE(selector) {
	                tinymce.init({
                        selector: selector,
                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                        height: 350,
                        promotion: false,
                        branding: false,
                        license_key: 'gpl'
                    });
                }

            // Apply only if the sections were specifically intended as legal/long document sections
            @if(in_array($section->slug, ['general-terms-and-conditions', 'data-protection-notice', 'home-hero']))
                // Apply TinyMCE
                initTinyMCE('#edit-item-description');
                initTinyMCE('#document-editor');
                initTinyMCE('textarea[name="description"]'); // For the add item modal
            @endif

            document.querySelectorAll('.edit-item').forEach(button => {
                button.addEventListener('click', function () {
                    const item = JSON.parse(this.dataset.item);
                    const form = document.querySelector('#editItemForm');
                    form.action = `/app/cms/items/${item.id}`;

                    document.querySelector('#edit-item-title').value = item.title;
	                    if (document.querySelector('#edit-item-date')) {
	                        document.querySelector('#edit-item-date').value = item.date ? item.date.split('T')[0] : '';
	                        const display = document.querySelector('#edit-item-date-display');
	                        if (display) display.value = formatToYmdSlashes(document.querySelector('#edit-item-date').value);
	                    }
                    if (tinymce.get('edit-item-description')) {
                        tinymce.get('edit-item-description').setContent(item.description || '');
                    } else {
                        document.querySelector('#edit-item-description').value = item.description;
                    }

                    if (document.querySelector('#edit-item-icon')) {
                        document.querySelector('#edit-item-icon').value = item.icon || '';
                    }
                    document.querySelector('#edit-item-order').value = item.order || 0;
                });
            });
        });
    </script>
@endsection
