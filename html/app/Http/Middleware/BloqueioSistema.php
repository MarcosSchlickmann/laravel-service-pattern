<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Response;
use stdClass;

use Illuminate\Support\Facades\Auth;

use Closure;

use App\Renault\Services\ParametroService;

class BloqueioSistema
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
        if(
            ParametroService::retrieve()->sistema_bloqueado &&
            (
                !Auth::user() ||
                !Auth::user()->is_admin
            )
        ){
            $response = new stdClass;
            $response->success  = false;
            $response->message  = 'Sistema bloqueado.';
            $response->error    = ["O sistema foi bloqueado pela administração. Tente novamente mais tarde."];
            $response->time    = null;
            return Response::json($response, 403);
        }
        return $next($request);
    }
}
