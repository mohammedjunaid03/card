// Health Card System - Dashboard JavaScript

$(document).ready(function() {
    // Initialize dashboard components
    initializeDashboard();
    
    // Sidebar toggle functionality
    $('.sidebar-toggle').on('click', function() {
        $('#sidebar').toggleClass('collapsed');
        $('#content').toggleClass('expanded');
        
        // Save sidebar state
        const isCollapsed = $('#sidebar').hasClass('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    });
    
    // Load sidebar state
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        $('#sidebar').addClass('collapsed');
        $('#content').addClass('expanded');
    }
    
    // Mobile sidebar toggle
    $('.mobile-sidebar-toggle').on('click', function() {
        $('#sidebar').toggleClass('show');
    });
    
    // Close mobile sidebar when clicking outside
    $(document).on('click', function(e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('#sidebar, .mobile-sidebar-toggle').length) {
                $('#sidebar').removeClass('show');
            }
        }
    });
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-bs-toggle="popover"]').popover();
    
    // Auto-refresh dashboard data
    if ($('.auto-refresh').length) {
        setInterval(function() {
            refreshDashboardData();
        }, 30000); // Refresh every 30 seconds
    }
    
    // Real-time notifications
    if (typeof window.Echo !== 'undefined') {
        // Listen for notifications
        window.Echo.channel('notifications')
            .listen('NotificationSent', (e) => {
                showNotification(e.notification.message, e.notification.type);
                updateNotificationBadge();
            });
    }
    
    // Form validation
    $('.dashboard-form').on('submit', function(e) {
        if (!validateForm($(this))) {
            e.preventDefault();
        }
    });
    
    // Data table initialization
    if ($.fn.DataTable) {
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
    
    // Chart initialization
    initializeCharts();
    
    // File upload handling
    $('.file-upload').on('change', function() {
        handleFileUpload($(this));
    });
    
    // Search functionality
    $('.search-input').on('input', function() {
        const searchTerm = $(this).val();
        const targetTable = $(this).data('target');
        searchTable(targetTable, searchTerm);
    });
    
    // Bulk actions
    $('.bulk-action-btn').on('click', function() {
        const action = $(this).data('action');
        const selectedItems = getSelectedItems();
        
        if (selectedItems.length === 0) {
            showNotification('Please select items to perform this action', 'warning');
            return;
        }
        
        performBulkAction(action, selectedItems);
    });
    
    // Select all checkbox
    $('.select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.item-checkbox').prop('checked', isChecked);
        updateBulkActionButtons();
    });
    
    // Individual item checkbox
    $('.item-checkbox').on('change', function() {
        updateBulkActionButtons();
    });
    
    // Confirmation dialogs
    $('.confirm-action').on('click', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        const message = $(this).data('message') || 'Are you sure you want to perform this action?';
        
        if (confirm(message)) {
            const url = $(this).attr('href');
            if (url) {
                window.location.href = url;
            } else {
                // Handle AJAX action
                performAction(action, $(this).data('id'));
            }
        }
    });
    
    // Auto-save functionality
    $('.auto-save').on('input', function() {
        const form = $(this).closest('form');
        autoSaveForm(form);
    });
    
    // Export functionality
    $('.export-btn').on('click', function() {
        const format = $(this).data('format');
        const table = $(this).data('table');
        exportData(table, format);
    });
    
    // Print functionality
    $('.print-btn').on('click', function() {
        const target = $(this).data('target') || 'body';
        printElement(target);
    });
    
    // Theme toggle
    $('.theme-toggle').on('click', function() {
        toggleTheme();
    });
    
    // Load saved theme
    loadTheme();
    
    // Initialize all dashboard components
    console.log('Dashboard initialized successfully!');
});

// Dashboard initialization function
function initializeDashboard() {
    // Load dashboard statistics
    loadDashboardStats();
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Setup keyboard shortcuts
    setupKeyboardShortcuts();
    
    // Initialize dashboard widgets
    initializeWidgets();
}

// Load dashboard statistics
function loadDashboardStats() {
    $.ajax({
        url: '/api/dashboard/stats',
        method: 'GET',
        success: function(data) {
            updateStatsCards(data);
        },
        error: function(xhr) {
            console.error('Failed to load dashboard stats:', xhr);
        }
    });
}

// Update statistics cards
function updateStatsCards(stats) {
    Object.keys(stats).forEach(function(key) {
        const element = $(`[data-stat="${key}"]`);
        if (element.length) {
            animateCounter(element[0], 0, stats[key], 2000);
        }
    });
}

// Animate counter
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

// Refresh dashboard data
function refreshDashboardData() {
    $('.auto-refresh').each(function() {
        const url = $(this).data('refresh-url');
        if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $(this).html(data);
                }.bind(this)
            });
        }
    });
}

