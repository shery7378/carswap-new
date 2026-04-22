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
                                            <span class="fw-bold text-dark fs-6">{{ $subscription->user->name ?? $subscription->user->first_name . ' ' . $subscription->user->last_name ?? 'Unknown' }}</span>
                                            <small class="text-muted"><i class="bx bx-envelope me-1 small"></i>{{ $subscription->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-label-{{ $subscription->plan->color ?? 'primary' }} px-3 py-2 rounded-pill fw-bold">
                                            <i class="bx bx-trophy me-1 small"></i> {{ $subscription->plan->name ?? 'Standard' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold fs-6">HUF {{ number_format($subscription->amount, 0, '.', '') }}</span>
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
                                            'pending' => 'bg-label-warning'
                                        ][$subscription->status] ?? 'bg-label-primary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        <i class="bx bx-circle me-1 small"></i> {{ __(ucfirst($subscription->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $subscription->next_billing_at ? $subscription->next_billing_at->format('M d, Y') : 'N/A' }}</span>
                                        <small class="text-muted">{{ $subscription->next_billing_at ? $subscription->next_billing_at->diffForHumans() : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-icon btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('app-subscription-view', $subscription->id) }}"><i class="bx bx-show-alt me-1 text-primary"></i> {{ __('View Details') }}</a>
                                            <a class="dropdown-item" href="{{ route('app-subscription-view', $subscription->id) }}#edit"><i class="bx bx-edit-alt me-1 text-info"></i> {{ __('Adjust Plan') }}</a>
                                            <div class="dropdown-divider"></div>
                                            @if($subscription->status === 'active')
                                                <a class="dropdown-item text-warning status-toggle-btn" href="javascript:void(0);" data-id="{{ $subscription->id }}" data-status="paused"><i class="bx bx-pause-circle me-1"></i> {{ __('Suspend') }}</a>
                                            @else
                                                <a class="dropdown-item text-success status-toggle-btn" href="javascript:void(0);" data-id="{{ $subscription->id }}" data-status="active"><i class="bx bx-play-circle me-1"></i> {{ __('Reactivate') }}</a>
                                            @endif
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"><i class="bx bx-x-circle me-1"></i> {{ __('Cancel Flow') }}</a>
                                        </div>
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

@section('page-script')
<script>
    $(document).ready(function() {
        var table = $('#subscriptions-table').DataTable({
            "order": [[4, "asc"]],
            "pageLength": 10,
            "language": {
                "search": "",
                "searchPlaceholder": "{{ __('Search by customer or plan...') }}",
                "paginate": {
                    "next": '<i class="bx bx-chevron-right fs-5"></i>',
                    "previous": '<i class="bx bx-chevron-left fs-5"></i>'
                }
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
    font-size: 0.75rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    font-weight: 700;
}
.bg-label-success {
    background-color: #e8fadf !important;
    color: #71dd37 !important;
}
</style>
@endsection
