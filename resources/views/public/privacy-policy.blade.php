@extends('layouts.app')

@section('title', 'Privacy Policy - KCC HealthCard')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Privacy Policy</h1>
                <p class="lead">Your privacy is important to us. Learn how we protect and use your information.</p>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <p class="text-muted mb-4">Last updated: {{ date('F d, Y') }}</p>
                        
                        <h3>1. Introduction</h3>
                        <p>KCC HealthCard ("we," "our," or "us") is committed to protecting your privacy and personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our digital health card platform and services.</p>
                        
                        <h3>2. Information We Collect</h3>
                        
                        <h4>2.1 Personal Information</h4>
                        <p>We collect the following types of personal information:</p>
                        <ul>
                            <li><strong>Identity Information:</strong> Name, date of birth, gender, Aadhaar number</li>
                            <li><strong>Contact Information:</strong> Email address, phone number, postal address</li>
                            <li><strong>Health Information:</strong> Blood group, medical history, health card details</li>
                            <li><strong>Documentation:</strong> Aadhaar card, profile pictures, medical certificates</li>
                        </ul>
                        
                        <h4>2.2 Usage Information</h4>
                        <ul>
                            <li>Platform usage patterns and preferences</li>
                            <li>Hospital visits and service utilization</li>
                            <li>Device information and IP addresses</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                        
                        <h3>3. How We Use Your Information</h3>
                        <p>We use your personal information for the following purposes:</p>
                        
                        <h4>3.1 Service Provision</h4>
                        <ul>
                            <li>Creating and managing your digital health card</li>
                            <li>Connecting you with partner hospitals and healthcare providers</li>
                            <li>Processing healthcare service requests and payments</li>
                            <li>Providing customer support and assistance</li>
                        </ul>
                        
                        <h4>3.2 Communication</h4>
                        <ul>
                            <li>Sending important service updates and notifications</li>
                            <li>Providing healthcare reminders and alerts</li>
                            <li>Responding to your inquiries and support requests</li>
                            <li>Marketing communications (with your consent)</li>
                        </ul>
                        
                        <h4>3.3 Platform Improvement</h4>
                        <ul>
                            <li>Analyzing usage patterns to improve our services</li>
                            <li>Developing new features and functionality</li>
                            <li>Conducting research and analytics</li>
                            <li>Ensuring platform security and fraud prevention</li>
                        </ul>
                        
                        <h3>4. Information Sharing and Disclosure</h3>
                        <p>We may share your information in the following circumstances:</p>
                        
                        <h4>4.1 With Healthcare Providers</h4>
                        <ul>
                            <li>Partner hospitals for service delivery</li>
                            <li>Healthcare professionals for treatment purposes</li>
                            <li>Insurance providers for claim processing</li>
                        </ul>
                        
                        <h4>4.2 With Service Providers</h4>
                        <ul>
                            <li>Payment processors for transaction handling</li>
                            <li>Cloud storage providers for data hosting</li>
                            <li>Analytics services for platform improvement</li>
                        </ul>
                        
                        <h4>4.3 Legal Requirements</h4>
                        <ul>
                            <li>Compliance with applicable laws and regulations</li>
                            <li>Response to legal requests and court orders</li>
                            <li>Protection of rights, property, and safety</li>
                        </ul>
                        
                        <h3>5. Data Security</h3>
                        <p>We implement comprehensive security measures to protect your information:</p>
                        <ul>
                            <li><strong>Encryption:</strong> All data is encrypted in transit and at rest</li>
                            <li><strong>Access Controls:</strong> Strict access controls and authentication</li>
                            <li><strong>Regular Audits:</strong> Security assessments and vulnerability testing</li>
                            <li><strong>Staff Training:</strong> Regular privacy and security training for employees</li>
                        </ul>
                        
                        <h3>6. Data Retention</h3>
                        <p>We retain your personal information for as long as necessary to:</p>
                        <ul>
                            <li>Provide our services and fulfill our obligations</li>
                            <li>Comply with legal and regulatory requirements</li>
                            <li>Resolve disputes and enforce our agreements</li>
                            <li>Support business operations and analytics</li>
                        </ul>
                        
                        <h3>7. Your Rights and Choices</h3>
                        <p>You have the following rights regarding your personal information:</p>
                        
                        <h4>7.1 Access and Portability</h4>
                        <ul>
                            <li>Request access to your personal information</li>
                            <li>Receive a copy of your data in a portable format</li>
                            <li>Update or correct inaccurate information</li>
                        </ul>
                        
                        <h4>7.2 Deletion and Restriction</h4>
                        <ul>
                            <li>Request deletion of your personal information</li>
                            <li>Restrict processing of your data</li>
                            <li>Object to certain uses of your information</li>
                        </ul>
                        
                        <h4>7.3 Communication Preferences</h4>
                        <ul>
                            <li>Opt-out of marketing communications</li>
                            <li>Manage notification preferences</li>
                            <li>Control cookie settings</li>
                        </ul>
                        
                        <h3>8. Cookies and Tracking</h3>
                        <p>We use cookies and similar technologies to:</p>
                        <ul>
                            <li>Remember your preferences and settings</li>
                            <li>Analyze platform usage and performance</li>
                            <li>Provide personalized content and recommendations</li>
                            <li>Ensure platform security and functionality</li>
                        </ul>
                        
                        <h3>9. Third-Party Services</h3>
                        <p>Our platform may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies before providing any personal information.</p>
                        
                        <h3>10. International Data Transfers</h3>
                        <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information during such transfers.</p>
                        
                        <h3>11. Children's Privacy</h3>
                        <p>Our services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If we become aware of such collection, we will take steps to delete the information promptly.</p>
                        
                        <h3>12. Changes to This Policy</h3>
                        <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
                        <ul>
                            <li>Posting the updated policy on our platform</li>
                            <li>Sending email notifications to registered users</li>
                            <li>Displaying prominent notices on our platform</li>
                        </ul>
                        
                        <h3>13. Contact Information</h3>
                        <p>If you have questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> privacy@kcchealthcard.com</li>
                            <li><strong>Phone:</strong> +91 1234567890</li>
                            <li><strong>Address:</strong> KCC HealthCard, Bangalore, India</li>
                            <li><strong>Data Protection Officer:</strong> dpo@kcchealthcard.com</li>
                        </ul>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-shield-alt me-2"></i>
                            <strong>Your Privacy Matters:</strong> We are committed to protecting your privacy and ensuring the security of your personal information. If you have any concerns, please don't hesitate to contact us.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
