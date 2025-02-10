Below is a complete, step‐by‐step guide for Laravel 11 that shows you how to restrict access to routes by allowed IP addresses (as configured in your environment) and validate the HTTP method. In Laravel 11 the traditional `app/Http/Kernel.php` is no longer used for registering route middleware aliases. Instead, you can register middleware aliases using a custom service provider.

In this guide you will create:

- Two configuration files
- Two middleware classes
- A middleware service provider to register middleware aliases
- An example route that applies the middleware

---

## 1. Environment Configuration

In your project’s root, open (or create) the `.env` file and add the `ALLOWED_IPS` variable. If this value is empty (or not provided) then requests from any IP address will be allowed.

```dotenv
# .env
# To allow only specific IP addresses:
ALLOWED_IPS=127.0.0.1,192.168.1.100

# Or leave it empty to allow any IP:
ALLOWED_IPS=
```

---

## 2. Create Configuration Files

### a. Allowed IPs Configuration

Create a new file at `config/allowed_ips.php` that reads the `ALLOWED_IPS` environment variable and converts it to an array. If the variable is empty, the middleware will allow all IP addresses.

```php
<?php
// config/allowed_ips.php

$ips = trim(env('ALLOWED_IPS', ''));
$allowedIps = [];

// Only parse the IP addresses if the value is not empty
if (!empty($ips)) {
    $allowedIps = array_map('trim', explode(',', $ips));
}

return [
    'ips' => $allowedIps,
];
```

### b. Request Type & Unauthorized IP Error Messages

Create a new file at `config/RequestTypeErrorMessages.php` that holds error message templates. Placeholders will be replaced with dynamic values in the middleware.

```php
<?php
// config/RequestTypeErrorMessages.php

return [
    'invalid_request_method' => 'Invalid request method. Expected :expected but received :actual.',
    'unauthorized_ip'        => 'Unauthorized IP address: :ip is not allowed.',
];
```

---

## 3. Create Middleware Classes

Create two middleware classes inside `app/Http/Middleware`.

### a. Allowed IP Middleware

This middleware checks if the request’s IP address is allowed. If a list of allowed IPs exists (i.e. it is not empty) and the request’s IP is not in that list, it returns a JSON error message using the `unauthorized_ip` template.

Create the file: `app/Http/Middleware/AllowedIPMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowedIPMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve allowed IPs from configuration
        $allowedIps = config('allowed_ips.ips', []);

        // If allowed IPs are defined and the current IP is not in the list, reject the request.
        if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
            // Get the error message template and replace the :ip placeholder
            $errorTemplate = config('RequestTypeErrorMessages.unauthorized_ip');
            $errorMessage = str_replace(':ip', $request->ip(), $errorTemplate);

            return response()->json(['error' => $errorMessage], 403);
        }

        return $next($request);
    }
}
```

### b. Validate Request Method Middleware

This middleware ensures that the HTTP method of the incoming request matches the expected method. If not, it returns a JSON error message using the `invalid_request_method` template.

Create the file: `app/Http/Middleware/ValidateRequestMethodMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRequestMethodMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $allowedMethod  The expected HTTP method (e.g., GET)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $allowedMethod)
    {
        // Normalize methods to uppercase for comparison
        $allowedMethod = strtoupper($allowedMethod);
        $currentMethod = strtoupper($request->method());

        // If the request method does not match the expected method, return a JSON error.
        if ($currentMethod !== $allowedMethod) {
            // Get the error message template and replace the placeholders
            $errorTemplate = config('RequestTypeErrorMessages.invalid_request_method');
            $errorMessage = str_replace(
                [':expected', ':actual'],
                [$allowedMethod, $currentMethod],
                $errorTemplate
            );

            return response()->json(['error' => $errorMessage], 405);
        }

        return $next($request);
    }
}
```

---

## 4. Register Middleware Aliases in Laravel 11

Since Laravel 11 does not use `app/Http/Kernel.php` for middleware alias registration, you can register your middleware aliases using a custom service provider.

### a. Create the Middleware Service Provider

Run the following Artisan command in your terminal to generate the provider:

```bash
php artisan make:provider MiddlewareServiceProvider
```

Then update the generated file at `app/Providers/MiddlewareServiceProvider.php` as follows:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\AllowedIPMiddleware;
use App\Http\Middleware\ValidateRequestMethodMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router)
    {
        // Register route middleware aliases
        $router->aliasMiddleware('allowed.ip', AllowedIPMiddleware::class);
        $router->aliasMiddleware('check.request.method', ValidateRequestMethodMiddleware::class);
    }
}
```

### b. Register the Service Provider

Open `config/app.php` and add your new provider to the `providers` array:

```php
'providers' => [
    // Other Service Providers

    App\Providers\MiddlewareServiceProvider::class,
],
```

---

## 5. Apply Middleware to Your Routes

Now you can use the middleware aliases in your route definitions. Open (or create) your routes file (for example, `routes/web.php`) and add your routes with the middleware applied.

```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::middleware(['allowed.ip', 'check.request.method:GET'])->group(function () {
    // This route is accessible only if:
    //   - The request comes from an allowed IP (if ALLOWED_IPS is defined)
    //   - The HTTP method is GET
    Route::get('/example', function () {
        return response()->json(['message' => 'Hello World']);
    });
});
```

---

## Summary

1. **Environment Variable**:  
   - Define `ALLOWED_IPS` in your `.env` file. An empty value means no IP restrictions.

2. **Configuration Files**:  
   - `config/allowed_ips.php` parses the allowed IPs.
   - `config/RequestTypeErrorMessages.php` holds error message templates for invalid HTTP methods and unauthorized IP addresses.

3. **Middleware**:  
   - `AllowedIPMiddleware` checks the client’s IP address.
   - `ValidateRequestMethodMiddleware` validates that the HTTP method matches the expected method.

4. **Middleware Registration in Laravel 11**:  
   - Create a custom service provider (`MiddlewareServiceProvider`) to register your middleware aliases since Laravel 11 no longer uses `app/Http/Kernel.php` for this purpose.
   - Register the provider in `config/app.php`.

5. **Routes**:  
   - Apply the middleware aliases to your routes in `routes/web.php` (or `routes/api.php`).

This complete setup ensures that your Laravel 11 application accepts requests only from the allowed IP addresses (if specified) and only when the correct HTTP method is used, returning clear JSON error messages when conditions are not met.
