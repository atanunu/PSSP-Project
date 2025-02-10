Below is a complete, step-by-step guide with all the necessary code to build a Laravel 11 system that:

1. **Restricts access to routes by allowed IP addresses** (configured in your `.env` file and loaded via a config file). If no IPs are specified, requests from any IP will be allowed.
2. **Validates the HTTP method** used to access a route and returns a JSON error if it doesn’t match the expected method. Error messages (including one for unauthorized IP addresses) are provided via a configuration file.

Follow these instructions and add the code files as described.

---

## 1. Environment Variable

In your root `.env` file, add (or update) the `ALLOWED_IPS` variable.  
If you leave it empty or omit it, requests from any IP will be allowed.

```dotenv
# .env
# Example with allowed IPs:
ALLOWED_IPS=127.0.0.1,192.168.1.100

# Example with no restriction (empty value):
ALLOWED_IPS=
```

---

## 2. Configuration Files

### a. `config/allowed_ips.php`

This configuration file reads the `ALLOWED_IPS` environment variable and creates an array of IP addresses. If empty, the resulting array will be empty (which means no IP restrictions).

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

### b. `config/RequestTypeErrorMessages.php`

This configuration file contains the error message templates for invalid request methods and unauthorized IP addresses. Placeholders (`:expected`, `:actual`, `:ip`) are used to dynamically inject values.

```php
<?php
// config/RequestTypeErrorMessages.php

return [
    'invalid_request_method' => 'Invalid request method. Expected :expected but received :actual.',
    'unauthorized_ip'        => 'Unauthorized IP address: :ip is not allowed.',
];
```

---

## 3. Middleware Classes

### a. Allowed IP Middleware

This middleware checks if the request’s IP address is in the allowed list. If the list is empty (no restriction), the middleware will allow the request. If the IP is not allowed, it returns a JSON error using the `unauthorized_ip` template.

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

        // If there are allowed IPs and the current request IP is not in the list, reject the request.
        if (!empty($allowedIps) && !in_array($request->ip(), $allowedIps)) {
            // Get the error message template and replace the :ip placeholder with the actual IP.
            $errorTemplate = config('RequestTypeErrorMessages.unauthorized_ip');
            $errorMessage = str_replace(':ip', $request->ip(), $errorTemplate);

            return response()->json(['error' => $errorMessage], 403);
        }

        return $next($request);
    }
}
```

### b. Validate Request Method Middleware

This middleware checks whether the incoming request uses the specified HTTP method. If it doesn’t, it returns a JSON error using the `invalid_request_method` template.

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
            // Get the error message template and replace placeholders with expected and actual values.
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

## 4. Register Middleware in Kernel

Open your Kernel file `app/Http/Kernel.php` and register the new middleware classes under the `$routeMiddleware` array. This allows you to assign them to your routes.

```php
<?php
// app/Http/Kernel.php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...

    protected $routeMiddleware = [
        // ... other middleware registrations
        'allowed.ip'           => \App\Http\Middleware\AllowedIPMiddleware::class,
        'check.request.method' => \App\Http\Middleware\ValidateRequestMethodMiddleware::class,
    ];
}
```

---

## 5. Apply Middleware to Routes

You can now use these middleware in your route definitions. For example, open your routes file (e.g., `routes/web.php` or `routes/api.php`) and define routes with middleware applied.

```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;

Route::middleware(['allowed.ip', 'check.request.method:GET'])->group(function () {
    // This route is accessible only from allowed IP addresses (if specified)
    // and only via GET requests.
    Route::get('/example', function () {
        return response()->json(['message' => 'Hello World']);
    });
});
```

In the above example:

- **`allowed.ip`**: Checks that the request comes from an allowed IP. If `ALLOWED_IPS` is empty, the check is bypassed.
- **`check.request.method:GET`**: Ensures that only GET requests are allowed. If any other method (e.g., POST) is used, a JSON error is returned using the template defined in `config/RequestTypeErrorMessages.php`.

---

## Summary

1. **Environment Configuration**:  
   - Set the `ALLOWED_IPS` variable in your `.env` file.
   - If empty, the middleware allows requests from any IP address.

2. **Configuration Files**:  
   - `config/allowed_ips.php` parses the `ALLOWED_IPS` variable.
   - `config/RequestTypeErrorMessages.php` defines error message templates for invalid HTTP methods and unauthorized IP addresses.

3. **Middleware**:  
   - `AllowedIPMiddleware` checks the client’s IP address.
   - `ValidateRequestMethodMiddleware` validates that the request method is as expected.

4. **Kernel Registration and Route Usage**:  
   - Register middleware in `app/Http/Kernel.php`.
   - Apply the middleware to routes in your routes file.

This complete setup ensures that your Laravel application accepts requests only from specified IP addresses (if any) and only when the correct HTTP method is used, returning clear JSON error messages when conditions are not met.
