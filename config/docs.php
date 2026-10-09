<?php

return [

    // Root of the live API the "Try It" panels call. Override per environment.
    'api_base' => env('DOCS_API_BASE', 'https://nunu.pakapay.ng/api'),

    // Sidebar. section slug => icon (see shell.blade.php), title, pages (slug => title).
    // Each page is resources/views/docs/pages/{section}/{page}.blade.php
    'sections' => [
        'getting-started' => [
            'title' => 'Getting Started',
            'icon' => 'home',
            'pages' => [
                'introduction' => 'Introduction',
                'response-format' => 'Response Format',
                'authentication' => 'Authentication',
                'errors' => 'Errors & Status Codes',
                'rate-limits' => 'Rate Limits',
            ],
        ],
        'account' => [
            'title' => 'Account & Profile',
            'icon' => 'user',
            'pages' => [
                'current-user' => 'Current User',
                'profile' => 'Profile & Contact Details',
                'bank-accounts' => 'Bank Accounts',
            ],
        ],
        'payments' => [
            'title' => 'Payments',
            'icon' => 'wallet',
            'pages' => [
                'sending' => 'Sending Money',
                'receiving' => 'Receiving Money (QR)',
                'activity' => 'Activity & Reports',
            ],
        ],
        'payment-points' => [
            'title' => 'Payment Points',
            'icon' => 'store',
            'pages' => [
                'overview' => 'Overview & Management',
                'sharing' => 'Sharing & Reporting',
            ],
        ],
        'security' => [
            'title' => 'Security & Identity',
            'icon' => 'shield',
            'pages' => [
                'settings' => 'Security Settings',
                'pin-management' => 'PIN & Spending Limits',
                'kyc' => 'KYC Verification',
            ],
        ],
        'offline' => [
            'title' => 'Offline Payments',
            'icon' => 'phone',
            'pages' => [
                'overview' => 'How It Works',
                'device-keys' => 'Device Keys',
                'code-format' => 'Code Format & Signing',
                'voice-webhook' => 'Voice Webhook',
            ],
        ],
        'integrations' => [
            'title' => 'Partners & Webhooks',
            'icon' => 'bell',
            'pages' => [
                'webhooks' => 'Inbound Webhooks',
                'services' => 'Third-party Services',
            ],
        ],
        'platform' => [
            'title' => 'Platform (Internal)',
            'icon' => 'cog',
            'pages' => [
                'security-model' => 'Security Model',
                'fraud-engine' => 'Fraud Engine',
                'admin-dashboard' => 'Admin Dashboard',
                'configuration' => 'Configuration',
            ],
        ],
    ],

    // Single-page entries shown below the divider. slug => label (page slug == section slug).
    'standalone' => [
        'changelog' => 'Changelog',
    ],
];
