<footer>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fas fa-heartbeat"></i> Health Card System</h5>
                <p>Digitizing healthcare access with smart health cards. Get discounts at partner hospitals nationwide.</p>
                <div class="social-links">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 mb-4">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                    <li><a href="{{ route('hospital-network') }}">Hospitals</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h6>For Partners</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('register-hospital') }}">Register Hospital</a></li>
                    <li><a href="{{ route('partner-benefits') }}">Partner Benefits</a></li>
                    <li><a href="{{ route('terms-conditions') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h6>Contact Us</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-phone"></i> +91 1234567890</li>
                    <li><i class="fas fa-envelope"></i> support@kcchealthcard.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> Bangalore, India</li>
                </ul>
            </div>
        </div>
        
        <hr class="bg-secondary">
        
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Health Card System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>