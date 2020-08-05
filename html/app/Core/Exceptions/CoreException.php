<?php

namespace App\Core\Exceptions;

use App\Core\HandlerVersion;
use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

class CoreException extends Exception implements Throwable
{
    protected $api;
    protected $message;
    protected $code;
    protected $error;

    public function __construct($message = null, $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $handlerVersion = new HandlerVersion();
        
        $this->api = $handlerVersion->getApi();

        Log::error($this->getTraceAsString());
    }

    public function render()
    {
        return $this->api->responseFail($message = $this->getMessage());
    }

    public function hasError()
    {
        if (!empty($this->error)) return true;

        return false;
    }

    public function getError()
    {
        return $this->error;
    }
}
?>