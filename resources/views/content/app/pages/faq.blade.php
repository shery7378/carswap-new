@extends('layouts/contentNavbarLayout')

@section('title', 'FAQ')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Frequently Asked Questions</h5>
                <p class="text-muted mb-0">Find answers to common questions about our platform</p>
            </div>
            <div class="card-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                <i class="bx bx-help-circle me-2"></i>
                                What is your platform and how does it work?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Our platform is a comprehensive management system designed to help businesses streamline their operations. It provides tools for project management, team collaboration, customer relationship management, and more. The system works by centralizing all your business data and processes in one place, making it easier to manage and analyze your operations.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                <i class="bx bx-dollar-circle me-2"></i>
                                What pricing plans are available?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer three pricing plans to suit different business needs:
                                <ul class="mt-2">
                                    <li><strong>Basic ($9.99/month):</strong> Essential features for small teams</li>
                                    <li><strong>Premium ($29.99/month):</strong> Advanced features for growing businesses</li>
                                    <li><strong>Enterprise ($99.99/month):</strong> Complete solution with custom features</li>
                                </ul>
                                All plans include a 14-day free trial, and you can upgrade or downgrade at any time.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                <i class="bx bx-lock me-2"></i>
                                How secure is my data?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We take data security very seriously. Our platform uses industry-standard encryption protocols to protect your data both in transit and at rest. We comply with GDPR, CCPA, and other major data protection regulations. Regular security audits and penetration testing ensure our systems remain secure against emerging threats.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                <i class="bx bx-support me-2"></i>
                                What kind of support do you offer?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We provide comprehensive support to all our customers:
                                <ul class="mt-2">
                                    <li>24/7 email support for all plans</li>
                                    <li>Live chat support for Premium and Enterprise plans</li>
                                    <li>Phone support for Enterprise customers</li>
                                    <li>Extensive documentation and video tutorials</li>
                                    <li>Community forum for peer support</li>
                                </ul>
                                Our average response time is under 2 hours for email inquiries.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                                <i class="bx bx-sync me-2"></i>
                                Can I integrate with other tools and services?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! Our platform offers extensive integration capabilities with popular business tools including:
                                <ul class="mt-2">
                                    <li>CRM systems (Salesforce, HubSpot)</li>
                                    <li>Accounting software (QuickBooks, Xero)</li>
                                    <li>Communication tools (Slack, Microsoft Teams)</li>
                                    <li>Project management tools (Jira, Trello)</li>
                                    <li>Marketing automation (Mailchimp, HubSpot Marketing)</li>
                                </ul>
                                We also provide a robust API for custom integrations.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                                <i class="bx bx-credit-card me-2"></i>
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We accept various payment methods for your convenience:
                                <ul class="mt-2">
                                    <li>Credit and debit cards (Visa, MasterCard, American Express)</li>
                                    <li>PayPal and other digital wallets</li>
                                    <li>Bank transfers for Enterprise plans</li>
                                    <li>Purchase orders for qualified businesses</li>
                                </ul>
                                All payments are processed securely through our PCI-compliant payment partners.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                                <i class="bx bx-undo me-2"></i>
                                What is your refund policy?
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer a 30-day money-back guarantee for all new subscriptions. If you're not satisfied with our service within the first 30 days, contact our support team for a full refund. After the initial period, you can cancel your subscription at any time, and you'll continue to have access until the end of your billing period.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight">
                                <i class="bx bx-cloud me-2"></i>
                                Is there a mobile app available?
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! We offer native mobile apps for both iOS and Android devices. The mobile apps provide full functionality including project management, team communication, and real-time notifications. You can download them from the Apple App Store or Google Play Store. The mobile apps are included with all subscription plans.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Still have questions?</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Can't find the answer you're looking for? Our support team is here to help!</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-primary mb-3">
                                <i class="bx bx-envelope"></i>
                            </div>
                            <h6 class="mb-2">Email Support</h6>
                            <p class="text-muted small mb-3">Get help via email</p>
                            <a href="mailto:support@example.com" class="btn btn-primary btn-sm">support@example.com</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-info mb-3">
                                <i class="bx bx-message-rounded"></i>
                            </div>
                            <h6 class="mb-2">Live Chat</h6>
                            <p class="text-muted small mb-3">Chat with our team</p>
                            <button type="button" class="btn btn-info btn-sm">Start Chat</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-label-success mb-3">
                                <i class="bx bx-phone"></i>
                            </div>
                            <h6 class="mb-2">Phone Support</h6>
                            <p class="text-muted small mb-3">Call us directly</p>
                            <a href="tel:+15551234567" class="btn btn-success btn-sm">+1 (555) 123-4567</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
