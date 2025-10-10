/**
 * Health Card System - Dashboard JavaScript
 * Handles all interactive features for the dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dashboard features
    initializeSidebarToggle();
    initializeTooltips();
    initializeAlerts();
    initializeTables();
    initializeForms();
    initializeModals();
});

/**
 * Sidebar Toggle Functionality
 */
function initializeSidebarToggle() {
    console.log('Initializing sidebar toggle...');
    
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    console.log('Sidebar toggle button:', sidebarToggle);
    console.log('Sidebar element:', sidebar);
    console.log('Content element:', content);
    
    if (!sidebarToggle || !sidebar || !content) {
        console.log('Missing elements for sidebar toggle');
        return;
    }
    
    // Check if sidebar state is stored in localStorage
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    console.log('Sidebar initially hidden:', sidebarHidden);
    
    if (sidebarHidden) {
        sidebar.classList.add('hidden');
        content.classList.add('full-width');
        updateToggleIcon(sidebarToggle, true);
        console.log('Sidebar set to hidden state');
    }
    
    sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Sidebar toggle clicked');
        
        const isHidden = sidebar.classList.contains('hidden');
        console.log('Current sidebar state - hidden:', isHidden);
        
        if (isHidden) {
            // Show sidebar
            console.log('Showing sidebar');
            sidebar.classList.remove('hidden');
            content.classList.remove('full-width');
            updateToggleIcon(sidebarToggle, false);
            localStorage.setItem('sidebarHidden', 'false');
        } else {
            // Hide sidebar
            console.log('Hiding sidebar');
            sidebar.classList.add('hidden');
            content.classList.add('full-width');
            updateToggleIcon(sidebarToggle, true);
            localStorage.setItem('sidebarHidden', 'true');
        }
        
        console.log('Sidebar classes after toggle:', sidebar.className);
        console.log('Content classes after toggle:', content.className);
    });
}

function updateToggleIcon(toggleButton, isHidden) {
    if (isHidden) {
        toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
        toggleButton.title = 'Show Sidebar';
    } else {
        toggleButton.innerHTML = '<i class="fas fa-times"></i>';
        toggleButton.title = 'Hide Sidebar';
    }
}

/**
 * Initialize Bootstrap Tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Auto-dismiss alerts after 5 seconds
 */
function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

/**
 * Enhanced table functionality
 */
function initializeTables() {
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Initialize data tables if present
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('.data-table').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }
}

/**
 * Enhanced form functionality
 */
function initializeForms() {
    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(function() {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 10000);
            }
        });
    });
    
    // Add confirmation to delete forms
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Initialize modals
 */
function initializeModals() {
    // Auto-focus on first input in modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        });
    });
}

/**
 * Utility Functions
 */

// Show loading spinner
function showLoading(element) {
    if (element) {
        element.classList.add('loading');
        element.style.pointerEvents = 'none';
    }
}

// Hide loading spinner
function hideLoading(element) {
    if (element) {
        element.classList.remove('loading');
        element.style.pointerEvents = '';
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Create toast container if it doesn't exist
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Format date for display
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

// Debounce function for search inputs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Copied to clipboard!', 'success');
    }).catch(function() {
        showToast('Failed to copy to clipboard', 'danger');
    });
}

// Export functions for global use
window.HealthCardDashboard = {
    showLoading,
    hideLoading,
    showToast,
    formatDate,
    formatCurrency,
    copyToClipboard,
    debounce
};