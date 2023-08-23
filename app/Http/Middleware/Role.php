<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        if (! auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        foreach ($roles as $role) {
            if ($user->role_id == $role) {
                return $next($request);
            }
        }

        return abort(403);
    }
}
