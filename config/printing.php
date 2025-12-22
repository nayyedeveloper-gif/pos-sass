<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Printing Mode
    |--------------------------------------------------------------------------
    |
    | Determines how printing is handled:
    | - 'local': Direct network connection to printers (same LAN)
    | - 'cloud': For VPS/Cloud deployments (requires VPN or print agent)
    |
    */

    'mode' => env('PRINTING_MODE', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Use Print Agent
    |--------------------------------------------------------------------------
    |
    | When in cloud mode, determines whether to use print agent server.
    | If false, assumes VPN connection (Tailscale/ZeroTier) to printers.
    |
    */

    'use_agent' => env('USE_PRINT_AGENT', false),

    /*
    |--------------------------------------------------------------------------
    | Print Agent URL
    |--------------------------------------------------------------------------
    |
    | The URL of the local print agent server when using cloud mode.
    | Example: http://192.168.1.100:3001
    |
    */

    'agent_url' => env('PRINT_AGENT_URL', 'http://localhost:3001'), // Local print agent URL

    /*
    |--------------------------------------------------------------------------
    | Print Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for print operations to complete.
    |
    */

    'timeout' => env('PRINT_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | Auto Print on Order Completion
    |--------------------------------------------------------------------------
    |
    | Automatically print receipts when orders are completed.
    |
    */

    'auto_print_receipt' => env('AUTO_PRINT_RECEIPT', true),

    /*
    |--------------------------------------------------------------------------
    | Auto Print Kitchen/Bar Orders
    |--------------------------------------------------------------------------
    |
    | Automatically print to kitchen/bar when new items are added.
    |
    */

    'auto_print_kitchen' => env('AUTO_PRINT_KITCHEN', true),
    
    'auto_print_nan_pyar' => env('AUTO_PRINT_NAN_PYAR', true),

    'auto_print_bar' => env('AUTO_PRINT_BAR', true),

    // Printer configurations
    'printers' => [
        'receipt' => [
            'ip' => env('RECEIPT_PRINTER_IP', '192.168.0.66'),
            'port' => env('RECEIPT_PRINTER_PORT', 9100),
            'type' => env('RECEIPT_PRINTER_TYPE', 'network'), // 'network' or 'usb'
        ],
        'kitchen' => [
            'ip' => env('KITCHEN_PRINTER_IP', '192.168.0.88'),
            'port' => env('KITCHEN_PRINTER_PORT', 9100),
            'type' => env('KITCHEN_PRINTER_TYPE', 'network'),
        ],
        'nan_pyar' => [
            'ip' => env('NAN_PYAR_PRINTER_IP', '192.168.0.66'),
            'port' => env('NAN_PYAR_PRINTER_PORT', 9100),
            'type' => env('NAN_PYAR_PRINTER_TYPE', 'network'),
        ],
        'bar' => [
            'ip' => env('BAR_PRINTER_IP', '192.168.0.77'),
            'port' => env('BAR_PRINTER_PORT', 9100),
            'type' => env('BAR_PRINTER_TYPE', 'network'),
        ],
    ],
];
