<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //$request->wantsJson()
        if ($request->headers->get('accept') != strtolower('application/json')) {
            return response()->json(['code' => 422, 'message' => 'Invalid Content Type'], 422);
        } 
        return $next($request);
    }
}
