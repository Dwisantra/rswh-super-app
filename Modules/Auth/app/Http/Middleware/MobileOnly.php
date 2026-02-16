<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MobileOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $ua = strtolower($request->userAgent());

        if (!preg_match('/android|iphone|ipad|ipod/', $ua)) {
            return response()->json([
                'message' => 'Login hanya diizinkan melalui perangkat mobile'
            ], 403);
        }
        
        return $next($request);
    }
}
