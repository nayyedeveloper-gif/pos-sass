/**
 * Local Print Agent Server
 * Run this on a local machine (Windows/Linux/Mac) in the same network as printers
 * VPS will send HTTP requests to this server to trigger printing
 * 
 * Installation:
 * 1. Install Node.js
 * 2. npm install express escpos escpos-network
 * 3. node server.js
 */

const express = require('express');
const escpos = require('escpos');
const Network = require('escpos-network');
const usb = require('escpos-usb');
const app = express();
const PORT = process.env.PORT || 1818;

// USB device (auto-detect first USB printer)
escpos.USB = usb;

// Simple API Key Authentication
const PRINT_API_KEY = process.env.PRINT_API_KEY || 'pos-pro-2025';

// Authentication middleware
app.use((req, res, next) => {
    // Skip auth for health check
    if (req.path === '/health') {
        return next();
    }
    
    const authKey = req.headers['x-print-key'];
    if (!authKey || authKey !== PRINT_API_KEY) {
        return res.status(401).json({ error: 'Unauthorized - Invalid API Key' });
    }
    next();
});

// CORS - Allow domain access
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*'); // Allow all origins
    res.header('Access-Control-Allow-Headers', 'Content-Type, x-print-key, Authorization');
    res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    next();
});

// Health check
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        message: 'Print Agent is running',
        timestamp: new Date().toISOString()
    });
});

// Helper function for receipt printing
async function handleReceiptPrint(req, res) {
    const { printerIp, printerPort, orderData, printerType = 'network' } = req.body;

    let device;
    
    // Select device type
    if (printerType === 'usb') {
        // Auto-detect first USB printer
        device = new escpos.USB();
    } else {
        // Network printer
        if (!printerIp || !printerPort) {
            return res.status(400).json({ 
                success: false, 
                error: 'Missing printerIp or printerPort for network printer' 
            });
        }
        device = new Network(printerIp, printerPort);
    }

    device.open(async (error) => {
        if (error) {
            console.error('Printer connection error:', error);
            return res.status(500).json({ 
                success: false, 
                error: 'Failed to connect to printer: ' + error.message 
            });
        }

        try {
            const printer = new escpos.Printer(device);

            // Print header
            printer
                .align('CT')
                .size(2, 2)
                .text(orderData.businessName || 'Restaurant')
                .size(1, 1)
                .text(orderData.businessAddress || '')
                .text(orderData.businessPhone || '')
                .text('================================')
                .align('LT')
                .text(`Order: ${orderData.orderNumber}`)
                .text(`Date: ${orderData.date}`)
                .text(`Table: ${orderData.table || 'Takeaway'}`)
                .text('--------------------------------');

            // Print items
            printer.text('Item                     Qty     Amount');
            printer.text('------------------------------------------------');
            
            orderData.items.forEach(item => {
                const name = item.name.substring(0, 23).padEnd(23);
                const qty = String(item.quantity).padStart(3);
                printer.text(`${name} ${qty}     `);
                printer.style('B'); // Bold for amount
                printer.text(String(item.amount).padStart(8));
                printer.style('NORMAL'); // Reset style
                printer.text('');
            });

            printer.text('================================================');

            // Print totals
            printer
                .align('RT')
                .text('Subtotal: ');
            printer.style('B'); // Bold for amount
            printer.text(`${orderData.subtotal} Ks`);
            printer.style('NORMAL'); // Reset style
            printer.text('');
            
            if (orderData.tax > 0) {
                printer.text(`Tax (${orderData.taxPercentage}%): `);
                printer.style('B');
                printer.text(`${orderData.tax} Ks`);
                printer.style('NORMAL');
                printer.text('');
            }
            
            if (orderData.discount > 0) {
                printer.text(`Discount (${orderData.discountPercentage}%): -`);
                printer.style('B');
                printer.text(`${orderData.discount} Ks`);
                printer.style('NORMAL');
                printer.text('');
            }
            
            if (orderData.serviceCharge > 0) {
                printer.text('Service Charge: ');
                printer.style('B');
                printer.text(`${orderData.serviceCharge} Ks`);
                printer.style('NORMAL');
                printer.text('');
            }
            
            // Add payment information if available
            if (orderData.paidAmount && orderData.paidAmount > 0) {
                printer.text('================================================');
                printer.text('Paid: ');
                printer.style('B');
                printer.text(`${orderData.paidAmount} Ks`);
                printer.style('NORMAL');
                printer.text('');
                
                if (orderData.changeAmount && orderData.changeAmount > 0) {
                    printer.text('Change: ');
                    printer.style('B');
                    printer.text(`${orderData.changeAmount} Ks`);
                    printer.style('NORMAL');
                    printer.text('');
                }
            }
            
            printer.text('================================================')
                .size(2, 1)
                .style('B');
            printer.text(`TOTAL: ${orderData.total} Ks`);
            printer.style('NORMAL');
            printer.size(1, 1)
                .text('================================================');

            // Print footer
            printer
                .align('CT')
                .text('')
                .text('Thank You!')
                .text('Please Come Again')
                .text('')
                .feed(3)
                .cut()
                .close();

            res.json({ 
                success: true, 
                message: 'Receipt printed successfully' 
            });

        } catch (printError) {
            console.error('Print error:', printError);
            res.status(500).json({ 
                success: false, 
                error: 'Print failed: ' + printError.message 
            });
        }
    });
}

