<?php

namespace App\Http\Middleware;


use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class API
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiToken = $request->header('apiKey');
        if ($apiToken) {
            $user = User::where('api_token', $apiToken)->first();
            if (!$user) {
                return response()->json(['message' => 'Invalid API token'], 401);
            }
            return $next($request);
        }

        if (auth()->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
