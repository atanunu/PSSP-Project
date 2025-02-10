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
