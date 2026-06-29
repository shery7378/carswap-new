@extends('layouts/contentNavbarLayout')

@section('title', __('Manage') . ' ' . __($title))


@section('content')
@php
    $icons = [
        'brands' => 'bx-award',
        'models' => 'bx-car',
        'fuel-types' => 'bx-gas-pump',
        'transmissions' => 'bx-cog',
        'drive-types' => 'bx-trip',
        'body-types' => 'bx-shape-square',
        'exterior-colors' => 'bx-palette',
        'interior-colors' => 'bx-palette',
        'sales-methods' => 'bx-dollar-circle',
        'document-types' => 'bx-file',
        'vehicle-statuses' => 'bx-check-shield',
        'extra-features' => 'bx-list-plus',
        'extra-feature-categories' => 'bx-category'
    ];
    $icon = $icons[(string)$type] ?? 'bx-collection';
    $activeCount = $items->where('is_active', 1)->count();
    $inactiveCount = $items->where('is_active', 0)->count();
    $showImageField = in_array($type, ['brands', 'body-types']);
    $brandLogoPlaceholder = \App\Models\Brand::logoPlaceholder();
    $totalCols = 4;
    if ($showImageField) {
        $totalCols = 5;
    } elseif ($type === 'models' || $type === 'extra-features') {
        $totalCols = 5;
    }
    $transmissionParents = collect();
    $transmissionChildren = collect();
    if ($type === 'transmissions') {
        $transmissionParents = $items->whereNull('parent_id');
        $transmissionChildren = $items->whereNotNull('parent_id')->groupBy('parent_id');
    }
@endphp

