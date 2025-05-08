<?php

return [
    /*
    |--------------------------------------------------------------------------
    | POS Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the POS system including
    | payment methods, receipt settings, and other POS-related options.
    |
    */

    'payment_methods' => [
        'cash' => 'Cash',
        'card' => 'Credit/Debit Card',
        'gcash' => 'GCash',
        'maya' => 'Maya',
    ],

    'receipt' => [
        'header' => [
            'company_name' => env('APP_NAME', 'Laravel POS'),
            'address' => '123 Main Street, City',
            'phone' => '+1234567890',
            'email' => 'info@example.com',
        ],
        'footer' => [
            'thank_you_message' => 'Thank you for your purchase!',
            'return_policy' => 'Items can be returned within 7 days with receipt',
        ],
    ],

    'tax' => [
        'rate' => 0.12,
        'inclusive' => true,
    ],

    'inventory' => [
        'low_stock_threshold' => 10,
        'enable_notifications' => true,
    ],
];
