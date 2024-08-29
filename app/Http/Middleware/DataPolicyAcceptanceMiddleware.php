<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\MicrosoftController;
use Auth;
use App\DataPolicyAcceptance;





class DataPolicyAcceptanceMiddleware

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
        $user =  Auth::user();
        $dpa = DataPolicyAcceptance::where(['user_id'=>$user->id, 'accepted'=> 1])->first();
        if(!$dpa)
        {
            return \redirect('/data_policy');

        }
        return $next($request);

    }
}
