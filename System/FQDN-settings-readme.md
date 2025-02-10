Below are the steps to add the specified domain values to your Laravel 11 project:

---

## 1. Update Your **.env** File

Open your project's **.env** file and add the following lines. These environment variables store the Fully Qualified Domain Names (FQDN) for various modules in your system:

```dotenv
# Domain Settings for the various API endpoints and modules

# FQDN to access Merchants API Services
API_DOMAIN=api.pssp.co.biz.ng

# FQDN to access the System's Financial Service Providers Module
BANKCONNECT_DOMAIN=bankconnect.pssp.co.biz.ng

# FQDN for Instant Payment Notifications from Financial Service Providers
IPN_DOMAIN=ipn.pssp.co.biz.ng

# FQDN to access the System's Notification Module
NOTIFICATION_DOMAIN=notification.pssp.co.biz.ng

# FQDN to access the System's Customer Management Module
CUSTOMER_DOMAIN=customer.pssp.co.biz.ng

# FQDN to access the System's Merchant Management Module
MERCHANT_DOMAIN=merchant.pssp.co.biz.ng

# FQDN to access the System's Wallet Module
WALLET_DOMAIN=wallets.pssp.co.biz.ng

# FQDN to access the System's Rewards Module (currently the same as Wallet Module)
REWARDS_DOMAIN=wallets.pssp.co.biz.ng
```

> **Note:** When deploying your application, make sure that these environment variables are set correctly in your production environment.

---

## 2. Create a Custom Configuration File

Next, create a configuration file named `domain-settings.php` in your project's `config` directory.

### File: `config/domain-settings.php`

```php
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
```

---

## 3. Usage Instructions

You can now access these domain values anywhere in your Laravel application using the `config()` helper function.

### Example

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function show()
    {
        // Retrieve the API domain from the configuration file
        $apiDomain = config('domain-settings.api');
        
        // Use the domain in your logic (e.g., constructing an API URL)
        $apiUrl = "https://{$apiDomain}/endpoint";

        // Return or use the constructed URL as needed
        return view('example', compact('apiUrl'));
    }
}
```

### Additional Notes

- **Caching Configuration:**  
  When deploying to production, remember to cache your configuration for improved performance using:
  
  ```bash
  php artisan config:cache
  ```
  
  If you update any environment variables or configuration files, clear the cache with:
  
  ```bash
  php artisan config:clear
  ```

- **Default Values:**  
  The second parameter in the `env()` helper (e.g., `'api.example.com'`) acts as a default. Replace these with values that suit your development or testing environment if necessary.

---

By following these steps, you have successfully integrated the domain settings into your Laravel 11 project and created a dedicated configuration file for easy management and usage throughout your application.