<!-- TITLE & STATS -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
            <span class="avatar-initial rounded-circle bg-label-primary shadow-sm">
                <i class="bx {{ $icon }} fs-3"></i>
            </span>
        </div>
        <div>
            <h4 class="mb-0 fw-bold">{{ __($title) }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('Car Settings') }}</a></li>
                    <li class="breadcrumb-item active">{{ __($title) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mt-md-0">
        <div class="stat-badge d-flex align-items-center px-3 py-2 bg-white rounded shadow-xs border">
            <div class="badge bg-label-success rounded-circle p-2 me-2"><i class="bx bx-check"></i></div>
            <div>
                <small class="text-muted d-block line-height-1">{{ __('Active') }}</small>
                <span class="fw-bold stat-active-count">{{ $activeCount }}</span>
            </div>
        </div>
        <div class="stat-badge d-flex align-items-center px-3 py-2 bg-white rounded shadow-xs border">
            <div class="badge bg-label-warning rounded-circle p-2 me-2"><i class="bx bx-power-off"></i></div>
            <div>
                <small class="text-muted d-block line-height-1">{{ __('Inactive') }}</small>
                <span class="fw-bold stat-inactive-count">{{ $inactiveCount }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Form Side -->
    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4 border-0 shadow-sm premium-card overflow-hidden">
            <div class="card-header bg-primary text-white py-4 position-relative overflow-hidden">
                <div class="position-relative z-index-1">
                    <h5 class="card-title mb-1 text-white fw-bold">{{ __('Quick Addition') }}</h5>
                    <p class="mb-0 text-white-50 small">{{ __('Register a new') }} {{ __($title) }}.</p>
                </div>
                <i class="bx {{ $icon }} position-absolute" style="right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15;"></i>
            </div>
            <div class="card-body pt-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible shadow-xs mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-2 fs-4"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="addForm" action="{{ route('admin.vehicle-settings.store', $type) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase mb-2">{{ __('Display Name / Megnevezés') }}</label>
                        <div class="input-group input-group-merge shadow-none border-0">
                            <span class="input-group-text bg-light border-0"><i class="bx bx-rename"></i></span>
                            <input type="text" class="form-control bg-light border-0 px-3 py-2" name="name" id="add_name" required 
                                placeholder="e.g. {{ $type === 'brands' ? __('BMW') : ($type === 'fuel-types' ? __('Hybrid') : __('New Item')) }}">
                        </div>
                    </div>

                    @if($showImageField)
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase mb-2">{{ $type === 'brands' ? __('Brand Logo / Badge') : __('Body Type Icon / Image') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-image-alt"></i></span>
                                <input type="file" class="form-control bg-light border-0 px-3 py-2" name="image" id="add_image" accept="image/*">
                            </div>
                            <small class="text-muted mt-1 d-block">{{ __('Recommended: SVG or PNG with transparency') }}</small>
                        </div>
                    @endif

                    @if($type === 'models')
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase mb-2">{{ __('Link to Parent Brand') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-purchase-tag"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="brand_id" id="add_brand_id" required>
                                    <option value="">{{ __('Choose a brand...') }}</option>
                                    @foreach(DB::table('brands')->where('is_active', true)->orderBy('name')->get() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($type === 'extra-features')
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase mb-2">{{ __('Link to Parent Category') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-category"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="property_category_id" id="add_property_category_id" required>
                                    <option value="">{{ __('Choose a category...') }}</option>
                                    @foreach(DB::table('property_categories')->where('is_active', true)->orderBy('name')->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($type === 'transmissions')
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase mb-2">{{ __('Parent Category') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-category"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="parent_id" id="add_parent_id">
                                    <option value="">{{ __('No parent category') }}</option>
                                    @foreach($transmissionParents->sortBy('name') as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="mb-2 mt-4 pt-2">
                        <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center shadow-primary py-2 fw-bold" id="addBtn">
                            <span class="btn-loader d-none spinner-border spinner-border-sm me-2"></span>
                            <i class="bx bx-check-circle me-2 btn-icon"></i> {{ __('Register New Item') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light border-0 py-3 text-center">
                <small class="text-muted">{{ __('Changes take effect immediately across all active vehicle listings.') }}</small>
            </div>
        </div>
    </div>

    <!-- Table Side -->
    <div class="col-xl-8 col-lg-7">
        <div class="card border-0 shadow-sm relationships-card">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bx bx-list-ul me-2 text-primary"></i> 
                        {{ __('Registered') }} {{ __($title) }}
                    </h5>
                    <div id="table-search-placeholder"></div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- TABLE VIEW -->
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0" id="relationships-table">
                        <thead>
                            <tr class="bg-light">
                                <th class="ps-4">{{ __('ID') }}</th>
                                @if($showImageField)
                                    <th style="width: 50px;">{{ __('Icon') }}</th>
                                @endif
                                <th>{{ __('Name / Label') }}</th>
                                @if($type === 'models')
                                    <th>{{ __('Parent Brand') }}</th>
                                @endif
                                @if($type === 'extra-features')
                                    <th>{{ __('Parent Category') }}</th>
                                @endif
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center pe-4">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        
                        <tbody class="table-border-bottom-0">
                            @php
                                $renderItems = $type === 'transmissions' ? $transmissionParents : $items;
                            @endphp
                            @foreach($renderItems as $item)
                                @php $children = $type === 'transmissions' ? ($transmissionChildren[$item->id] ?? collect()) : collect(); @endphp
                                <tr class="transition-all hover-bg-light transmission-parent-row" data-id="{{ $item->id }}">
                                    <td class="ps-4"><span class="text-muted fw-semibold">#{{ $item->id }}</span></td>
                                    @if($showImageField)
                                        <td class="logo-cell">
                                            @if($type === 'brands')
                                                <img src="{{ \App\Models\Brand::logoUrl($item->name, $item->image ?? null) }}"
                                                     alt="{{ $item->name }}"
                                                     class="rounded shadow-xs brand-image-preview"
                                                     style="width: 32px; height: 32px; object-fit: contain; background: #f8f9fa; padding: 2px;"
                                                     onerror="this.onerror=null;this.src='{{ $brandLogoPlaceholder }}';">
                                            @elseif(!empty($item->image))
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="rounded shadow-xs brand-image-preview" style="width: 32px; height: 32px; object-fit: contain; background: #f8f9fa; padding: 2px;">
                                            @else
                                                <div class="avatar avatar-xs">
                                                    <span class="avatar-initial rounded bg-label-secondary small"><i class="bx bx-image-alt"></i></span>
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="name-cell">
                                        <div class="d-flex align-items-center">
                                            @if($type === 'transmissions' && $children->isNotEmpty())
                                                <button type="button" class="btn btn-sm p-0 me-2 transmission-collapse-toggle" data-parent="{{ $item->id }}" aria-expanded="true">
                                                    <i class="bx bx-chevron-down"></i>
                                                </button>
                                            @endif
                                            <div class="indicator badge rounded-pill bg-{{ $item->is_active ? 'success' : 'secondary' }} me-2 p-1"></div>
                                            <span class="fw-bold text-dark fs-6 item-name text-truncate">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    @if($type === 'models')
                                        <td class="brand-cell">
                                            @php $brand = DB::table('brands')->where('id', $item->brand_id)->first(); @endphp
                                            @if($brand)
                                                <span class="badge bg-label-primary px-3 rounded-pill brand-badge">
                                                    <i class="bx bx-award me-1 small"></i> {{ $brand->name }}
                                                </span>
                                            @else
                                                <span class="text-muted italic small">{{ __('Orphaned Brand') }}</span>
                                            @endif
                                        </td>
                                    @endif

                                    @if($type === 'extra-features')
                                        <td class="brand-cell">
                                            @php $category = DB::table('property_categories')->where('id', $item->property_category_id)->first(); @endphp
                                            @if($category)
                                                <span class="badge bg-label-info px-3 rounded-pill brand-badge">
                                                    <i class="bx bx-category me-1 small"></i> {{ $category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted italic small">{{ __('Orphaned Category') }}</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status-toggle-switch" type="checkbox" 
                                                data-id="{{ $item->id }}" 
                                                data-type="{{ $type }}"
                                                {{ ($item->is_active ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </td>                                     <td class="text-center pe-4">
                                         <div class="action-container">
                                             <button type="button" class="btn-action edit edit-btn"
                                                 data-id="{{ $item->id }}"
                                                 data-name="{{ $item->name }}"
                                                 @if($showImageField) data-image="{{ $type === 'brands' ? \App\Models\Brand::logoUrl($item->name, $item->image ?? null) : ($item->image ? asset('storage/' . $item->image) : '') }}" @endif
                                                 @if($type === 'models') data-brand="{{ $item->brand_id }}" @endif
                                                 @if($type === 'extra-features') data-category="{{ $item->property_category_id }}" @endif
                                                 @if($type === 'transmissions') data-parent="{{ $item->parent_id }}" @endif
                                                 data-bs-toggle="tooltip" title="{{ __('Edit Item') }}">
                                                 <i class="bx bx-edit-alt"></i>
                                             </button>
 
                                             <form action="{{ route('admin.vehicle-settings.destroy', [$type, $item->id]) }}"
                                                 method="POST" class="d-inline delete-form">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="button" class="btn-action delete delete-trigger"
                                                     data-bs-toggle="tooltip" title="{{ __('Delete Item') }}">
                                                     <i class="bx bx-trash"></i>
                                                 </button>
                                             </form>
                                         </div>
                                     </td>
                                </tr>
                                @if($type === 'transmissions')
                                    @foreach($children as $child)
                                        <tr class="transition-all hover-bg-light transmission-child-row" data-id="{{ $child->id }}" data-parent="{{ $item->id }}">
                                            <td class="ps-4"><span class="text-muted fw-semibold">#{{ $child->id }}</span></td>
                                            <td class="name-cell">
                                                <div class="d-flex align-items-center ps-4">
                                                    <span class="me-2 text-muted">↳</span>
                                                    <div class="indicator badge rounded-pill bg-{{ $child->is_active ? 'success' : 'secondary' }} me-2 p-1"></div>
                                                    <span class="fw-bold text-dark fs-6 item-name text-truncate">{{ $child->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input status-toggle-switch" type="checkbox"
                                                        data-id="{{ $child->id }}"
                                                        data-type="{{ $type }}"
                                                        {{ ($child->is_active ?? true) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="action-container">
                                                    <button type="button" class="btn-action edit edit-btn"
                                                        data-id="{{ $child->id }}"
                                                        data-name="{{ $child->name }}"
                                                        data-parent="{{ $child->parent_id }}"
                                                        data-bs-toggle="tooltip" title="{{ __('Edit Item') }}">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </button>
                                                    <form action="{{ route('admin.vehicle-settings.destroy', [$type, $child->id]) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-action delete delete-trigger"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete Item') }}">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade shadow-lg" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info py-3">
                <h5 class="modal-title fw-bold text-white"><i class="bx bx-edit-alt me-2"></i>{{ __('Edit') }} {{ Str::singular(__($title)) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">{{ __('Identification Label / Name') }}</label>
                        <input type="text" class="form-control border-light shadow-none bg-light" name="name" id="edit_name" required>
                    </div>

                    @if($showImageField)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">{{ __('Icon / Image') }}</label>
                            <div class="d-flex align-items-center mb-2" id="edit_image_preview_container">
                                <img src="" id="edit_image_preview" class="rounded me-3 d-none shadow-xs" style="width: 48px; height: 48px; object-fit: contain; background: #f8f9fa;">
                                <div id="edit_image_placeholder" class="avatar avatar-md me-3">
                                    <span class="avatar-initial rounded bg-label-secondary fs-4"><i class="bx bx-image-alt"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control border-light shadow-none bg-light" name="image" id="edit_image" accept="image/*">
                                    <small class="text-muted mt-1 d-block">{{ __('Leave empty to keep current image') }}</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($type === 'models')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">{{ __('Link to Parent Brand') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-purchase-tag"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="brand_id" id="edit_brand_id" required>
                                    <option value="">{{ __('Select Brand') }}</option>
                                    @foreach(DB::table('brands')->where('is_active', true)->orderBy('name')->get() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($type === 'extra-features')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">{{ __('Link to Parent Category') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-category"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="property_category_id" id="edit_property_category_id" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach(DB::table('property_categories')->where('is_active', true)->orderBy('name')->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($type === 'transmissions')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">{{ __('Parent Category') }}</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-category"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2 no-select2" name="parent_id" id="edit_parent_id">
                                    <option value="">{{ __('No parent category') }}</option>
                                    @foreach($transmissionParents->sortBy('name') as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-label-secondary px-4" data-bs-dismiss="modal">{{ __('Discard') }}</button>
                    <button type="submit" class="btn btn-info px-4 shadow-info fw-bold">{{ __('Update Item') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function() {
        const table = $('#relationships-table').DataTable({
            "order": [[0, "desc"]],
            "ordering": {{ $type === 'transmissions' ? 'false' : 'true' }},
            "pageLength": 10,
            "language": {
                "search": "",
                "searchPlaceholder": "{{ __('Quick Search :title…', ['title' => __($title)]) }}",
                "emptyTable": "<div class='text-center py-4'><div class='opacity-25 mb-2'><i class='bx bx-layers display-4 text-muted'></i></div><h6 class='text-muted fw-normal'>{{ __('No records found.') }}</h6></div>",
                "zeroRecords": "<div class='text-center py-4'><div class='opacity-25 mb-2'><i class='bx bx-layers display-4 text-muted'></i></div><h6 class='text-muted fw-normal'>{{ __('No records found.') }}</h6></div>"
            },
            "dom": '<"row mx-0 border-bottom bg-light bg-opacity-10"' +
                   '<"col-12 col-md-4 py-2"l>' +
                   '<"col-12 col-md-8 py-2 d-flex justify-content-md-end"f>' +
                   '>' +
                   '<"table-responsive relationships-table-responsive"t>' +
                   '<"row mx-0 px-2 py-2 bg-light bg-opacity-10 border-top d-flex align-items-center justify-content-between flex-wrap"' +
                   '<"col-12 col-md-auto mb-2 mb-md-0 text-center text-md-start"i>' +
                   '<"col-12 col-md-auto d-flex justify-content-center justify-content-md-end"p>' +
                   '>',
            "columnDefs": [
                { "orderable": false, "targets": [{{ $totalCols - 2 }}, {{ $totalCols - 1 }}] }
            ]
        });

        function updateStats() {
            const active = $('.status-toggle-switch:checked').length;
            const inactive = $('.status-toggle-switch:not(:checked)').length;
            $('.stat-active-count').text(active);
            $('.stat-inactive-count').text(inactive);
        }

        // Add Logic
        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const btn = $('#addBtn');
            const loader = btn.find('.btn-loader');
            const icon = btn.find('.btn-icon');
            
            btn.prop('disabled', true);
            loader.removeClass('d-none');
            icon.addClass('d-none');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: new FormData(form[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        const item = response.item;
                        const type = '{{ $type }}';
                        const brandLogoPlaceholder = @json($brandLogoPlaceholder);

                        if (type === 'transmissions') {
                            window.location.reload();
                            return;
                        }

                        let rowHtml = `
                            <tr class="transition-all hover-bg-light" data-id="${item.id}">
                                <td class="ps-4"><span class="text-muted fw-semibold">#${item.id}</span></td>`;

                        if (type === 'brands' || type === 'body-types') {
                            const logoSrc = type === 'brands'
                                ? (item.logo_url || brandLogoPlaceholder)
                                : (item.image ? `{{ asset('storage') }}/${item.image}` : '');
                            rowHtml += `
                                <td class="logo-cell">
                                    ${(type === 'brands' || item.image) ? `<img src="${logoSrc}" alt="${item.name}" class="rounded shadow-xs brand-image-preview" style="width: 32px; height: 32px; object-fit: contain; background: #f8f9fa; padding: 2px;" onerror="this.onerror=null;this.src='${brandLogoPlaceholder}';">` : `
                                        <div class="avatar avatar-xs">
                                            <span class="avatar-initial rounded bg-label-secondary small"><i class="bx bx-image-alt"></i></span>
                                        </div>
                                    `}
                                </td>`;
                        }

                        rowHtml += `
                                <td class="name-cell">
                                    <div class="d-flex align-items-center">
                                        <div class="indicator badge rounded-pill bg-success me-2 p-1"></div>
                                        <span class="fw-bold text-dark fs-6 item-name">${item.name}</span>
                                    </div>
                                </td>`;
                        
                        if (type === 'models') {
                            rowHtml += `
                                <td class="brand-cell">
                                    <span class="badge bg-label-primary px-3 rounded-pill brand-badge">
                                        <i class="bx bx-award me-1 small"></i> ${response.brand_name}
                                    </span>
                                </td>`;
                        }

                        if (type === 'extra-features') {
                            rowHtml += `
                                <td class="brand-cell">
                                    <span class="badge bg-label-info px-3 rounded-pill brand-badge">
                                        <i class="bx bx-category me-1 small"></i> ${response.category_name}
                                    </span>
                                </td>`;
                        }

                        rowHtml += `
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status-toggle-switch" type="checkbox" 
                                            data-id="${item.id}" data-type="${type}" checked>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="action-container">
                                        <button type="button" class="btn-action edit edit-btn"
                                            data-id="${item.id}" data-name="${item.name}" 
                                            ${(type === 'brands' || type === 'body-types') ? `data-image="${logoSrc || ''}"` : ''}
                                            ${type === 'models' ? `data-brand="${item.brand_id}"` : ''}
                                            ${type === 'extra-features' ? `data-category="${item.property_category_id}"` : ''}
                                            data-bs-toggle="tooltip" title="Edit Item">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        <form action="{{ url('/app/vehicle-settings') }}/${type}/${item.id}" method="POST" class="d-inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-action delete delete-trigger"
                                                data-bs-toggle="tooltip" title="Delete Item">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>`;

                        const rowNode = table.row.add($(rowHtml)).draw(false).node();
                        $(rowNode).addClass('transition-all hover-bg-light');
                        
                        form[0].reset();
                        toastr.success('{{ __('Item added successfully') }}');
                        updateStats();
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error adding item');
                },
                complete: function() {
                    btn.prop('disabled', false);
                    loader.addClass('d-none');
                    icon.removeClass('d-none');
                }
            });
        });

        // Edit Button Logic
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const brandId = $(this).data('brand');
            const categoryId = $(this).data('category');
            const parentId = $(this).data('parent');
            const image = $(this).data('image');
            const type = '{{ $type }}';
 
            $('#edit_name').val(name);
            $('#edit_brand_id').val(brandId);
            $('#edit_property_category_id').val(categoryId);
            $('#edit_parent_id').val(parentId || '');
            
            if (type === 'brands' || type === 'body-types') {
                if (image) {
                    $('#edit_image_preview').attr('src', image).removeClass('d-none');
                    $('#edit_image_placeholder').addClass('d-none');
                } else {
                    $('#edit_image_preview').addClass('d-none');
                    $('#edit_image_placeholder').removeClass('d-none');
                }
                $('#edit_image').val(''); // Clear file input
            }

            $('#editForm').attr('action', `{{ url('/app/vehicle-settings') }}/${type}/${id}`);
            
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });

        // Edit Submit Logic
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const type = '{{ $type }}';
            const brandLogoPlaceholder = @json($brandLogoPlaceholder);
            $.ajax({
                url: form.attr('action'),
                type: 'POST', // Blade method field handles PUT
                data: new FormData(form[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        const item = response.item;
                        const row = $(`tr[data-id="${item.id}"]`);

                        if (type === 'transmissions') {
                            window.location.reload();
                            return;
                        }
                        
                        row.find('.item-name').text(item.name);
                        
                        if (type === 'brands' || type === 'body-types') {
                            const logoSrc = type === 'brands'
                                ? (item.logo_url || brandLogoPlaceholder)
                                : (item.image ? `{{ asset('storage') }}/${item.image}` : '');

                            if (logoSrc) {
                                row.find('.logo-cell').html(`<img src="${logoSrc}" alt="${item.name}" class="rounded shadow-xs brand-image-preview" style="width: 32px; height: 32px; object-fit: contain; background: #f8f9fa; padding: 2px;" onerror="this.onerror=null;this.src='${brandLogoPlaceholder}';">`);
                            } else {
                                row.find('.logo-cell').html(`<div class="avatar avatar-xs"><span class="avatar-initial rounded bg-label-secondary small"><i class="bx bx-image-alt"></i></span></div>`);
                            }
                        }

                        if (response.brand_name) {
                            row.find('.brand-badge').html(`<i class="bx bx-award me-1 small"></i> ${response.brand_name}`);
                        }

                        if (response.category_name) {
                            row.find('.brand-badge').html(`<i class="bx bx-category me-1 small"></i> ${response.category_name}`);
                        }
                        
                        // Update data attributes on edit button
                        const editBtn = row.find('.edit-btn');
                        editBtn.data('name', item.name);
                        if (type === 'brands') {
                            editBtn.data('image', item.logo_url || brandLogoPlaceholder);
                        } else if (item.image) {
                            editBtn.data('image', `{{ asset('storage') }}/${item.image}`);
                        }
                        if (item.brand_id) editBtn.data('brand', item.brand_id);
                        if (item.property_category_id) editBtn.data('category', item.property_category_id);
                        if (item.parent_id) editBtn.data('parent', item.parent_id);

                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        toastr.success('{{ __('Item updated successfully') }}');
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error updating item');
                }
            });
        });

        // Status Toggle Switch
        $(document).on('change', '.status-toggle-switch', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const checked = $(this).prop('checked');
            const switchEl = $(this);
            const row = switchEl.closest('tr');
            
            $.ajax({
                url: `{{ url('/app/vehicle-settings') }}/${type}/${id}/toggle-status`,
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        row.find('.indicator').toggleClass('bg-success', response.is_active).toggleClass('bg-secondary', !response.is_active);
                        toastr.success(`{{ __('Status') }} ${response.is_active ? '{{ __('Activated') }}' : '{{ __('Deactivated') }}'}`, '{{ __('Success') }}');
                        updateStats();
                    }
                },
                error: function() {
                    switchEl.prop('checked', !checked);
                    toastr.error('Error', 'Unable to reach server');
                }
            });
        });

        $(document).on('click', '.transmission-collapse-toggle', function() {
            const btn = $(this);
            const parentId = btn.data('parent');
            const expanded = btn.attr('aria-expanded') === 'true';

            btn.attr('aria-expanded', expanded ? 'false' : 'true');
            btn.find('i').toggleClass('bx-chevron-down', !expanded).toggleClass('bx-chevron-right', expanded);
            $(`.transmission-child-row[data-parent="${parentId}"]`).toggleClass('d-none', expanded);
        });

        // Delete Logic
        $(document).on('click', '.delete-trigger', function() {
            const btn = $(this);
            const form = btn.closest('form');
            const row = btn.closest('tr');
            
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: "{{ __('Deleting this item may affect existing vehicle listings!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: '{{ __('Yes, delete it!') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                if ('{{ $type }}' === 'transmissions') {
                                    window.location.reload();
                                    return;
                                }
                                table.row(row).remove().draw(false);
                                toastr.success(response.message, 'Deleted');
                                updateStats();
                            }
                        },
                        error: function() {
                            toastr.error('Failed to delete item', 'Error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection

<style>
.premium-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.premium-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.modal-header {
    position: relative;
    padding-right: 50px !important;
}

/* Modal Close Button Static Premium Styling */
.modal-header .btn-close {
    background-color: #fff;
    opacity: 0.8; /* Slight opacity for the icon color look */
    padding: 0;
    margin: 0;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    filter: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    background-size: 14px;
    position: absolute;
    top: 8px;
    right: 8px;
    z-index: 100;
    transition: all 0.2s ease;
}
.modal-header .btn-close:hover {
    opacity: 1;
    background-color: #fff;
}
.shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.shadow-info { box-shadow: 0 4px 12px rgba(3, 195, 236, 0.3); }
.shadow-primary { box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3); }

.stat-badge {
    min-width: 130px;
}
.line-height-1 { line-height: 1; }

.relationships-card .dataTables_wrapper .dataTables_filter input {
    width: 250px !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem 1rem !important;
    border: 1px solid #d9dee3 !important;
    background: #fcfcfd;
}

.relationships-card .dataTables_length label {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
}

.relationships-card .table thead th {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #475569;
    padding: 0.75rem 0.5rem !important;
    position: relative;
    padding-right: 30px !important;
    white-space: normal;
    line-height: 1.2;
}
 


.relationships-card .table tbody td {
    padding: 0.5rem 0.5rem !important;
    font-size: 0.82rem;
}

.text-truncate {
    max-width: 100%;
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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
    justify-content: center;
}

.status-toggle-switch {
    width: 2.8rem !important;
    height: 1.4rem !important;
    cursor: pointer;
}

.hover-bg-light:hover {
    background-color: rgba(67, 89, 113, 0.02);
}

.indicator {
    width: 8px;
    height: 8px;
}

/* Perfect pagination sync */
.pagination {
    margin-bottom: 0;
}
.page-link {
    border-radius: 6px !important;
    margin: 0 2px;
}
.dataTables_info {
    font-size: 0.85rem;
    color: #8592a3;
    white-space: normal !important;
}
.dataTables_paginate {
    margin-top: 0.25rem;
}
@media (min-width: 768px) {
    .dataTables_paginate {
        margin-top: 0;
    }
}
/* Mobile Responsive & Sticky Header */
.relationships-table-responsive {
    overflow-x: auto;
    overflow-y: auto;
    max-height: 70vh;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.relationships-table-responsive::-webkit-scrollbar {
    width: 0;
    height: 0;
    background: transparent;
}
#relationships-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f8f9fa !important;
    box-shadow: 0 1px 0 #d9dee3;
}
@media (max-width: 767.98px) {
    .dataTables_length, .dataTables_filter {
        text-align: left !important;
        justify-content: flex-start !important;
    }
    .relationships-card .dataTables_length {
        margin-left: 0.80rem;
    }
    .relationships-card .dataTables_length label {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .dataTables_filter input {
        width: 100% !important;
        margin-left: 0 !important;
    }
}
.dataTables_empty {
    padding: 3rem 1.5rem !important;
    background-color: rgba(67, 89, 113, 0.01) !important;
    border-bottom: 0 !important;
}
</style>
@endsection
