<?php

namespace App\Core;

use App\Core\HandlerVersion;
use App\Http\Controllers\Controller;

class CoreController extends Controller
{
    protected $api;

    public function __construct()
    {
        $handlerVersion = new HandlerVersion();
        $this->api = $handlerVersion->getApi();
    }

    public function response($obj = [], $message = "Sucesso na requisição.")
    {
        return $this->api->responseOk($obj, $message);
    }

}
