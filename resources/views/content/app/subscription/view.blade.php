@extends('layouts/contentNavbarLayout')

@section('title', __('Subscription Invoice'))

@section('content')
    <div class="row">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card border-0 shadow-sm">
                <div class="card-body">
                    <div
                        class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                        <div class="mb-xl-0 mb-4">
                            <div class="d-flex svg-illustration mb-3 gap-2 align-items-center">
                                <span class="app-brand-logo demo">
                                    <i class="bx bx-car fs-2 text-primary"></i>
                                </span>
                                <span class="app-brand-text demo fw-bold text-dark fs-3">CarSwap</span>
                            </div>
                            <p class="mb-1 text-muted">123 Street Avenue, Budapest</p>
                            <p class="mb-1 text-muted">Hungary, 1051</p>
                            <p class="mb-0 text-muted">+36 (0) 123 4567</p>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-2">#INVOICE-{{ $subscription->id }}</h4>
                            <div class="mb-2">
                                <span class="me-1">Kiadás dátuma:</span>
                                <span
                                    class="fw-bold">{{ $subscription->starts_at ? $subscription->starts_at->formatDate() : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="me-1">Állapot:</span>
                                @php
                                    $statusClass = [
                                        'active' => 'bg-label-success',
                                        'trial' => 'bg-label-info',
                                        'expired' => 'bg-label-danger',
                                        'cancelled' => 'bg-label-secondary',
                                        'paused' => 'bg-label-warning'
                                    ][$subscription->status] ?? 'bg-label-primary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($subscription->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row p-sm-3 p-0">
                        <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                            <h6 class="pb-2 fw-bold text-uppercase">Számlázási címzett:</h6>
                            <p class="mb-1 fw-bold">
                                {{ $subscription->billing_full_name ?? $subscription->user->name ?? 'N/A' }}</p>
                            <p class="mb-1 text-muted">{{ $subscription->billing_company_name ?? 'N/A' }}</p>
                            <p class="mb-1 text-muted">{{ $subscription->billing_address ?? 'N/A' }}</p>
                            <p class="mb-1 text-muted">{{ $subscription->billing_city ?? 'N/A' }}</p>
                            <p class="mb-0 text-muted">{{ $subscription->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                            <h6 class="pb-2 fw-bold text-uppercase">Előfizetés részletei:</h6>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pe-3">Csomag:</td>
                                        <td class="fw-bold">{{ $subscription->plan->name ?? 'Standard' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3">Ciklus:</td>
                                        <td>Minden {{ $subscription->plan->billing_period ?? 'Hónap' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3">Érvényes eddig:</td>
                                        <td>{{ $subscription->ends_at ? $subscription->ends_at->formatDate() : 'N/A' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table border-top m-0">
                        <thead>
                            <tr>
                                <th>Tétel megnevezése</th>
                                <th>Számlázási időszak</th>
                                <th>Összeg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap fw-bold fs-6">
                                    Előfizetés a {{ $subscription->plan->name ?? 'Standard' }} csomagra
                                </td>
                                <td class="text-nowrap text-muted">
                                    {{ $subscription->starts_at ? $subscription->starts_at->formatDate() : 'N/A' }} -
                                    {{ $subscription->ends_at ? $subscription->ends_at->formatDate() : 'N/A' }}
                                </td>
                                <td class="fw-bold">@formatCurrency($subscription->amount)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-end">
                            <div class="d-flex justify-content-end align-items-center mb-2">
                                <span class="me-3">Részösszeg:</span>
                                <span class="fw-bold fs-6">@formatCurrency($subscription->amount)</span>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mb-2">
                                <span class="me-3">Adó:</span>
                                <span class="fw-bold fs-6">@formatCurrency(0)</span>
                            </div>
                            <div class="d-flex justify-content-end align-items-center border-top pt-2">
                                <span class="me-3 fs-5 fw-bold">Összesen:</span>
                                <span class="fw-bold fs-4 text-primary">@formatCurrency($subscription->amount)</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-bold">Megjegyzés:</h6>
                            <span class="text-muted small">Öröm volt Önnel és csapatával dolgozni. Reméljük, továbbra is a CarSwap-ot használja. Köszönjük!</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm overflow-hidden">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 fw-bold">Legutóbbi fizetések</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Azonosító</th>
                                <th>Összeg</th>
                                <th>Mód</th>
                                <th>Állapot</th>
                                <th>Dátum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->transaction_id ?? $payment->id }}</td>
                                    <td class="fw-bold text-dark">@formatCurrency($payment->amount)</td>
                                    <td><span class="text-uppercase small">{{ $payment->payment_method ?? 'Stripe' }}</span>
                                    </td>
                                    <td><span class="badge bg-label-success">{{ ucfirst($payment->status) }}</span></td>
                                    <td>{{ $payment->created_at->formatDateTime() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Nem találhatók fizetési rekordok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-xl-3 col-md-4 col-12">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header border-bottom">
                    <h5 class="mb-0 fw-bold">Műveletek</h5>
                </div>
                <div class="card-body pt-4">
                    <button class="btn btn-primary d-grid w-100 mb-3" data-bs-toggle="modal"
                        data-bs-target="#editSubscriptionModal">
                        <span class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-edit me-2"></i>Csomag szerkesztése
                        </span>
                    </button>

                    @if($subscription->status === 'active')
                        <button class="btn btn-warning d-grid w-100 mb-3 status-toggle" data-status="paused">
                            <span class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-pause-circle me-2"></i>Felhasználó szüneteltetése
                            </span>
                        </button>
                    @else
                        <button class="btn btn-success d-grid w-100 mb-3 status-toggle" data-status="active">
                            <span class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-play-circle me-2"></i>Felhasználó folytatása
                            </span>
                        </button>
                    @endif

                    <button class="btn btn-secondary d-grid w-100 mb-3" onclick="window.print()">
                        <span class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-printer me-2"></i>Számla nyomtatása
                        </span>
                    </button>

                    <a href="{{ route('app-subscription-list') }}" class="btn btn-outline-secondary d-grid w-100">
                        Vissza a listához
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subscription Modal -->
    <div class="modal fade" id="editSubscriptionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Előfizetés részleteinek frissítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
                </div>
                <form action="{{ route('app-subscription-update', $subscription->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Előfizetési csomag</label>
                                <select name="plan_id" class="form-select">
                                    @foreach($plans as $plan)
                                        @php
                                            $period = strtolower($plan->billing_period);
                                            $label = 'Other';
                                            if (str_contains($period, 'month')) $label = 'Monthly';
                                            if (str_contains($period, 'year')) $label = 'Yearly';
                                        @endphp
                                        <option value="{{ $plan->id }}" {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - {{ $label }} (@formatCurrency((float) $plan->price))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kifizetett összeg</label>
                                <input type="number" step="0.01" name="amount" class="form-control"
                                    value="{{ $subscription->amount }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kezdő dátum</label>
                                <input type="datetime-local" name="starts_at" class="form-control"
                                    value="{{ $subscription->starts_at ? $subscription->starts_at->format('Y-m-d\TH:i') : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Záró dátum</label>
                                <input type="datetime-local" name="ends_at" class="form-control"
                                    value="{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d\TH:i') : '' }}">
                            </div>
                        </div>
                        <hr>
                        <h6 class="mt-4 fw-bold">Számlázási információk</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teljes név</label>
                                <input type="text" name="billing_full_name" class="form-control"
                                    value="{{ $subscription->billing_full_name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cégnév</label>
                                <input type="text" name="billing_company_name" class="form-control"
                                    value="{{ $subscription->billing_company_name }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Cím</label>
                                <input type="text" name="billing_address" class="form-control"
                                    value="{{ $subscription->billing_address }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Város</label>
                                <input type="text" name="billing_city" class="form-control"
                                    value="{{ $subscription->billing_city }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Időtartam</label>
                                <select name="duration" class="form-select">
                                    <option value="Monthly" {{ $subscription->duration == 'Monthly' ? 'selected' : '' }}>
                                        Monthly</option>
                                    <option value="Yearly" {{ $subscription->duration == 'Yearly' ? 'selected' : '' }}>Yearly
                                    </option>
                                    <option value="Lifetime (Free)" {{ $subscription->duration == 'Lifetime (Free)' ? 'selected' : '' }}>Lifetime (Free)</option>
                                    <option value="Trial" {{ $subscription->duration == 'Trial' ? 'selected' : '' }}>Trial
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Állapot</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="paused" {{ $subscription->status == 'paused' ? 'selected' : '' }}>Paused
                                    </option>
                                    <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired
                                    </option>
                                    <option value="trial" {{ $subscription->status == 'trial' ? 'selected' : '' }}>Trial
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Bezárás</button>
                        <button type="submit" class="btn btn-primary">Módosítások mentése</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        $(document).ready(function () {
            // Auto-open modal if hash is #edit
            if (window.location.hash === '#edit') {
                var editModal = new bootstrap.Modal(document.getElementById('editSubscriptionModal'));
                editModal.show();
            }

            // Auto-calculate end date and amount
            const plans = @json($plans->keyBy('id'));
            console.log('Available Plans:', plans);

            function calculateSubscription() {
                const planEl = $('select[name="plan_id"]');
                const planId = planEl.val();
                const startsAt = $('input[name="starts_at"]').val();

                console.log('Calculating... Plan ID:', planId, 'Starts At:', startsAt);

                if (planId && plans[planId]) {
                    const plan = plans[planId];
                    console.log('Selected Plan Details:', plan);

                    // Update amount
                    const formattedPrice = parseFloat(plan.price).toFixed(2);
                    $('input[name="amount"]').val(formattedPrice);

                    if (startsAt) {
                        const dateString = startsAt.includes('T') ? startsAt : startsAt.replace(' ', 'T');
                        const date = new Date(dateString);

                        if (isNaN(date.getTime())) {
                            console.warn('Invalid Date format:', startsAt);
                            return;
                        }

                        const period = (plan.billing_period || '').toLowerCase();
                        if (period === 'monthly' || period === 'month') {
                            date.setMonth(date.getMonth() + 1);
                            $('select[name="duration"]').val('Monthly');
                        } else if (period === 'yearly' || period === 'year') {
                            date.setFullYear(date.getFullYear() + 1);
                            $('select[name="duration"]').val('Yearly');
                        }

                        const format = (d) => {
                            const yyyy = d.getFullYear();
                            const mm = String(d.getMonth() + 1).padStart(2, '0');
                            const dd = String(d.getDate()).padStart(2, '0');
                            const hh = String(d.getHours()).padStart(2, '0');
                            const min = String(d.getMinutes()).padStart(2, '0');
                            return `${yyyy}-${mm}-${dd}T${hh}:${min}`;
                        };

                        const endDate = format(date);
                        $('input[name="ends_at"]').val(endDate);
                        console.log('Auto-calculated End Date:', endDate);
                    }
                }
            }

            // Listen for both standard change and Select2 change
            $(document).on('change select2:select', 'select[name="plan_id"]', calculateSubscription);
            $(document).on('change', 'input[name="starts_at"]', calculateSubscription);

            $('#editSubscriptionModal').on('shown.bs.modal', function () {
                console.log('Modal shown, triggering calculation');
                calculateSubscription();
            });

            // Run once on load to ensure sync
            setTimeout(calculateSubscription, 500);

            $('.status-toggle').on('click', function () {
                var status = $(this).data('status');
                var btn = $(this);
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route("app-subscription-status", $subscription->id) }}',
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function () {
                        alert('Something went wrong!');
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection

@section('page-style')
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 1.6cm;
            }

            .layout-navbar,
            .layout-menu,
            .col-xl-3,
            .btn-close,
            .modal,
            .card-header,
            .btn,
            .footer {
                display: none !important;
            }

            .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }

            .col-xl-9 {
                width: 100% !important;
            }

            .invoice-preview-card {
                box-shadow: none !important;
                border: none !important;
            }
        }

        .bg-label-info {
            background-color: #e5f5fa !important;
            color: #03c3ec !important;
        }

        .bg-label-warning {
            background-color: #fff2e2 !important;
            color: #ffab00 !important;
        }

        .bg-label-secondary {
            background-color: #ebedef !important;
            color: #8592a3 !important;
        }
    </style>
@endsection