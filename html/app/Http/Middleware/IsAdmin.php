<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Response;
use stdClass;

use Illuminate\Support\Facades\Auth;
use Closure;

class IsAdmin
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
        if(!Auth::user()->is_admin){
            $response = new stdClass;
            $response->success  = false;
            $response->message  = 'Não autorizado.';
            $response->error    = ["Você não possui permissão para isso."];
            $response->time    = null;
            return Response::json($response, 403);
        }
        return $next($request);
    }
}
