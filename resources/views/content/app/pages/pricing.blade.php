@extends('layouts/contentNavbarLayout')

@section('title', 'Pricing')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="text-center mb-8">
            <h2 class="mb-2">Choose Your Plan</h2>
            <p class="text-muted">Select the perfect plan for your business needs</p>
            <div class="d-flex justify-content-center align-items-center mb-4">
                <span class="me-2">Monthly</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="pricingToggle">
                    <label class="form-check-label" for="pricingToggle"></label>
                </div>
                <span class="ms-2">Annual <span class="badge bg-label-success">Save 20%</span></span>
            </div>
        </div>

        <div class="row g-4 mb-8">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-info mb-3">
                                <i class="bx bx-user"></i>
                            </div>
                            <h4 class="mb-2">Basic</h4>
                            <p class="text-muted mb-4">Perfect for individuals and small teams</p>
                            <div class="pricing-monthly">
                                <h2 class="mb-0">$9.99</h2>
                                <small class="text-muted">/month</small>
                            </div>
                            <div class="pricing-annual d-none">
                                <h2 class="mb-0">$95.90</h2>
                                <small class="text-muted">/year</small>
                            </div>
                        </div>
                        <hr class="my-4">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Up to 5 users
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                10 GB storage
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Basic support
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Core features
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bx bx-x text-danger me-2"></i>
                                Advanced analytics
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bx bx-x text-danger me-2"></i>
                                API access
                            </li>
                            <li class="text-muted">
                                <i class="bx bx-x text-danger me-2"></i>
                                Custom integrations
                            </li>
                        </ul>
                        <button type="button" class="btn btn-outline-primary w-100">Get Started</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-primary">
                    <div class="card-body position-relative">
                        <span class="badge bg-label-primary position-absolute top-0 start-50 translate-middle-y">Popular</span>
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-primary mb-3">
                                <i class="bx bx-star"></i>
                            </div>
                            <h4 class="mb-2">Premium</h4>
                            <p class="text-muted mb-4">Best for growing businesses</p>
                            <div class="pricing-monthly">
                                <h2 class="mb-0">$29.99</h2>
                                <small class="text-muted">/month</small>
                            </div>
                            <div class="pricing-annual d-none">
                                <h2 class="mb-0">$287.90</h2>
                                <small class="text-muted">/year</small>
                            </div>
                        </div>
                        <hr class="my-4">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Up to 20 users
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                100 GB storage
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Priority support
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                All features
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Advanced analytics
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                API access
                            </li>
                            <li class="mb-3 text-muted">
                                <i class="bx bx-x text-danger me-2"></i>
                                Custom integrations
                            </li>
                        </ul>
                        <button type="button" class="btn btn-primary w-100">Get Started</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-warning mb-3">
                                <i class="bx bx-crown"></i>
                            </div>
                            <h4 class="mb-2">Enterprise</h4>
                            <p class="text-muted mb-4">Complete solution for large organizations</p>
                            <div class="pricing-monthly">
                                <h2 class="mb-0">$99.99</h2>
                                <small class="text-muted">/month</small>
                            </div>
                            <div class="pricing-annual d-none">
                                <h2 class="mb-0">$959.90</h2>
                                <small class="text-muted">/year</small>
                            </div>
                        </div>
                        <hr class="my-4">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Unlimited users
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Unlimited storage
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                24/7 dedicated support
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                All features
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Advanced analytics
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                API access
                            </li>
                            <li class="mb-3">
                                <i class="bx bx-check text-success me-2"></i>
                                Custom integrations
                            </li>
                        </ul>
                        <button type="button" class="btn btn-outline-primary w-100">Contact Sales</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Feature Comparison</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th class="text-center">Basic</th>
                                <th class="text-center">Premium</th>
                                <th class="text-center">Enterprise</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Users</td>
                                <td class="text-center">Up to 5</td>
                                <td class="text-center">Up to 20</td>
                                <td class="text-center">Unlimited</td>
                            </tr>
                            <tr>
                                <td>Storage</td>
                                <td class="text-center">10 GB</td>
                                <td class="text-center">100 GB</td>
                                <td class="text-center">Unlimited</td>
                            </tr>
                            <tr>
                                <td>Projects</td>
                                <td class="text-center">10</td>
                                <td class="text-center">100</td>
                                <td class="text-center">Unlimited</td>
                            </tr>
                            <tr>
                                <td>Support Response Time</td>
                                <td class="text-center">48 hours</td>
                                <td class="text-center">24 hours</td>
                                <td class="text-center">1 hour</td>
                            </tr>
                            <tr>
                                <td>API Calls</td>
                                <td class="text-center">1,000/month</td>
                                <td class="text-center">10,000/month</td>
                                <td class="text-center">Unlimited</td>
                            </tr>
                            <tr>
                                <td>Custom Branding</td>
                                <td class="text-center"><i class="bx bx-x text-danger"></i></td>
                                <td class="text-center"><i class="bx bx-check text-success"></i></td>
                                <td class="text-center"><i class="bx bx-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Advanced Security</td>
                                <td class="text-center"><i class="bx bx-x text-danger"></i></td>
                                <td class="text-center"><i class="bx bx-check text-success"></i></td>
                                <td class="text-center"><i class="bx bx-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td>Custom Integrations</td>
                                <td class="text-center"><i class="bx bx-x text-danger"></i></td>
                                <td class="text-center"><i class="bx bx-x text-danger"></i></td>
                                <td class="text-center"><i class="bx bx-check text-success"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Frequently Asked Questions</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">Can I change my plan later?</h6>
                        <p class="text-muted">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Is there a free trial?</h6>
                        <p class="text-muted">All plans come with a 14-day free trial. No credit card required to start your trial.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">What payment methods do you accept?</h6>
                        <p class="text-muted">We accept all major credit cards, PayPal, and bank transfers for Enterprise plans.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Can I cancel anytime?</h6>
                        <p class="text-muted">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
