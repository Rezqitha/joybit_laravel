<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->query('api_key') ?? $request->header('X-API-KEY') ?? $request->input('api_key', '');

        if ($apiKey !== 'joybite123') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid API Key',
            ], 401);
        }

        return $next($request);
    }
}
