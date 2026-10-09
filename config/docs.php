<?php

return [

    // Root of the live API the "Try It" panels call. Set DOCS_API_BASE to your deployment, e.g. https://offline.example.com/api/v1
    'api_base' => env('DOCS_API_BASE', 'https://api.example.com/api/v1'),

    // Sidebar. section slug => icon (see shell.blade.php), title, pages (slug => title).
    // Each page is resources/views/docs/pages/{section}/{page}.blade.php
    'sections' => [
        'getting-started' => [
            'title' => 'Getting Started', 'icon' => 'home',
            'pages' => [
                'introduction' => 'Introduction',
                'quickstart' => 'Quickstart',
                'authentication' => 'Authentication',
                'idempotency' => 'Idempotency',
                'errors' => 'Errors & Rate Limits',
            ],
        ],
        'api' => [
            'title' => 'API Reference', 'icon' => 'bolt',
            'pages' => [
                'subscribers' => 'Subscribers',
                'merchants' => 'Merchants',
                'codes' => 'Payment Codes',
                'transactions' => 'Transactions',
            ],
        ],
        'webhooks' => [
            'title' => 'Webhooks', 'icon' => 'bell',
            'pages' => [
                'endpoints' => 'Endpoints',
                'events' => 'Events',
                'verifying' => 'Verifying Signatures',
                'delivery' => 'Delivery & Retries',
            ],
        ],
        'payers' => [
            'title' => 'Payers & Settlement', 'icon' => 'phone',
            'pages' => [
                'voice' => 'The Payer\'s Call',
                'numbers' => 'Voice Numbers & Routing',
                'settlement' => 'Settlement Adapter',
                'lifecycle' => 'Code Lifecycle & Reconciliation',
                'going-live' => 'Sandbox & Going Live',
            ],
        ],
        'portal' => [
            'title' => 'Tenant Portal', 'icon' => 'store',
            'pages' => [
                'overview' => 'Overview & Roles',
                'payments' => 'Payments & Reporting',
                'developers' => 'Developer Tools',
                'team' => 'Team & Security',
            ],
        ],
        'operators' => [
            'title' => 'Operators', 'icon' => 'cog',
            'pages' => [
                'console' => 'Operator Console',
                'configuration' => 'Configuration',
                'security' => 'Security Model',
                'commands' => 'Commands & Scheduling',
            ],
        ],
    ],

    // Single-page entries shown below the divider. slug => label (page slug == section slug).
    'standalone' => [
        'clients' => 'Client Libraries',
        'changelog' => 'Changelog',
    ],
];
