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
