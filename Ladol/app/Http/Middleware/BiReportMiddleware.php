<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\MicrosoftController;

class BiReportMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = 'kkDW2iMWNiIsInR5cUH1NcIhqYcHT5kpiVh82ZDkHxydRENRHcvt9rX9RF94N3K8k3HO';
        
        if($request->token !== $token){
            return response()->json([
                'message'=> 'Not Allowed!',
                'data'=> 'Invalid Token',
            ]);
        }
        
        return $next($request);
    }
}
