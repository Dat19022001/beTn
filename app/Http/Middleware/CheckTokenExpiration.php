<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->tokenCan('activity')) {
            // Kiểm tra thời gian hết hạn của token
            $currentTimestamp = now()->timestamp;
            

            // if ($tokenExpiresAt <= $currentTimestamp) {
            //     return response()->json(['error' => 'Token đã hết hạn'], 401);
            // }
            // dd($currentTimestamp );
        }

        return $next($request);
    }
}
