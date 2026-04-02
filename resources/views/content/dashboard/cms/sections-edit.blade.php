@extends('layouts/contentNavbarLayout')

@section('title', 'CMS Editor - ' . $section->name)

@section('page-style')
    <style>
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
    </style>
@endsection

@section('content')
    @php
        $isDocumentMode = in_array($section->slug, ['general-terms-and-conditions', 'data-protection-notice', 'home-hero']);
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-5 mt-2">
        <div>
            <div class="nav-breadcrumb mb-1"><a href="{{ route('admin.cms.index') }}">CMS Dashboard</a> / Editor</div>
            <h3 class="section-title mb-0">{{ $section->name }}</h3>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            <button type="submit" form="section-main-form" class="btn btn-save shadow-sm">Update Section</button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card edit-panel border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <i class="bx bx-cog text-primary fs-4 me-2"></i>
                        <h6 class="mb-0 fw-bold">Configuration</h6>
                    </div>
                    <form id="section-main-form" action="{{ route('admin.cms.update', $section->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Internal Name</label>
                            <input type="text" name="name" class="form-control border-1" value="{{ $section->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Web Slug</label>
                            <input type="text" name="slug" class="form-control border-1 bg-light"
                                value="{{ $section->slug }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $section->status ? 'selected' : '' }}>Publicly Visible</option>
                                <option value="0" {{ !$section->status ? 'selected' : '' }}>Hidden / Draft</option>
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
                            <h6 class="mb-0 fw-bold">Professional Document Editor</h6>
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
                                    <label class="form-label text-muted small fw-bold text-uppercase">Icon Class (e.g.
                                        bx-car)</label>
                                    <input type="text" name="icon" class="form-control" value="{{ $mainItem->icon ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Main Image Overlay</label>
                                    <input type="file" name="image" class="form-control">
                                    @if(isset($mainItem) && $mainItem->image)
                                        <small class="text-success mt-1 d-block"><i class='bx bx-check-circle'></i> Image is
                                            attached</small>
                                    @endif
                                </div>
                            </div>

                            <div class="doc-editor-wrapper mb-4 border rounded">
                                <textarea id="document-editor" name="description"
                                    class="form-control border-0">{{ $mainItem->description ?? '' }}</textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-save px-5">
                                    Save Document Content
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-header p-4 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-list-ul text-primary fs-3 me-2"></i>
                            <h6 class="mb-0 fw-bold">Section Components</h6>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal"
                            data-bs-target="#addItemModal">
                            Add New
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
                                                <small class="text-muted">Index: {{ $item->order }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-icon btn-outline-light text-muted border-1 edit-item"
                                                data-item="{{ json_encode($item) }}" data-bs-toggle="modal"
                                                data-bs-target="#editItemModal">
                                                <i class="bx bx-edit-alt"></i>
                                            </button>
                                            <form action="{{ route('admin.cms.items.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-outline-light text-danger border-1"
                                                    onclick="return confirm('Remove?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted mb-0">No components defined.</p>
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
                    <h5 class="modal-title">Add Grid Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Easy Exchange" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Icon Class (Boxicons)</label>
                            <input type="text" name="icon" class="form-control" placeholder="bx-car">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Custom Image (Optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
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
                    <h5 class="modal-title">Edit Grid Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Item Title</label>
                        <input type="text" id="edit-item-title" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit-item-description" name="description" class="form-control" rows="3"
                            required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" id="edit-item-icon" name="icon" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" id="edit-item-order" name="order" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Custom Image (Optional)</label>
                        <input type="file" id="edit-item-image" name="image" class="form-control">
                        <small class="text-muted">Leave empty to keep current image.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('page-script')
    <script src="https://cdn.tiny.cloud/1/{{ $tinymce_api_key ?? 'no-api-key' }}/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Shared TinyMCE Init for both modal areas
            function initTinyMCE(selector) {
                tinymce.init({
                    selector: selector,
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
                    menubar: false,
                    height: 350,
                    skin: 'oxide',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            }

            // Apply only if the sections were specifically intended as legal/long document sections
            @if(in_array($section->slug, ['general-terms-and-conditions', 'data-protection-notice', 'home-hero']))
                // Apply TinyMCE to the Main Item Description
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
                    const descField = document.querySelector('#edit-item-description');

                    if (tinymce.get('edit-item-description')) {
                        tinymce.get('edit-item-description').setContent(item.description || '');
                    } else {
                        descField.value = item.description;
                    }

                    document.querySelector('#edit-item-icon').value = item.icon || '';
                    document.querySelector('#edit-item-order').value = item.order || 0;
                });
            });
        });
    </script>
@endsection