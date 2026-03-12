@extends('layouts/contentNavbarLayout')

@section('title', 'Payment Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Payment Methods</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="creditCard" checked>
                                <label class="form-check-label" for="creditCard">Credit/Debit Cards</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="paypal" checked>
                                <label class="form-check-label" for="paypal">PayPal</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="stripe">
                                <label class="form-check-label" for="stripe">Stripe</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="bankTransfer" checked>
                                <label class="form-check-label" for="bankTransfer">Bank Transfer</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="cashOnDelivery" checked>
                                <label class="form-check-label" for="cashOnDelivery">Cash on Delivery</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="applePay">
                                <label class="form-check-label" for="applePay">Apple Pay</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="googlePay">
                                <label class="form-check-label" for="googlePay">Google Pay</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Payment Gateway Configuration</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="gatewayProvider">Default Gateway</label>
                            <select class="form-select" id="gatewayProvider">
                                <option selected>Select gateway</option>
                                <option value="stripe">Stripe</option>
                                <option value="paypal">PayPal</option>
                                <option value="square">Square</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="currency">Currency</label>
                            <select class="form-select" id="currency">
                                <option selected>USD - US Dollar</option>
                                <option value="eur">EUR - Euro</option>
                                <option value="gbp">GBP - British Pound</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="apiKey">API Key</label>
                            <input type="password" class="form-control" id="apiKey" placeholder="Enter API key">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="secretKey">Secret Key</label>
                            <input type="password" class="form-control" id="secretKey" placeholder="Enter secret key">
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Transaction Settings</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="minAmount">Minimum Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="minAmount" value="10.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="maxAmount">Maximum Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="maxAmount" value="10000.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="transactionFee">Transaction Fee (%)</label>
                            <input type="number" class="form-control" id="transactionFee" value="2.9" step="0.1">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="autoRefund" checked>
                                <label class="form-check-label" for="autoRefund">Enable Automatic Refunds</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="fraudDetection" checked>
                                <label class="form-check-label" for="fraudDetection">Enable Fraud Detection</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
