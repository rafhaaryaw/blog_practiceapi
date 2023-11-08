<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkstatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$status): Response
    {
        $user = $request->user();
        if (!$user || !in_array($user->status, $status)) {
            return response()->json([
                'message' => 'Hubungi admin untuk verifikasi',
            ], 403);
        }
        return $next($request);
    }
}
