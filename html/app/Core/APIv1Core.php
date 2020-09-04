<?php

namespace App\Core;

use Illuminate\Support\Facades\Response;
use stdClass;

class APIv1Core extends Core
{
    public function responseOk($obj = [], $message = "Sucesso na requisição.", $successCode = null)
    {
        $response = new stdClass();
        $response->success = true;
        $response->message = $message;
        $response->data    = $obj;
        $response->uuid    = parent::makeUuid();
        $response->time    = parent::getResponseTime();

        parent::logInfo(self::getRoute());
        return Response::json($response, $successCode ?: parent::getSuccessCode());
    }

    public function responseFail($e = [], $message = "FAIL", $code = 400)
    {
        $response = new stdClass;
        $response->success  = false;
        $response->message  = $message;
        $response->error    = (is_array($e)) ? $e : ["Ocorreu um problema genérico."];
        $response->time    = parent::getResponseTime();

        parent::logError(self::getRoute());
        return Response::json($response, $code);
    }

    public function responseNoAuth($e = [], $message = "NOAUTH", $code = 401)
    {
        $response = new stdClass;
        $response->success  = false;
        $response->message  = $message;
        $response->error    = ($e == 0) ? ["Autenticação inválida."] : [$e];
        $response->time    = parent::getResponseTime();

        parent::logError(self::getRoute());
        return Response::json($response, $code);
    }

    public function responseInvalidJson($message = "Invalid json format.")
    {
        $response = new stdClass;
        $response->success = false;
        $response->message = $message;

        parent::logError($message);
        return Response::json($response, 500);
    }

    public function responseNotFound($message = "Not found.")
    {
        $response = new stdClass;
        $response->success = false;
        $response->message = $message;

        parent::logWarn($message);
        return Response::json($response, 404);
    }

}
