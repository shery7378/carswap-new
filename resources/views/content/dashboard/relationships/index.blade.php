@extends('layouts/contentNavbarLayout')

@section('title', 'Manage ' . $title)

@section('vendor-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<style>
    .toast-success { background-color: #696cff !important; box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4) !important; border-radius: 8px !important; }
    .toast-error { background-color: #ff3e1d !important; box-shadow: 0 4px 12px rgba(255, 62, 29, 0.4) !important; border-radius: 8px !important; }
</style>
@endsection

@section('vendor-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

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
        'extra-features' => 'bx-list-plus'
    ];
    $icon = $icons[$type] ?? 'bx-collection';
    $activeCount = $items->where('is_active', 1)->count();
    $inactiveCount = $items->where('is_active', 0)->count();
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
            <h4 class="mb-0 fw-bold">{{ $title }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Car Settings</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mt-md-0">
        <div class="stat-badge d-flex align-items-center px-3 py-2 bg-white rounded shadow-xs border">
            <div class="badge bg-label-success rounded-circle p-2 me-2"><i class="bx bx-check"></i></div>
            <div>
                <small class="text-muted d-block line-height-1">Active</small>
                <span class="fw-bold stat-active-count">{{ $activeCount }}</span>
            </div>
        </div>
        <div class="stat-badge d-flex align-items-center px-3 py-2 bg-white rounded shadow-xs border">
            <div class="badge bg-label-warning rounded-circle p-2 me-2"><i class="bx bx-power-off"></i></div>
            <div>
                <small class="text-muted d-block line-height-1">Inactive</small>
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
                    <h5 class="card-title mb-1 text-white fw-bold">Quick Addition</h5>
                    <p class="mb-0 text-white-50 small">Register a new {{ Str::singular(Str::lower($title)) }} item.</p>
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

                <form id="addForm" action="{{ route('admin.vehicle-settings.store', $type) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase mb-2">Display Name / Megnevezés</label>
                        <div class="input-group input-group-merge shadow-none border-0">
                            <span class="input-group-text bg-light border-0"><i class="bx bx-rename"></i></span>
                            <input type="text" class="form-control bg-light border-0 px-3 py-2" name="name" id="add_name" required 
                                placeholder="e.g. {{ $type === 'brands' ? 'BMW' : ($type === 'fuel-types' ? 'Hybrid' : 'New Item') }}">
                        </div>
                    </div>

                    @if($type === 'models')
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase mb-2">Link to Parent Brand</label>
                            <div class="input-group input-group-merge shadow-none border-0">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-purchase-tag"></i></span>
                                <select class="form-select bg-light border-0 px-3 py-2" name="brand_id" id="add_brand_id" required>
                                    <option value="">Choose a brand...</option>
                                    @foreach(DB::table('brands')->where('is_active', true)->orderBy('name')->get() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="mb-2 mt-4 pt-2">
                        <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center shadow-primary py-2 fw-bold" id="addBtn">
                            <span class="btn-loader d-none spinner-border spinner-border-sm me-2"></span>
                            <i class="bx bx-check-circle me-2 btn-icon"></i> Register New Item
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light border-0 py-3 text-center">
                <small class="text-muted">Changes take effect immediately across all active vehicle listings.</small>
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
                        Registered {{ $title }}
                    </h5>
                    <div id="table-search-placeholder"></div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0" id="relationships-table">
                        <thead>
                            <tr class="bg-light">
                                <th class="ps-4">ID</th>
                                <th>Name / Label</th>
                                @if($type === 'models')
                                    <th>Parent Brand</th>
                                @endif
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($items as $item)
                                <tr class="transition-all hover-bg-light" data-id="{{ $item->id }}">
                                    <td class="ps-4"><span class="text-muted fw-semibold">#{{ $item->id }}</span></td>
                                    <td class="name-cell">
                                        <div class="d-flex align-items-center">
                                            <div class="indicator badge rounded-pill bg-{{ $item->is_active ? 'success' : 'secondary' }} me-2 p-1"></div>
                                            <span class="fw-bold text-dark fs-6 item-name">{{ $item->name }}</span>
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
                                                <span class="text-muted italic small">Orphaned Brand</span>
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
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-label-info border-0 edit-btn shadow-none"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                @if($type === 'models') data-brand="{{ $item->brand_id }}" @endif
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Item">
                                                <i class="bx bx-edit-alt"></i>
                                            </button>

                                            <form action="{{ route('admin.vehicle-settings.destroy', [$type, $item->id]) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-trigger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Item">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $type === 'models' ? 5 : 4 }}" class="text-center py-5">
                                        <div class="opacity-25 mb-2"><i class="bx bx-layers display-4 text-muted"></i></div>
                                        <h6 class="text-muted fw-normal">No {{ Str::lower($title) }} records matching your search.</h6>
                                    </td>
                                </tr>
                            @endforelse
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
        <div class="modal-content overflow-hidden border-0">
            <div class="modal-header bg-info py-3">
                <h5 class="modal-title fw-bold text-white"><i class="bx bx-edit-alt me-2"></i>Edit {{ Str::singular($title) }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Identification Label / Name</label>
                        <input type="text" class="form-control border-light shadow-none bg-light" name="name" id="edit_name" required>
                    </div>

                    @if($type === 'models')
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Link to Parent Brand</label>
                            <select class="form-select border-light shadow-none bg-light" name="brand_id" id="edit_brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach(DB::table('brands')->where('is_active', true)->orderBy('name')->get() as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-label-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-info px-4 shadow-info fw-bold">Update Item</button>
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
            "pageLength": 10,
            "language": {
                "search": "",
                "searchPlaceholder": "Filter items...",
                "paginate": {
                    "next": '<i class="bx bx-chevron-right"></i>',
                    "previous": '<i class="bx bx-chevron-left"></i>'
                }
            },
            "dom": '<"row mx-0 border-bottom bg-light bg-opacity-10"' +
                   '<"col-md-4 py-3"l>' +
                   '<"col-md-8 py-3 d-flex justify-content-end"f>' +
                   '>t' +
                   '<"row mx-0 p-3 bg-light bg-opacity-10 border-top"' +
                   '<"col-md-6"i>' +
                   '<"col-md-6 d-flex justify-content-end"p>' +
                   '>',
            "columnDefs": [
                { "orderable": false, "targets": [{{ $type === 'models' ? 3 : 2 }}, {{ $type === 'models' ? 4 : 3 }}] }
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
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        const item = response.item;
                        const type = '{{ $type }}';
                        let rowHtml = `
                            <tr class="transition-all hover-bg-light" data-id="${item.id}">
                                <td class="ps-4"><span class="text-muted fw-semibold">#${item.id}</span></td>
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

                        rowHtml += `
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status-toggle-switch" type="checkbox" 
                                            data-id="${item.id}" data-type="${type}" checked>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-icon btn-sm btn-label-info border-0 edit-btn shadow-none"
                                            data-id="${item.id}" data-name="${item.name}" 
                                            ${type === 'models' ? `data-brand="${item.brand_id}"` : ''}
                                            data-bs-toggle="tooltip" title="Edit Item">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        <form action="{{ url('/app/vehicle-settings') }}/${type}/${item.id}" method="POST" class="d-inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-icon btn-sm btn-label-danger border-0 shadow-none delete-trigger"
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
                        toastr.success('Item added successfully');
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
            const type = '{{ $type }}';

            $('#edit_name').val(name);
            $('#edit_brand_id').val(brandId);
            $('#editForm').attr('action', `{{ url('/app/vehicle-settings') }}/${type}/${id}`);
            
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });

        // Edit Submit Logic
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST', // Blade method field handles PUT
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        const item = response.item;
                        const row = $(`tr[data-id="${item.id}"]`);
                        
                        row.find('.item-name').text(item.name);
                        if (response.brand_name) {
                            row.find('.brand-badge').html(`<i class="bx bx-award me-1 small"></i> ${response.brand_name}`);
                        }
                        
                        // Update data attributes on edit button
                        const editBtn = row.find('.edit-btn');
                        editBtn.data('name', item.name);
                        if (item.brand_id) editBtn.data('brand', item.brand_id);

                        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                        toastr.success('Item updated successfully');
                    }
                },
                error: function(xhr) {
                    toastr.error('Error updating item');
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
                        toastr.success(`Status ${response.is_active ? 'Activated' : 'Deactivated'}`, 'Success');
                        updateStats();
                    }
                },
                error: function() {
                    switchEl.prop('checked', !checked);
                    toastr.error('Error', 'Unable to reach server');
                }
            });
        });

        // Delete Logic
        $(document).on('click', '.delete-trigger', function() {
            const btn = $(this);
            const form = btn.closest('form');
            const row = btn.closest('tr');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this item may affect existing vehicle listings!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
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

.relationships-card .table thead th {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #475569;
    padding: 1rem 0.5rem;
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
}
</style>
@endsection