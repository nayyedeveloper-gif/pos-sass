import './bootstrap';
import './cloud-sync';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

// Livewire 3 initializes Alpine automatically.
// We registers plugins to the window.Alpine instance provided by Livewire.
const registerPlugins = () => {
    window.Alpine.plugin(focus);
    window.Alpine.plugin(collapse);
};

if (window.Alpine) {
    registerPlugins();
} else {
    document.addEventListener('alpine:init', registerPlugins);
}

// This ensures Alpine is available globally for Livewire
window.deferLoadingAlpine = function(callback) {
    window.addEventListener('alpine:init', callback);
};

// Service Worker Registration is handled in layouts/app.blade.php
// No need to register here to avoid conflicts

// Network Printer Helper
window.printToNetwork = async (printerIp, printerPort, content) => {
    try {
        const response = await fetch('/api/print', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                printer_ip: printerIp,
                printer_port: printerPort,
                content: content
            })
        });
        
        return await response.json();
    } catch (error) {
        console.error('Print error:', error);
        throw error;
    }
};

// Format currency helper
window.formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' Ks';
};

// Format date helper
window.formatDate = (date) => {
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
};

// Toast notification helper
window.showToast = (message, type = 'success') => {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        type === 'warning' ? 'bg-yellow-600' : 
        'bg-blue-600'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// Confirm dialog helper
window.confirmDialog = (message) => {
    return new Promise((resolve) => {
        if (confirm(message)) {
            resolve(true);
        } else {
            resolve(false);
        }
    });
};

// Play notification sound
window.playNotificationSound = () => {
    const audio = new Audio('/sounds/notification.mp3');
    audio.play().catch(e => console.log('Audio play failed:', e));
};

// Vibrate device (for mobile)
window.vibrateDevice = (pattern = [200]) => {
    if ('vibrate' in navigator) {
        navigator.vibrate(pattern);
    }
};

// Check online status
window.isOnline = () => {
    return navigator.onLine;
};

// Online/Offline event listeners
window.addEventListener('online', () => {
    showToast('ချိတ်ဆက်မှု ပြန်လည်ရရှိပါပြီ / Connection restored', 'success');
});

window.addEventListener('offline', () => {
    showToast('အင်တာနက်ချိတ်ဆက်မှု ပြတ်တောက်နေပါသည် / No internet connection', 'warning');
});

// Prevent accidental page refresh
window.addEventListener('beforeunload', (e) => {
    // Only show warning if there's unsaved data
    const hasUnsavedData = document.querySelector('[data-unsaved="true"]');
    if (hasUnsavedData) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Auto-logout on inactivity (30 minutes)
let inactivityTimer;
const resetInactivityTimer = () => {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        if (confirm('သင်၏ session သက်တမ်းကုန်ဆုံးပါပြီ။ ထပ်မံ login ဝင်ရန် လိုအပ်ပါသည်။\n\nYour session has expired. Please login again.')) {
            window.location.href = '/logout';
        }
    }, 30 * 60 * 1000); // 30 minutes
};

// Reset timer on user activity
['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
    document.addEventListener(event, resetInactivityTimer, true);
});

// Initialize timer
resetInactivityTimer();

// Print Event Handlers
document.addEventListener('livewire:initialized', () => {
    // Listen for print receipt event
    Livewire.on('print-receipt', async (data) => {
        try {
            // Get order data from the current component
            const orderData = data.orderData || await getCurrentOrderData();
            const printer = window.printerConfig?.receipt;
            
            const response = await fetch(`${window.printAgentUrl}/print`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-print-key': window.printApiKey,
                },
                body: JSON.stringify({
                    printType: 'receipt',
                    orderData: orderData,
                    printerIp: printer?.ip || '192.168.0.66',
                    printerPort: printer?.port || 9100,
                    printerType: printer?.type || 'network'
                })
            });

            const result = await response.json();
            if (result.success) {
                showToast('Receipt printed successfully', 'success');
            } else {
                showToast('Print failed: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Print error:', error);
            showToast('Print failed: ' + error.message, 'error');
        }
    });

    // Listen for print kitchen order event
    Livewire.on('print-kitchen-order', async (data) => {
        try {
            const orderData = data.orderData || await getCurrentOrderData();
            const printer = window.printerConfig?.kitchen;
            
            const response = await fetch(`${window.printAgentUrl}/print`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-print-key': window.printApiKey,
                },
                body: JSON.stringify({
                    printType: 'kitchen',
                    orderData: orderData,
                    printerIp: printer?.ip || '192.168.0.88',
                    printerPort: printer?.port || 9100,
                    printerType: printer?.type || 'network'
                })
            });

            const result = await response.json();
            if (result.success) {
                showToast('Kitchen order printed successfully', 'success');
            } else {
                showToast('Print failed: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Print error:', error);
            showToast('Print failed: ' + error.message, 'error');
        }
    });

    // Listen for print bar order event
    Livewire.on('print-bar-order', async (data) => {
        try {
            const orderData = data.orderData || await getCurrentOrderData();
            const printer = window.printerConfig?.bar;
            
            const response = await fetch(`${window.printAgentUrl}/print`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'x-print-key': window.printApiKey,
                },
                body: JSON.stringify({
                    printType: 'bar',
                    orderData: orderData,
                    printerIp: printer?.ip || '192.168.0.77',
                    printerPort: printer?.port || 9100,
                    printerType: printer?.type || 'network'
                })
            });

            const result = await response.json();
            if (result.success) {
                showToast('Bar order printed successfully', 'success');
            } else {
                showToast('Print failed: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Print error:', error);
            showToast('Print failed: ' + error.message, 'error');
        }
    });
});

// Helper function to get current order data (you may need to implement this based on your Livewire components)
async function getCurrentOrderData() {
    // This is a placeholder - you need to implement how to get the current order data
    // from the Livewire component state
    return {
        orderNumber: 'TEST-001',
        date: new Date().toLocaleDateString(),
        table: 'Table 1',
        items: [
            {
                name: 'Test Item',
                nameEn: 'Test Item English',
                quantity: 1,
                amount: 1000,
                isFoc: false,
                notes: ''
            }
        ],
        subtotal: 1000,
        tax: 50,
        discount: 0,
        total: 1050,
        businessName: 'Teahouse',
        businessAddress: '123 Main St',
        businessPhone: '123-456-7890'
    };
}
