<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $path)
    {
        if(Auth::user() === null){
            return redirect('/')->with(['error', 'Please login']);
        }

        $routeAuth = DB::table('v_usermenus')
            ->where('email', Auth::user()->email)
            ->where('route', $path)
            ->first();
        
        if(empty($routeAuth)){
            return redirect('/')->with(['error', 'Opps! You do not have access']);
        }

        return $next($request);
    }
}
