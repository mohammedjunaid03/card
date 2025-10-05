$(document).ready(function() {
    // Sidebar toggle
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
    });
    
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Confirm delete actions
    $('[data-confirm]').on('click', function(e) {
        if (!confirm($(this).data('confirm'))) {
            e.preventDefault();
            return false;
        }
    });
    
    // DataTables initialization (if using DataTables)
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });
    }
    
    // Charts initialization
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Popovers
    $('[data-bs-toggle="popover"]').popover();
});

// Chart initialization function
function initializeCharts() {
    // Example: Line chart for trends
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Cards Issued',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

// QR Code Scanner (if using)
function initQRScanner() {
    // Implementation for QR code scanning
    console.log('QR Scanner initialized');
}

// Export functions
function exportTable(format) {
    alert('Exporting to ' + format);
    // Implementation for export functionality
}