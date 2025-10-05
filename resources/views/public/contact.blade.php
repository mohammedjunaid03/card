@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h2 class="fw-bold mb-4">Get In Touch</h2>
                <p class="text-muted mb-4">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                
                <div class="mb-4">
                    <h5><i class="fas fa-map-marker-alt text-primary"></i> Address</h5>
                    <p>123 Healthcare Street<br>Bangalore, Karnataka 560001<br>India</p>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-phone text-primary"></i> Phone</h5>
                    <p>+91 1234567890<br>Mon-Sat: 9:00 AM - 6:00 PM</p>
                </div>
                
                <div class="mb-4">
                    <h5><i class="fas fa-envelope text-primary"></i> Email</h5>
                    <p>support@healthcard.com<br>info@healthcard.com</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Send us a Message</h4>
                        
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
                            
                            <button type="submit" class="btn btn-primary w-100">
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