// Helper function for kitchen printing
async function handleKitchenPrint(req, res) {
    const { printerIp, printerPort, orderData, printerType = 'network' } = req.body;

    let device;
    
    if (printerType === 'usb') {
        device = new escpos.USB();
    } else {
        if (!printerIp || !printerPort) {
            return res.status(400).json({ 
                success: false, 
                error: 'Missing printerIp or printerPort' 
            });
        }
        device = new Network(printerIp, printerPort);
    }

    device.open(async (error) => {
        if (error) {
            return res.status(500).json({ 
                success: false, 
                error: 'Failed to connect to printer' 
            });
        }

        const printer = new escpos.Printer(device);

        printer
            .align('CT')
            .size(2, 2)
            .text('KITCHEN ORDER')
            .size(1, 1)
            .text('--------------------------------')
            .align('LT');
        
        // Order info
        printMyanmarText(printer, 'Order: ' + orderData.orderNumber, 1);
        printMyanmarText(printer, 'Table: ' + (orderData.table || 'Takeaway'), 1);
        printMyanmarText(printer, 'Time: ' + orderData.time, 1);
        
        printer.text('--------------------------------');

        // Print items
        orderData.items.forEach(item => {
            // Item name with quantity - use Myanmar text if available
            const itemText = `x${item.quantity}  ${item.name || item.nameEn}`;
            printMyanmarText(printer, itemText, 1);
            
            // Print English name if different
            if (item.nameEn && item.name && item.nameEn !== item.name) {
                printer.text(`     ${item.nameEn}`);
            }
            
            // Print notes if available
            if (item.notes) {
                printMyanmarText(printer, `   Notes: ${item.notes}`, 1);
            }
            
            // Print FOC if applicable
            if (item.isFoc) {
                printer.text('   ** FOC **');
            }
            
            printer.text('');
        });

        printer
            .text('--------------------------------')
            .feed(2)
            .cut()
            .close();

        res.json({ success: true, message: 'Kitchen order printed' });
    });
}

// Helper function for bar printing
async function handleBarPrint(req, res) {
    const { printerIp, printerPort, orderData, printerType = 'network' } = req.body;

    let device;
    
    if (printerType === 'usb') {
        device = new escpos.USB();
    } else {
        if (!printerIp || !printerPort) {
            return res.status(400).json({ 
                success: false, 
                error: 'Missing printerIp or printerPort' 
            });
        }
        device = new Network(printerIp, printerPort);
    }

    device.open(async (error) => {
        if (error) {
            return res.status(500).json({ 
                success: false, 
                error: 'Failed to connect to printer' 
            });
        }

        const printer = new escpos.Printer(device);

        printer
            .align('CT')
            .size(2, 2)
            .text('BAR ORDER')
            .size(1, 1)
            .text('--------------------------------')
            .align('LT');
        
        // Order info
        printMyanmarText(printer, 'Order: ' + orderData.orderNumber, 1);
        printMyanmarText(printer, 'Table: ' + (orderData.table || 'Takeaway'), 1);
        printMyanmarText(printer, 'Time: ' + orderData.time, 1);
        
        printer.text('--------------------------------');

        // Print items
        orderData.items.forEach(item => {
            // Item name with quantity - use Myanmar text if available
            const itemText = `x${item.quantity}  ${item.name || item.nameEn}`;
            printMyanmarText(printer, itemText, 1);
            
            // Print English name if different
            if (item.nameEn && item.name && item.nameEn !== item.name) {
                printer.text(`     ${item.nameEn}`);
            }
            
            // Print notes if available
            if (item.notes) {
                printMyanmarText(printer, `   Notes: ${item.notes}`, 1);
            }
            
            // Print FOC if applicable
            if (item.isFoc) {
                printer.text('   ** FOC **');
            }
            
            printer.text('');
        });

        printer
            .text('--------------------------------')
            .feed(2)
            .cut()
            .close();

        res.json({ success: true, message: 'Bar order printed' });
    });
}

