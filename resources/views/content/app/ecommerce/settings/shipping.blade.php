@extends('layouts/contentNavbarLayout')

@section('title', 'Shipping Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Shipping Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Shipping Methods</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="standardShipping" checked>
                                <label class="form-check-label" for="standardShipping">Standard Shipping</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="expressShipping" checked>
                                <label class="form-check-label" for="expressShipping">Express Shipping</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="overnightShipping">
                                <label class="form-check-label" for="overnightShipping">Overnight Shipping</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="internationalShipping" checked>
                                <label class="form-check-label" for="internationalShipping">International Shipping</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="localPickup" checked>
                                <label class="form-check-label" for="localPickup">Local Pickup</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="freeShipping">
                                <label class="form-check-label" for="freeShipping">Free Shipping</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Shipping Rates</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="standardRate">Standard Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="standardRate" value="5.99" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="expressRate">Express Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="expressRate" value="12.99" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="overnightRate">Overnight Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="overnightRate" value="24.99" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="internationalRate">International Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="internationalRate" value="29.99" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Free Shipping Threshold</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="enableFreeThreshold" checked>
                                <label class="form-check-label" for="enableFreeThreshold">Enable Free Shipping Threshold</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="freeThreshold">Free Shipping Threshold</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="freeThreshold" value="50.00" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase fw-medium mb-3">Processing Time</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="processingDays">Processing Days</label>
                            <input type="number" class="form-control" id="processingDays" value="1" min="0" max="7">
                            <small class="text-muted">Business days before shipping</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="standardDays">Standard Delivery Days</label>
                            <input type="number" class="form-control" id="standardDays" value="5" min="1" max="30">
                            <small class="text-muted">Days for standard shipping</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="expressDays">Express Delivery Days</label>
                            <input type="number" class="form-control" id="expressDays" value="2" min="1" max="7">
                            <small class="text-muted">Days for express shipping</small>
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
