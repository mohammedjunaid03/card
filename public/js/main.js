// Health Card System - Main JavaScript

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });
    
    // Form validation enhancement
    $('form').on('submit', function(e) {
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<span class="spinner"></span> Processing...').prop('disabled', true);
        
        // Re-enable button after 3 seconds (fallback)
        setTimeout(function() {
            submitBtn.html(originalText).prop('disabled', false);
        }, 3000);
    });
    
    // Auto-hide alerts after 5 seconds
    $('.alert').each(function() {
        var alert = $(this);
        setTimeout(function() {
            alert.fadeOut('slow');
        }, 5000);
    });
    
    // Animated counters
    function animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
    
    // Trigger counter animation when element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Animate counters on scroll
    $(window).on('scroll', function() {
        $('.counter').each(function() {
            if (isInViewport(this) && !$(this).hasClass('animated')) {
                $(this).addClass('animated');
                const endValue = parseInt($(this).data('count'));
                animateCounter(this, 0, endValue, 2000);
            }
        });
    });
    
    // Image lazy loading
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Mobile menu toggle
    $('.navbar-toggler').on('click', function() {
        $('.navbar-collapse').toggleClass('show');
    });
    
    // Close mobile menu when clicking on a link
    $('.navbar-nav .nav-link').on('click', function() {
        $('.navbar-collapse').removeClass('show');
    });
    
    // Back to top button
    const backToTop = $('<button class="btn btn-primary back-to-top" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: none; border-radius: 50%; width: 50px; height: 50px;"><i class="fas fa-arrow-up"></i></button>');
    $('body').append(backToTop);
    
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            backToTop.fadeIn();
        } else {
            backToTop.fadeOut();
        }
    });
    
    backToTop.on('click', function() {
        $('html, body').animate({scrollTop: 0}, 800);
    });
    
    // File upload preview
    $('input[type="file"]').on('change', function() {
        const file = this.files[0];
        const preview = $(this).siblings('.file-preview');
        
        if (file && preview.length) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`);
                };
                reader.readAsDataURL(file);
            } else {
                preview.html(`<div class="alert alert-info"><i class="fas fa-file"></i> ${file.name}</div>`);
            }
        }
    });
    
    // Password strength indicator
    $('input[type="password"]').on('input', function() {
        const password = $(this).val();
        const strengthIndicator = $(this).siblings('.password-strength');
        
        if (strengthIndicator.length) {
            let strength = 0;
            let strengthText = '';
            let strengthClass = '';
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    strengthText = 'Very Weak';
                    strengthClass = 'danger';
                    break;
                case 2:
                    strengthText = 'Weak';
                    strengthClass = 'warning';
                    break;
                case 3:
                    strengthText = 'Fair';
                    strengthClass = 'info';
                    break;
                case 4:
                    strengthText = 'Good';
                    strengthClass = 'success';
                    break;
                case 5:
                    strengthText = 'Strong';
                    strengthClass = 'success';
                    break;
            }
            
            strengthIndicator.html(`<div class="progress" style="height: 5px;"><div class="progress-bar bg-${strengthClass}" style="width: ${(strength / 5) * 100}%"></div></div><small class="text-${strengthClass}">${strengthText}</small>`);
        }
    });
    
    // Search functionality
    $('.search-input').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const searchContainer = $(this).data('search-container');
        
        $(searchContainer).find('.search-item').each(function() {
            const itemText = $(this).text().toLowerCase();
            if (itemText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Confirmation dialogs
    $('.confirm-delete').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const message = $(this).data('message') || 'Are you sure you want to delete this item?';
        
        if (confirm(message)) {
            window.location.href = url;
        }
    });
    
    // Auto-save form data
    $('form[data-autosave]').on('input', function() {
        const form = $(this);
        const formData = form.serialize();
        localStorage.setItem('autosave_' + form.attr('id'), formData);
    });
    
    // Restore auto-saved data
    $('form[data-autosave]').each(function() {
        const form = $(this);
        const savedData = localStorage.getItem('autosave_' + form.attr('id'));
        
        if (savedData) {
            const params = new URLSearchParams(savedData);
            params.forEach((value, key) => {
                form.find(`[name="${key}"]`).val(value);
            });
        }
    });
    
    // Clear auto-saved data on successful form submission
    $('form[data-autosave]').on('submit', function() {
        const form = $(this);
        localStorage.removeItem('autosave_' + form.attr('id'));
    });
    
    // Notification system
    function showNotification(message, type = 'info', duration = 5000) {
        const notification = $(`
            <div class="alert alert-${type} alert-dismissible fade show notification" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.fadeOut(() => {
                notification.remove();
            });
        }, duration);
    }
    
    // Global notification function
    window.showNotification = showNotification;
    
    // AJAX error handling
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        if (xhr.status === 419) {
            showNotification('Session expired. Please refresh the page.', 'warning');
        } else if (xhr.status === 500) {
            showNotification('Server error. Please try again later.', 'danger');
        } else if (xhr.status === 404) {
            showNotification('Requested resource not found.', 'warning');
        }
    });
    
    // Initialize AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
    
    // Print functionality
    $('.print-btn').on('click', function() {
        window.print();
    });
    
    // Copy to clipboard functionality
    $('.copy-btn').on('click', function() {
        const text = $(this).data('copy');
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Copied to clipboard!', 'success', 2000);
        });
    });
    
    // Theme toggle (if implemented)
    $('.theme-toggle').on('click', function() {
        $('body').toggleClass('dark-theme');
        localStorage.setItem('theme', $('body').hasClass('dark-theme') ? 'dark' : 'light');
    });
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        $('body').addClass('dark-theme');
    }
    
    // Initialize date pickers (if datepicker library is loaded)
    if (typeof $.fn.datepicker !== 'undefined') {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
    }
    
    // Initialize time pickers (if timepicker library is loaded)
    if (typeof $.fn.timepicker !== 'undefined') {
        $('.timepicker').timepicker({
            showMeridian: false,
            minuteStep: 15
        });
    }
    
    // Form wizard functionality
    $('.form-wizard .next-btn').on('click', function() {
        const currentStep = $(this).closest('.wizard-step');
        const nextStep = currentStep.next('.wizard-step');
        
        if (nextStep.length) {
            currentStep.hide();
            nextStep.show();
        }
    });
    
    $('.form-wizard .prev-btn').on('click', function() {
        const currentStep = $(this).closest('.wizard-step');
        const prevStep = currentStep.prev('.wizard-step');
        
        if (prevStep.length) {
            currentStep.hide();
            prevStep.show();
        }
    });
    
    // Initialize all components
    console.log('Health Card System initialized successfully!');
});

// Utility functions
const HealthCardUtils = {
    // Format currency
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR'
        }).format(amount);
    },
    
    // Format date
    formatDate: function(date) {
        return new Intl.DateTimeFormat('en-IN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    },
    
    // Validate email
    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    // Validate phone
    validatePhone: function(phone) {
        const re = /^[6-9]\d{9}$/;
        return re.test(phone);
    },
    
    // Generate random string
    generateRandomString: function(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    },
    
    // Debounce function
    debounce: function(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
};

// Make utilities globally available
window.HealthCardUtils = HealthCardUtils;
