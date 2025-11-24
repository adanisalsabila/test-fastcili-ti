<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $roles = array_map('intval', $roles);
        $user_role = (int) $request->user()->id_level;

        if (in_array($user_role, $roles)) {
            return $next($request);
        }

        abort(403, 'You do not have access');
    }
}
