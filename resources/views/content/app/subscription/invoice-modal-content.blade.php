<div class="modal-body p-0">
    <div class="row m-0">
        <!-- Invoice Preview Card -->
        <div class="col-12 p-4">
            <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column mb-4">
                <div class="mb-xl-0 mb-4">
                    <div class="d-flex svg-illustration mb-3 gap-2 align-items-center">
                        <span class="app-brand-logo demo">
                            <i class="bx bx-car fs-2 text-primary"></i>
                        </span>
                        <span class="app-brand-text demo fw-bold text-dark fs-3">CarSwap</span>
                    </div>
                    <p class="mb-1 text-muted small">123 Street Avenue, Budapest</p>
                    <p class="mb-1 text-muted small">Hungary, 1051</p>
                    <p class="mb-0 text-muted small">+36 (0) 123 4567</p>
                </div>
                <div>
                    <h4 class="fw-bold mb-2">#INVOICE-{{ $subscription->id }}</h4>
                    <div class="mb-1">
                        <span class="me-1">Kiadás dátuma:</span>
                        <span
                            class="fw-bold">{{ $subscription->starts_at ? $subscription->starts_at->formatDate() : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="me-1">Állapot:</span>
                        @php
                            $statusLabels = [
                                'active' => 'Aktív',
                                'trial' => 'Próbaidőszak',
                                'expired' => 'Lejárt',
                                'cancelled' => 'Lemondva',
                                'paused' => 'Felfüggesztve'
                            ];
                            $statusClass = [
                                'active' => 'bg-label-success',
                                'trial' => 'bg-label-info',
                                'expired' => 'bg-label-danger',
                                'cancelled' => 'bg-label-secondary',
                                'pending' => 'bg-label-warning',
                                'paused' => 'bg-label-warning'
                            ][$subscription->status] ?? 'bg-label-primary';
                        @endphp
                        <span
                            class="badge {{ $statusClass }} py-0 px-2 fw-normal">{{ $statusLabels[$subscription->status] ?? $subscription->status }}</span>
                    </div>
                </div>
            </div>

            <hr class="mx-n4 my-0">

            <div class="row pt-4">
                <div class="col-6 mb-xl-0 mb-4">
                    <h6 class="pb-2 fw-bold text-uppercase">Számlázási címzett:</h6>
                    <p class="mb-1 fw-bold">{{ $subscription->billing_full_name ?? $subscription->user->name ?? 'N/A' }}
                    </p>
                    <p class="mb-1 text-muted">{{ $subscription->billing_company_name ?? 'N/A' }}</p>
                    <p class="mb-1 text-muted">{{ $subscription->billing_address ?? 'N/A' }}</p>
                    <p class="mb-1 text-muted">{{ $subscription->billing_city ?? 'N/A' }}</p>
                    <p class="mb-0 text-muted">{{ $subscription->user->email ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
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
                                <td>{{ $subscription->ends_at ? $subscription->ends_at->formatDate() : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
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

            <div class="row pt-4">
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
                        <span class="fw-bold fs-5 text-primary">@formatCurrency($subscription->amount)</span>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-bold">Megjegyzés:</h6>
                    <span class="text-muted small">Öröm volt Önnel és csapatával dolgozni. Reméljük, továbbra is a CarSwap-ot használja. Köszönjük!</span>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm overflow-hidden">
                <div class="card-header border-bottom px-0">
                    <h6 class="mb-2 fw-bold text-uppercase">Legutóbbi fizetések</h6>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2">Azonosító</th>
                                <th class="py-2">Összeg</th>
                                <th class="py-2">Mód</th>
                                <th class="py-2">Állapot</th>
                                <th class="py-2">Dátum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->transaction_id ?? $payment->id }}</td>
                                    <td class="fw-bold text-dark">@formatCurrency($payment->amount)</td>
                                    <td><span class="text-uppercase small">{{ $payment->payment_method ?? 'Stripe' }}</span></td>
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
    </div>
</div>
<div class="modal-footer border-top-0 d-flex justify-content-between px-4 pb-4">
    <div>
        @if($subscription->status === 'active')
            <button class="btn btn-outline-warning status-toggle btn-sm px-3" data-id="{{ $subscription->id }}"
                data-status="paused">
                <i class="bx bx-pause-circle me-1"></i> {{ __('Suspend') }}
            </button>
        @else
            <button class="btn btn-outline-success status-toggle btn-sm px-3" data-id="{{ $subscription->id }}"
                data-status="active">
                <i class="bx bx-play-circle me-1"></i> {{ __('Reactivate') }}
            </button>
        @endif
        <a href="{{ route('app-subscription-view', $subscription->id) }}" class="btn btn-label-primary btn-sm px-3">
            <i class="bx bx-link-external me-1"></i> {{ __('Full Details') }}
        </a>
    </div>
    <div>
        <button type="button" class="btn btn-label-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn btn-primary btn-sm px-3" onclick="window.print();">
            <i class="bx bx-printer me-1 text-white"></i> {{ __('Print') }}
        </button>
    </div>
</div>

<style>
    @media print {
        @page {
            margin: 0;
            size: auto;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            visibility: hidden !important;
        }

        /* Show only the modal and its content */
        #invoiceModal, 
        #invoiceModalContent,
        #invoiceModalContent * {
            visibility: visible !important;
        }

        /* Position the modal at the top left of the print page */
        #invoiceModal {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        /* Remove modal-dialog margins/styling for print */
        #invoiceModal .modal-dialog {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            transform: none !important;
        }

        #invoiceModal .modal-content {
            border: none !important;
            box-shadow: none !important;
            background: #fff !important;
            padding: 1.6cm !important; /* Re-apply the desired margin here */
        }

        /* Hide buttons, footer, and header during print */
        #invoiceModal .modal-footer,
        #invoiceModal .btn,
        #invoiceModal .btn-close,
        #invoiceModal .modal-header {
            display: none !important;
        }

        .modal-body {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Ensure content takes full width where intended, but allow col-6 to be side-by-side */
        .col-12 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        
        .col-6 {
            width: 50% !important;
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
    }
</style>