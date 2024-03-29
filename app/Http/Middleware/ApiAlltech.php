<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAlltech
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    
        if ($request->header('api-key-alltech') == config('app.api_key_alltech')) { 
            return $next($request);
        }else {
            return response()->json(['error' => 'autenticação inválida'], 200);
        }
    }
}