// Print endpoint (unified for all print types)
app.post('/print', async (req, res) => {
    const { printType, orderData, printerIp, printerPort, printerType = 'network' } = req.body;

    if (!orderData || !printType) {
        return res.status(400).json({ 
            success: false, 
            error: 'Missing required fields: orderData and printType' 
        });
    }

    if (!['receipt', 'kitchen', 'bar'].includes(printType)) {
        return res.status(400).json({ 
            success: false, 
            error: 'Invalid printType. Must be: receipt, kitchen, or bar' 
        });
    }

    try {
        // Route to appropriate print function
        switch (printType) {
            case 'receipt':
                return await handleReceiptPrint(req, res);
            case 'kitchen':
                return await handleKitchenPrint(req, res);
            case 'bar':
                return await handleBarPrint(req, res);
        }
    } catch (err) {
        console.error('Print error:', err);
        res.status(500).json({ 
            success: false, 
            error: 'Print failed: ' + err.message 
        });
    }
});

// Print kitchen order endpoint
app.post('/print/kitchen', async (req, res) => {
    const { printerIp, printerPort, orderData, printerType = 'network' } = req.body;

    try {
        let device;
        
        if (printerType === 'usb') {
            device = new escpos.USB();
        } else {
            if (!printerIp || !printerPort) {
                return res.status(400).json({ 
                    success: false, 
                    error: 'Missing printerIp or printerPort' 
                });
            }
            device = new Network(printerIp, printerPort);
        }

        device.open(async (error) => {
            if (error) {
                return res.status(500).json({ 
                    success: false, 
                    error: 'Failed to connect to printer' 
                });
            }

            const printer = new escpos.Printer(device);

            printer
                .align('CT')
                .size(2, 2)
                .text('KITCHEN ORDER')
                .size(1, 1)
                .text('--------------------------------')
                .align('LT');
            
            // Order info
            printMyanmarText(printer, `Order: ${orderData.orderNumber}`, 1);
            printMyanmarText(printer, `Table: ${orderData.table || 'Takeaway'}`, 1);
            printMyanmarText(printer, `Time: ${orderData.time}`, 1);
            
            printer.text('--------------------------------');

            // Print items
            orderData.items.forEach(item => {
                // Item name with quantity - use Myanmar text if available
                const itemText = `x${item.quantity}  ${item.name || item.nameEn}`;
                printMyanmarText(printer, itemText, 1);
                
                // Print English name if different
                if (item.nameEn && item.name && item.nameEn !== item.name) {
                    printer.text(`     ${item.nameEn}`);
                }
                
                // Print notes if available
                if (item.notes) {
                    printMyanmarText(printer, `   Notes: ${item.notes}`, 1);
                }
                
                // Print FOC if applicable
                if (item.isFoc) {
                    printer.text('   ** FOC **');
                }
                
                printer.text('');
            });

            printer
                .text('--------------------------------')
                .feed(2)
                .cut()
                .close();

            res.json({ success: true, message: 'Kitchen order printed' });
        });

    } catch (err) {
        res.status(500).json({ success: false, error: err.message });
    }
});

// Myanmar text printing helper function
function printMyanmarText(printer, text, fontSize = 1) {
    // For now, just use regular text since Myanmar text rendering requires image generation
    // This is a simplified version - in a full implementation, you'd generate images
    printer.text(text);
}

app.listen(PORT, '0.0.0.0', () => {
    console.log(`🖨️  Print Agent Server running on port ${PORT}`);
    console.log(`📡 Access from VPS: http://YOUR_LOCAL_IP:${PORT}`);
    console.log(`💚 Health check: http://localhost:${PORT}/health`);
});
