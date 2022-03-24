<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponder;
use Closure;
use Illuminate\Http\Request;

class RequestApprovalMiddleware
{
    use ApiResponder;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route()->parameters;
        if( ! auth()->user()->isAdmin()){
            return $this->error('Unauthorized access.', 401);
        }

        if(auth()->user()->ownsDocument($route['document'])){
            return $this->error('You are not allowed to approve a request made by you.', 403);
        }

        return $next($request);
    }
}
