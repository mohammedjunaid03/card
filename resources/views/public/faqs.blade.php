@extends('layouts.app')

@section('title', 'FAQs - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Frequently Asked Questions</h1>
                <p class="lead">Find answers to common questions about our health card system</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                What is a Health Card?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                A Health Card is a digital card that provides you with exclusive discounts at our partner hospitals. It makes healthcare more affordable and accessible by offering significant savings on medical services, consultations, treatments, and procedures.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                How do I get a Health Card?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Getting a Health Card is simple:
                                <ol>
                                    <li>Register on our website with your personal details</li>
                                    <li>Upload required documents (Aadhaar card, photo)</li>
                                    <li>Verify your email and mobile number with OTP</li>
                                    <li>Your digital health card will be generated instantly</li>
                                    <li>Download the PDF or access it from your dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                Is there any registration fee?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                No, registration is completely free. There are no hidden charges, annual fees, or maintenance costs. You only pay for the medical services you avail at partner hospitals, and you get discounts on those services.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                How do I use the Health Card?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Using your Health Card is easy:
                                <ol>
                                    <li>Find a partner hospital near you using our hospital network page</li>
                                    <li>Visit the hospital for treatment or consultation</li>
                                    <li>Show your health card at the reception desk</li>
                                    <li>The hospital staff will scan your QR code to verify authenticity</li>
                                    <li>Discounts will be applied automatically to your bill</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                What is the validity of the Health Card?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                The Health Card is valid for 5 years from the date of issue. You can renew it before expiry from your dashboard. We'll send you reminders before the expiry date to ensure uninterrupted access to healthcare benefits.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq6">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                Can I use the card for my family members?
                            </button>
                        </h2>
                        <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Each person needs their own individual health card. You can register multiple family members separately using their own details. This ensures proper medical records and personalized healthcare services for each family member.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq7">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                What documents do I need for registration?
                            </button>
                        </h2>
                        <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You need the following documents:
                                <ul>
                                    <li><strong>Mandatory:</strong> Aadhaar card (PDF or image)</li>
                                    <li><strong>Optional:</strong> Recent passport-size photograph</li>
                                    <li><strong>Personal Details:</strong> Name, date of birth, address, blood group</li>
                                    <li><strong>Contact:</strong> Valid email address and mobile number</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 8 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq8">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                How much discount can I get?
                            </button>
                        </h2>
                        <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Discounts vary by hospital and service type:
                                <ul>
                                    <li><strong>Consultations:</strong> 20-40% off</li>
                                    <li><strong>Diagnostic Tests:</strong> 30-50% off</li>
                                    <li><strong>Surgeries:</strong> 25-45% off</li>
                                    <li><strong>Emergency Services:</strong> 15-30% off</li>
                                </ul>
                                The exact discount percentage is displayed on each hospital's profile page.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 9 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq9">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                                Is my personal information secure?
                            </button>
                        </h2>
                        <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="faq9" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, your personal information is completely secure. We use:
                                <ul>
                                    <li>End-to-end encryption for all data</li>
                                    <li>Secure servers with SSL certificates</li>
                                    <li>Compliance with data protection regulations</li>
                                    <li>Regular security audits and updates</li>
                                    <li>No sharing of personal data with third parties</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 10 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq10">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                                What if I lose my Health Card?
                            </button>
                        </h2>
                        <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="faq10" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Since your Health Card is digital, you can't really "lose" it. However, if you can't access it:
                                <ul>
                                    <li>Log into your account and download a new copy</li>
                                    <li>Contact our support team for assistance</li>
                                    <li>We can regenerate your card if needed</li>
                                    <li>Your card number and QR code remain the same</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 11 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq11">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="false" aria-controls="collapse11">
                                Can I use the card for emergency services?
                            </button>
                        </h2>
                        <div id="collapse11" class="accordion-collapse collapse" aria-labelledby="faq11" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, you can use your Health Card for emergency services at partner hospitals. However, please note:
                                <ul>
                                    <li>Emergency services may have different discount rates</li>
                                    <li>Some emergency procedures might not be covered</li>
                                    <li>Always inform the hospital staff about your health card</li>
                                    <li>Keep your card accessible on your phone for emergencies</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 12 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq12">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
                                How do I contact support?
                            </button>
                        </h2>
                        <div id="collapse12" class="accordion-collapse collapse" aria-labelledby="faq12" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer multiple ways to contact our support team:
                                <ul>
                                    <li><strong>Email:</strong> support@healthcardsystem.com</li>
                                    <li><strong>Phone:</strong> +91-800-123-4567 (24/7)</li>
                                    <li><strong>Live Chat:</strong> Available on our website</li>
                                    <li><strong>Support Tickets:</strong> Create a ticket from your dashboard</li>
                                    <li><strong>WhatsApp:</strong> +91-98765-43210</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">Still Have Questions?</h2>
                <p class="lead mb-4">Can't find the answer you're looking for? Our support team is here to help!</p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                <h5>Call Us</h5>
                                <p class="text-muted">+91-800-123-4567</p>
                                <small class="text-muted">24/7 Support</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                <h5>Email Us</h5>
                                <p class="text-muted">support@healthcardsystem.com</p>
                                <small class="text-muted">Response within 2 hours</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-comments fa-2x text-primary mb-3"></i>
                                <h5>Live Chat</h5>
                                <p class="text-muted">Available on website</p>
                                <small class="text-muted">Instant support</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-headset"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
