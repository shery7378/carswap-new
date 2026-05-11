@extends('layouts/contentNavbarLayout')

@section('title', __('User Subscriptions'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3 fw-bold">{{ __('User Subscriptions') }}</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 plan_filter"></div>
                    <div class="col-md-4 status_filter"></div>
                    <div class="col-md-4"></div>
                </div>
            </div>
            <div class="card-body p-0 pt-3">
                <!-- TABLE VIEW -->
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle border-top" id="subscriptions-table">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th>{{ __('Customer / Email') }}</th>
                                <th>{{ __('Plan Type') }}</th>
                                <th>{{ __('Billing Cycle') }}</th>
                                <th>{{ __('Current Status') }}</th>
                                <th>{{ __('Next Payment') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($subscriptions as $subscription)
                            <tr class="cursor-pointer subscription-row" data-id="{{ $subscription->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-3">
                                            @if($subscription->user->profile_photo_url)
                                                <img src="{{ $subscription->user->profile_photo_url }}" alt="Avatar" class="rounded-circle shadow-xs">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary shadow-xs">
                                                    {{ strtoupper(substr($subscription->user->name ?? $subscription->user->first_name ?? 'U', 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark fs-6">{{ $subscription->user->name ?? $subscription->user->first_name . ' ' . $subscription->user->last_name ?? __('Unknown') }}</span>
                                            <small class="text-muted"><i class="bx bx-envelope me-1 small"></i>{{ $subscription->user->email ?? __('N/A') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-{{ $subscription->plan->color ?? 'primary' }} px-3 py-2 rounded-pill fw-bold">
                                            <i class="bx bx-trophy me-1 small"></i> {{ __($subscription->plan->name ?? 'Standard') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-6">@formatCurrency($subscription->amount, $subscription->duration === 'Monthly')</span>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">{{ __('Every') }} {{ __($subscription->plan->billing_period ?? 'Month') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'active' => 'bg-label-success',
                                            'trial' => 'bg-label-info',
                                            'expired' => 'bg-label-danger',
                                            'cancelled' => 'bg-label-secondary',
                                            'pending' => 'bg-label-warning',
                                            'paused' => 'bg-label-warning'
                                        ][$subscription->status] ?? 'bg-label-primary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        <i class="bx bx-circle me-1 small"></i> {{ __($subscription->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $subscription->next_billing_at ? $subscription->next_billing_at->formatDate() : __('N/A') }}</span>
                                        <small class="text-muted">{{ $subscription->next_billing_at ? $subscription->next_billing_at->diffForHumans() : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-container">
                                        <a class="btn-action view" href="{{ route('app-subscription-view', $subscription->id) }}" data-bs-toggle="tooltip" title="{{ __('View Details') }}">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a class="btn-action edit" href="{{ route('app-subscription-view', $subscription->id) }}#edit" data-bs-toggle="tooltip" title="{{ __('Adjust Plan') }}">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        @if($subscription->status === 'active')
                                            <button class="btn-action view status-toggle-btn" style="background-color: #fff3e0 !important; color: #ff9800 !important;" data-id="{{ $subscription->id }}" data-status="paused" data-bs-toggle="tooltip" title="{{ __('Suspend') }}">
                                                <i class="bx bx-pause-circle"></i>
                                            </button>
                                        @else
                                            <button class="btn-action edit" style="background-color: #e8f5e9 !important; color: #4caf50 !important;" data-id="{{ $subscription->id }}" data-status="active" data-bs-toggle="tooltip" title="{{ __('Reactivate') }}">
                                                <i class="bx bx-play-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                </div>

            </div>
        </div>
    </div>
</div>

@section('page-script')
<script>
    $(document).ready(function() {
        var table = $('#subscriptions-table').DataTable({
            "order": [[4, "asc"]],
            "pageLength": 10,
            "language": {
                "search": "",
                "searchPlaceholder": "{{ __('Quick Search Subscriptions…') }}"
            },
            "dom": '<"row mx-2"' +
                   '<"col-md-2"<"me-3 mt-3"l>>' +
                   '<"col-md-10"<"text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 mt-3"f>>' +
                   '>t' +
                   '<"row mx-2"' +
                   '<"col-sm-12 col-md-6"i>' +
                   '<"col-sm-12 col-md-6"p>' +
                   '>',
            initComplete: function () {
                // Plan Filter (Column 1)
                this.api().columns(1).every(function () {
                    var column = this;
                    var select = $('<select class="form-select text-capitalize"><option value=""> {{ __('Filter by Plan') }} </option></select>')
                        .appendTo('.plan_filter')
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        var textVal = $.trim($(d).text());
                        if(textVal) select.append('<option value="' + textVal + '">' + textVal + '</option>');
                    });
                });

                // Status Filter (Column 3)
                this.api().columns(3).every(function () {
                    var column = this;
                    var select = $('<select class="form-select text-capitalize"><option value=""> {{ __('Filter by Status') }} </option></select>')
                        .appendTo('.status_filter')
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        var textVal = $.trim($(d).text());
                        if(textVal) select.append('<option value="' + textVal + '">' + textVal + '</option>');
                    });
                });
            }
        });



        // Row Click: Show Invoice Modal
        $(document).on('click', '.subscription-row td:not(:last-child)', function() {
            var id = $(this).closest('tr').data('id');
            var url = '{{ route("app-subscription-view", ":id") }}'.replace(':id', id);
            
            $('#invoiceModalContent').html('<div class="p-5 text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#invoiceModal').modal('show');

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    $('#invoiceModalContent').html(response);
                },
                error: function() {
                    $('#invoiceModalContent').html('<div class="p-5 text-center text-danger">{{ __('Failed to load invoice details.') }}</div>');
                }
            });
        });

        // Quick Status Toggle
        $(document).on('click', '.status-toggle-btn', function(e) {
            e.stopPropagation();
            var id = $(this).data('id');
            var status = $(this).data('status');
            var url = '{{ route("app-subscription-status", ":id") }}'.replace(':id', id);

            var confirmMsg = status == 'active' ? "{{ __('Are you sure you want to reactivate this subscription?') }}" : "{{ __('Are you sure you want to suspend this subscription?') }}";
            if(confirm(confirmMsg)) {
                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert("{{ __('Something went wrong!') }}");
                    }
                });
            }
        });
    });
</script>
@endsection

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content overflow-hidden border-0 shadow-lg">
            <div id="invoiceModalContent"></div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}
.subscription-row:hover {
    background-color: rgba(67, 89, 113, 0.04) !important;
}
.dataTables_filter {
    width: 350px;
}
.dataTables_filter input {
    width: 100% !important;
    border-radius: 0.5rem !important;
    padding: 0.45rem 0.8rem !important;
    border: 1px solid #d9dee3 !important;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
#subscriptions-table thead th {
    font-size: 0.72rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    font-weight: 700;
    padding: 0.6rem 0.5rem !important;
    position: relative;
    padding-right: 30px !important;
    white-space: normal;
    line-height: 1.2;
}
#subscriptions-table tbody td {
    padding: 0.5rem 0.5rem !important;
    font-size: 0.82rem;
}
.table-responsive {
    overflow-x: auto !important;
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
.bg-label-success {
    background-color: #e8fadf !important;
    color: #71dd37 !important;
}
</style>
@endsection
