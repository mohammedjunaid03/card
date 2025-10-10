@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="content-wrapper">
        <div class="row align-items-center mx-0">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead">Get in touch with our team for any questions or support</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="content-wrapper">
        <div class="row mx-0">
            <div class="col-lg-6 mb-4">
                <div class="contact-info-card">
                    <h2 class="fw-bold mb-4">Get In Touch</h2>
                    <p class="text-muted mb-4">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Address</h5>
                            <p>123 Healthcare Street<br>Bangalore, Karnataka 560001<br>India</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Phone</h5>
                            <p>+91 1234567890<br>Mon-Sat: 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email</h5>
                            <p>support@kcchealthcard.com<br>info@kcchealthcard.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="contact-form-card">
                    <h4 class="card-title mb-4">Send us a Message</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Your Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Your Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Subject *</label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                       value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Message *</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                          rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 15px 30px; border-radius: 15px; font-weight: 600; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 20px; display: block; visibility: visible; opacity: 1; width: 100%; cursor: pointer;">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection