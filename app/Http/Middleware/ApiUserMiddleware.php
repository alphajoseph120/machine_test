<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            if(auth()->user()->tokenCan('server:user')){
                return $next($request);
            } else {
                return response()->json([
                    'status'=>403,
                    'message'=>'Access Denied.! As you are not an user.',
                ]); 
            }
        } else {
            return response()->json([
                'status'=>401,
                'message'=>'Please Login First.',
            ]); 
        }
    }
}
