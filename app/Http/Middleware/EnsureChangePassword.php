<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->must_change_password) {
            return response()->json([
                'message' => 'You must change your password before proceeding.',
                'code' => 'PASSWORD_CHANGE_REQUIRED',
            ], 403);
        }
        return $next($request);
    }
}