// Show notification
function showNotification(message, type = 'info', duration = 5000) {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show notification" 
             style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
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

// Update notification badge
function updateNotificationBadge() {
    $.ajax({
        url: '/api/notifications/unread-count',
        method: 'GET',
        success: function(count) {
            const badge = $('.notification-badge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }
    });
}

// Validate form
function validateForm(form) {
    let isValid = true;
    
    form.find('[required]').each(function() {
        const field = $(this);
        const value = field.val().trim();
        
        if (!value) {
            field.addClass('is-invalid');
            isValid = false;
        } else {
            field.removeClass('is-invalid');
        }
    });
    
    return isValid;
}

// Initialize charts
function initializeCharts() {
    // Chart.js charts
    if (typeof Chart !== 'undefined') {
        $('.chart-container').each(function() {
            const canvas = $(this).find('canvas')[0];
            const config = $(this).data('chart-config');
            
            if (canvas && config) {
                new Chart(canvas, config);
            }
        });
    }
}

// Handle file upload
function handleFileUpload(input) {
    const file = input[0].files[0];
    const preview = input.siblings('.file-preview');
    
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
}

// Search table
function searchTable(tableSelector, searchTerm) {
    const table = $(tableSelector);
    const rows = table.find('tbody tr');
    
    rows.each(function() {
        const row = $(this);
        const text = row.text().toLowerCase();
        
        if (text.includes(searchTerm.toLowerCase())) {
            row.show();
        } else {
            row.hide();
        }
    });
}

// Get selected items
function getSelectedItems() {
    const selected = [];
    $('.item-checkbox:checked').each(function() {
        selected.push($(this).val());
    });
    return selected;
}

// Update bulk action buttons
function updateBulkActionButtons() {
    const selectedCount = $('.item-checkbox:checked').length;
    const bulkButtons = $('.bulk-action-btn');
    
    if (selectedCount > 0) {
        bulkButtons.prop('disabled', false);
        bulkButtons.find('.count').text(`(${selectedCount})`);
    } else {
        bulkButtons.prop('disabled', true);
        bulkButtons.find('.count').text('');
    }
}

// Perform bulk action
function performBulkAction(action, items) {
    const message = `Are you sure you want to ${action} ${items.length} item(s)?`;
    
    if (confirm(message)) {
        $.ajax({
            url: '/api/bulk-action',
            method: 'POST',
            data: {
                action: action,
                items: items,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showNotification(response.message, 'success');
                location.reload();
            },
            error: function(xhr) {
                showNotification('Failed to perform bulk action', 'danger');
            }
        });
    }
}

// Perform action
function performAction(action, id) {
    $.ajax({
        url: `/api/action/${action}/${id}`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            showNotification(response.message, 'success');
            location.reload();
        },
        error: function(xhr) {
            showNotification('Failed to perform action', 'danger');
        }
    });
}

// Auto-save form
function autoSaveForm(form) {
    const formData = form.serialize();
    const formId = form.attr('id');
    
    localStorage.setItem(`autosave_${formId}`, formData);
    
    // Show auto-save indicator
    const indicator = form.find('.auto-save-indicator');
    if (indicator.length) {
        indicator.text('Auto-saved').fadeIn().delay(2000).fadeOut();
    }
}

// Export data
function exportData(table, format) {
    const tableData = $(table).DataTable().data().toArray();
    
    $.ajax({
        url: '/api/export',
        method: 'POST',
        data: {
            table: table,
            format: format,
            data: tableData,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            window.open(response.download_url, '_blank');
        },
        error: function(xhr) {
            showNotification('Failed to export data', 'danger');
        }
    });
}

// Print element
function printElement(selector) {
    const element = $(selector);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .no-print { display: none !important; }
                </style>
            </head>
            <body>
                ${element.html()}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}

// Toggle theme
function toggleTheme() {
    $('body').toggleClass('dark-theme');
    const isDark = $('body').hasClass('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Load theme
function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        $('body').addClass('dark-theme');
    }
}

// Setup keyboard shortcuts
function setupKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl + S: Save form
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('.dashboard-form').submit();
        }
        
        // Ctrl + K: Focus search
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            $('.search-input').focus();
        }
        
        // Escape: Close modals
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });
}

// Initialize real-time updates
function initializeRealTimeUpdates() {
    // WebSocket connection for real-time updates
    if (typeof window.Echo !== 'undefined') {
        // Listen for dashboard updates
        window.Echo.channel('dashboard')
            .listen('DashboardUpdated', (e) => {
                updateDashboardData(e.data);
            });
    }
}

// Update dashboard data
function updateDashboardData(data) {
    // Update specific dashboard components based on data
    if (data.stats) {
        updateStatsCards(data.stats);
    }
    
    if (data.notifications) {
        updateNotificationBadge();
    }
}

// Initialize widgets
function initializeWidgets() {
    // Initialize any custom dashboard widgets
    $('.widget').each(function() {
        const widget = $(this);
        const type = widget.data('widget-type');
        
        switch (type) {
            case 'chart':
                initializeChartWidget(widget);
                break;
            case 'table':
                initializeTableWidget(widget);
                break;
            case 'stats':
                initializeStatsWidget(widget);
                break;
        }
    });
}

// Initialize chart widget
function initializeChartWidget(widget) {
    const canvas = widget.find('canvas')[0];
    const config = widget.data('chart-config');
    
    if (canvas && config && typeof Chart !== 'undefined') {
        new Chart(canvas, config);
    }
}

// Initialize table widget
function initializeTableWidget(widget) {
    const table = widget.find('table');
    
    if (table.length && $.fn.DataTable) {
        table.DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']]
        });
    }
}

// Initialize stats widget
function initializeStatsWidget(widget) {
    const statValue = widget.find('.stat-value');
    const targetValue = parseInt(statValue.data('value'));
    
    if (targetValue) {
        animateCounter(statValue[0], 0, targetValue, 2000);
    }
}

// Global dashboard functions
window.DashboardUtils = {
    showNotification: showNotification,
    refreshDashboardData: refreshDashboardData,
    exportData: exportData,
    printElement: printElement,
    toggleTheme: toggleTheme
};