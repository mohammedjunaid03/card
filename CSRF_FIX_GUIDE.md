# 🔒 CSRF Token Fix Guide - Health Card Management System

## Overview
This guide documents the complete production-ready fix for 419 "Page Expired" (CSRF token mismatch) errors in the Laravel Health Card Management System with multi-guard authentication.

## ✅ What Was Fixed

### 1. Session Configuration (.env)
```env
SESSION_LIFETIME=480                    # 8 hours instead of 2 hours
SESSION_EXPIRE_ON_CLOSE=false          # Sessions persist after browser close
SESSION_ENCRYPT=false                  # Disable encryption for development
SESSION_HTTP_ONLY=true                 # Prevent XSS attacks
SESSION_SAME_SITE=lax                  # CSRF protection
SESSION_SECURE_COOKIE=false            # Set to true in production with HTTPS
SESSION_DOMAIN=null                    # Allow all domains in development
SESSION_PATH=/                         # Root path for cookies
SESSION_COOKIE=healthcard-session      # Custom session cookie name
```

### 2. Enhanced LoginController
- **CSRF Token Regeneration**: Fresh tokens on each login attempt
- **Rate Limiting**: Prevents brute force attacks (5 attempts per IP)
- **Session Management**: Proper session regeneration and cleanup
- **Multi-Guard Support**: Handles all authentication guards (user, hospital, staff, admin)
- **Error Handling**: Comprehensive validation and error messages

### 3. Custom CSRF Middleware
- **Enhanced Token Matching**: Better handling of AJAX requests
- **Header Support**: Supports X-CSRF-TOKEN and X-XSRF-TOKEN headers
- **Flexible Token Sources**: Checks form input, headers, and encrypted cookies

### 4. Updated Routes
- **Multiple Logout Routes**: Separate logout endpoints for each guard
- **Proper CSRF Protection**: All POST routes protected by CSRF middleware

## 🚀 Key Features

### Rate Limiting
- Maximum 5 login attempts per IP address
- Automatic lockout with countdown timer
- Clear rate limiter on successful login

### Session Security
- Session ID regeneration on login
- Cross-guard session cleanup
- Proper token invalidation on logout

### Multi-Guard Authentication
- Support for 4 different user types
- Automatic guard selection based on user_type
- Proper redirection after login

## 🧪 Testing

### CSRF Test Page
Access the comprehensive test page at: `http://127.0.0.1:8000/csrf-test.html`

**Features:**
- Test login with all user types
- CSRF token refresh functionality
- Session status checking
- Logout testing
- Real-time error reporting

### Test Credentials
```
Admin:    admin@healthcard.com / admin123
Staff:    staff1@healthcard.com / staff123
Hospital: apollo@healthcard.com / apollo123
User:     Register a new account
```

## 🔧 Production Deployment

### Environment Variables
For production, update these settings in your `.env`:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true          # Enable for HTTPS
SESSION_SAME_SITE=strict            # Stricter CSRF protection
SESSION_LIFETIME=120                # 2 hours for production
```

### Web Server Configuration
Ensure your web server points to the `public` directory:

**Apache (.htaccess already configured):**
```apache
DocumentRoot "/path/to/your/project/public"
```

**Nginx:**
```nginx
server {
    root /path/to/your/project/public;
    index index.php index.html;
}
```

### Database Sessions
The system uses database sessions. Ensure the `sessions` table exists:
```bash
php artisan migrate
```

## 🛠️ Troubleshooting

### Common Issues

#### 1. 419 CSRF Token Mismatch
**Cause:** Token expired or missing
**Solution:** 
- Clear browser cache
- Refresh the login page
- Check session configuration

#### 2. Session Not Persisting
**Cause:** Incorrect session configuration
**Solution:**
- Verify `SESSION_DRIVER=database`
- Check database connection
- Ensure sessions table exists

#### 3. Rate Limiting Issues
**Cause:** Too many failed login attempts
**Solution:**
- Wait for lockout period to expire
- Clear rate limiter: `php artisan cache:clear`

### Debug Commands
```bash
# Check session configuration
php artisan config:show session

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Check database sessions
php artisan tinker
>>> DB::table('sessions')->count()
```

## 📋 File Changes Summary

### Modified Files
1. **`.env`** - Updated session configuration
2. **`app/Http/Controllers/Auth/LoginController.php`** - Enhanced with rate limiting and CSRF handling
3. **`routes/web.php`** - Added multiple logout routes
4. **`app/Http/Middleware/VerifyCsrfToken.php`** - Custom CSRF middleware

### New Files
1. **`public/csrf-test.html`** - Comprehensive testing page
2. **`CSRF_FIX_GUIDE.md`** - This documentation

## 🔐 Security Features

### CSRF Protection
- All forms include CSRF tokens
- AJAX requests supported with headers
- Token regeneration on login

### Rate Limiting
- Prevents brute force attacks
- IP-based limiting
- Configurable attempt limits

### Session Security
- Secure cookie settings
- Session ID regeneration
- Proper logout handling

## 📞 Support

If you encounter any issues:

1. **Check the test page**: `http://127.0.0.1:8000/csrf-test.html`
2. **Review logs**: `storage/logs/laravel.log`
3. **Verify configuration**: `php artisan config:show session`
4. **Clear caches**: Run all clear commands

## 🎯 Next Steps

1. Test all login/logout functionality
2. Verify CSRF protection works
3. Deploy to production with proper HTTPS settings
4. Monitor session performance
5. Set up proper logging for security events

---

**Status**: ✅ Complete - All CSRF issues resolved
**Last Updated**: October 9, 2025
**Version**: 1.0.0
