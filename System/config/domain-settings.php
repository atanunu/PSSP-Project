<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Domain Settings
    |--------------------------------------------------------------------------
    |
    | This configuration file stores the Fully Qualified Domain Names (FQDN)
    | for various modules of the system. The values are pulled from your
    | environment file (.env) using the env() helper. If an environment
    | variable is not set, the default value provided will be used.
    |
    | Usage:
    | To access a domain setting anywhere in your application, use the config()
    | helper. For example, to get the API domain:
    |
    |     $apiDomain = config('domain-settings.api');
    |
    | You can also use these values when defining routes, making HTTP requests,
    | or anywhere you need to reference these domains.
    |
    */

    // API domain for Merchants API Services
    'api'           => env('API_DOMAIN', 'api.example.com'),

    // Domain for the Financial Service Providers Module
    'bankconnect'   => env('BANKCONNECT_DOMAIN', 'bankconnect.example.com'),

    // Domain for Instant Payment Notifications (IPN)
    'ipn'           => env('IPN_DOMAIN', 'ipn.example.com'),

    // Domain for the Notification Module
    'notification'  => env('NOTIFICATION_DOMAIN', 'notification.example.com'),

    // Domain for the Customer Management Module
    'customer'      => env('CUSTOMER_DOMAIN', 'customer.example.com'),

    // Domain for the Merchant Management Module
    'merchant'      => env('MERCHANT_DOMAIN', 'merchant.example.com'),

    // Domain for the Wallet Module
    'wallet'        => env('WALLET_DOMAIN', 'wallets.example.com'),

    // Domain for the Rewards Module (currently the same as Wallet Module)
    'rewards'       => env('REWARDS_DOMAIN', 'wallets.example.com'),
];
