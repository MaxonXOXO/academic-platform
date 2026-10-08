<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserOnlineStatus
{
    /**
     * Handle an incoming request.
     * Keeps user marked as online in cache for 5 minutes when session is active.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Session::get('userId');
        if ($userId) {
            Cache::put('user_online_' . $userId, true, now()->addMinutes(5));
        }

        return $next($request);
    }
}
