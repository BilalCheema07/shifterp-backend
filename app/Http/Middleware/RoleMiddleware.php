<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role,$permission= null)
    {
        $msg= 'Sorry ! You are not allowed to access this page.';
        if(!$request->user()->hasRole($role)){
            return json_response(400, $msg);
        }
        if($permission != null && !$request->user()->can($permission)){
            return json_response(400, $msg);
        }
        return $next($request);
    }
}
