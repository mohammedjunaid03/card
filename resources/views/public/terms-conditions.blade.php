@extends('layouts.app')

@section('title', 'Terms & Conditions - KCC HealthCard')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Terms & Conditions</h1>
                <p class="lead">Please read these terms carefully before using our services</p>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <p class="text-muted mb-4">Last updated: {{ date('F d, Y') }}</p>
                        
                        <h3>1. Acceptance of Terms</h3>
                        <p>By accessing and using KCC HealthCard services, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                        
                        <h3>2. Description of Service</h3>
                        <p>KCC HealthCard provides a digital health card platform that connects patients with partner hospitals and healthcare providers. Our service includes:</p>
                        <ul>
                            <li>Digital health card generation and management</li>
                            <li>Hospital network access and verification</li>
                            <li>Discount and benefit management</li>
                            <li>Patient-provider matching services</li>
                        </ul>
                        
                        <h3>3. User Responsibilities</h3>
                        <h4>3.1 Patient Users</h4>
                        <ul>
                            <li>Provide accurate and complete information during registration</li>
                            <li>Maintain the confidentiality of your account credentials</li>
                            <li>Use the health card only for legitimate healthcare purposes</li>
                            <li>Comply with all applicable laws and regulations</li>
                        </ul>
                        
                        <h4>3.2 Hospital Partners</h4>
                        <ul>
                            <li>Maintain valid medical licenses and certifications</li>
                            <li>Provide quality healthcare services as advertised</li>
                            <li>Honor all discount commitments and pricing agreements</li>
                            <li>Maintain patient confidentiality and data security</li>
                        </ul>
                        
                        <h3>4. Privacy and Data Protection</h3>
                        <p>We are committed to protecting your privacy and personal information. Our data collection, use, and protection practices are detailed in our <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.</p>
                        
                        <h3>5. Service Availability</h3>
                        <p>While we strive to provide continuous service availability, we do not guarantee uninterrupted access to our platform. We reserve the right to:</p>
                        <ul>
                            <li>Perform scheduled maintenance and updates</li>
                            <li>Modify or discontinue services with reasonable notice</li>
                            <li>Suspend services for security or legal reasons</li>
                        </ul>
                        
                        <h3>6. Limitation of Liability</h3>
                        <p>KCC HealthCard shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:</p>
                        <ul>
                            <li>Loss of profits, data, or business opportunities</li>
                            <li>Medical malpractice or negligence by partner hospitals</li>
                            <li>Service interruptions or technical failures</li>
                            <li>Third-party actions or content</li>
                        </ul>
                        
                        <h3>7. Intellectual Property</h3>
                        <p>All content, trademarks, and intellectual property on this platform are owned by KCC HealthCard or our licensors. Users may not:</p>
                        <ul>
                            <li>Copy, modify, or distribute our content without permission</li>
                            <li>Use our trademarks or branding without authorization</li>
                            <li>Reverse engineer or attempt to extract our source code</li>
                        </ul>
                        
                        <h3>8. Termination</h3>
                        <p>We reserve the right to terminate or suspend your account at any time for:</p>
                        <ul>
                            <li>Violation of these terms and conditions</li>
                            <li>Fraudulent or illegal activities</li>
                            <li>Misrepresentation of information</li>
                            <li>Abuse of our services or other users</li>
                        </ul>
                        
                        <h3>9. Dispute Resolution</h3>
                        <p>Any disputes arising from these terms shall be resolved through:</p>
                        <ul>
                            <li>Good faith negotiations between parties</li>
                            <li>Mediation by a neutral third party</li>
                            <li>Binding arbitration if mediation fails</li>
                        </ul>
                        
                        <h3>10. Governing Law</h3>
                        <p>These terms and conditions are governed by the laws of India. Any legal proceedings shall be conducted in the courts of Bangalore, Karnataka.</p>
                        
                        <h3>11. Changes to Terms</h3>
                        <p>We reserve the right to modify these terms at any time. Users will be notified of significant changes via email or platform notification. Continued use of our services constitutes acceptance of the modified terms.</p>
                        
                        <h3>12. Contact Information</h3>
                        <p>For questions or concerns regarding these terms and conditions, please contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> legal@kcchealthcard.com</li>
                            <li><strong>Phone:</strong> +91 1234567890</li>
                            <li><strong>Address:</strong> KCC HealthCard, Bangalore, India</li>
                        </ul>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This document is a general overview. For specific legal advice, please consult with a qualified attorney.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
