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
                    <div class="mb-1 small">
                        <span class="me-1">Date Issues:</span>
                        <span class="fw-bold">{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="small">
                        <span class="me-1">Status:</span>
                        @php
                            $statusClass = [
                                'active' => 'bg-label-success',
                                'trial' => 'bg-label-info',
                                'expired' => 'bg-label-danger',
                                'cancelled' => 'bg-label-secondary',
                                'paused' => 'bg-label-warning'
                            ][$subscription->status] ?? 'bg-label-primary';
                        @endphp
                        <span class="badge {{ $statusClass }} py-0 px-2 fw-normal">{{ ucfirst($subscription->status) }}</span>
                    </div>
                </div>
            </div>
            
            <hr class="mx-n4 my-0">
            
            <div class="row pt-4">
                <div class="col-6 mb-xl-0 mb-4">
                    <h6 class="pb-2 fw-bold text-uppercase small">Invoice To:</h6>
                    <p class="mb-1 fw-bold">{{ $subscription->billing_full_name ?? $subscription->user->name ?? 'N/A' }}</p>
                    <p class="mb-1 text-muted small">{{ $subscription->billing_company_name ?? 'N/A' }}</p>
                    <p class="mb-1 text-muted small">{{ $subscription->billing_address ?? 'N/A' }}</p>
                    <p class="mb-1 text-muted small">{{ $subscription->billing_city ?? 'N/A' }}</p>
                    <p class="mb-0 text-muted small">{{ $subscription->user->email ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <h6 class="pb-2 fw-bold text-uppercase small">Subscription Details:</h6>
                    <table class="w-100 small">
                        <tbody>
                            <tr>
                                <td class="pe-3 pb-1">Plan:</td>
                                <td class="fw-bold pb-1 text-end">{{ $subscription->plan->name ?? 'Standard' }}</td>
                            </tr>
                            <tr>
                                <td class="pe-3 pb-1">Cycle:</td>
                                <td class="pb-1 text-end">Every {{ $subscription->plan->billing_period ?? 'Month' }}</td>
                            </tr>
                            <tr>
                                <td class="pe-3">Valid Until:</td>
                                <td class="text-end fw-bold text-info">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-responsive border rounded mt-4">
                <table class="table table-sm m-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="small py-2">Item Description</th>
                            <th class="small py-2 text-center">Period</th>
                            <th class="small py-2 text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap fw-bold fs-7 py-3">
                                Subscription to {{ $subscription->plan->name ?? 'Standard' }} Plan
                            </td>
                            <td class="text-nowrap text-muted text-center small">
                                {{ $subscription->starts_at ? $subscription->starts_at->format('m/d/y') : 'N/A' }} - 
                                {{ $subscription->ends_at ? $subscription->ends_at->format('m/d/y') : 'N/A' }}
                            </td>
                            <td class="fw-bold text-end py-3">HUF {{ number_format($subscription->amount, 0, '.', '') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row pt-4">
                <div class="col-md-6 mb-md-0 mb-3">
                    <div class="mt-2">
                        <small class="fw-bold d-block mb-1 text-uppercase">Payment Method:</small>
                        <div class="d-flex align-items-center">
                            <i class="bx bxl-stripe text-info fs-3 me-2"></i>
                            <span class="text-muted small">Stripe Secure Payment</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <div class="invoice-calculations w-100">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal:</span>
                            <span class="fw-bold small">HUF {{ number_format($subscription->amount, 0, '.', '') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                            <span class="text-muted small">Tax (0%):</span>
                            <span class="fw-bold small">HUF 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total:</span>
                            <span class="fw-bold fs-5 text-primary">HUF {{ number_format($subscription->amount, 0, '.', '') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 pt-2 border-top">
                <p class="text-muted small mb-0"><span class="fw-bold">Note:</span> Your subscription will automatically renew. You can manage your plan at any time from your account settings.</p>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer border-top-0 d-flex justify-content-between px-4 pb-4">
    <div>
        @if($subscription->status === 'active')
            <button class="btn btn-outline-warning status-toggle btn-sm px-3" data-id="{{ $subscription->id }}" data-status="paused">
                <i class="bx bx-pause-circle me-1"></i> Suspend
            </button>
        @else
            <button class="btn btn-outline-success status-toggle btn-sm px-3" data-id="{{ $subscription->id }}" data-status="active">
                <i class="bx bx-play-circle me-1"></i> Reactivate
            </button>
        @endif
        <a href="{{ route('app-subscription-view', $subscription->id) }}" class="btn btn-label-primary btn-sm px-3">
            <i class="bx bx-link-external me-1"></i> Full Details
        </a>
    </div>
    <div>
        <button type="button" class="btn btn-label-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm px-3" onclick="window.print();">
            <i class="bx bx-printer me-1 text-white"></i> Print
        </button>
    </div>
</div>

<style>
@media print {
    @page { margin: 0; }
    body { margin: 1.6cm; }
    .layout-navbar, .layout-menu, .modal-header, .btn, .footer, .btn-close, .modal-footer {
        display: none !important;
    }
    .modal-content {
        box-shadow: none !important;
        border: none !important;
        background: white !important;
    }
    .modal-body {
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>
