<?php

namespace App\Core\Exceptions;

use App\Core\HandlerVersion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CoreExceptionHandler
{
    private $api;

    public function __construct()
    {
        $handlerVersion = new HandlerVersion();
        $this->api = $handlerVersion->getApi();
    }

    public function handle($request, Throwable $e)
    {
        $error = $this->getError($request->trace, $e);
        $message = $this->getMessage($e);
        $code = $this->getCode($e);

        return $this->api->responseFail($error, $message, $code);
    }

    private function getError($trace, Throwable $e)
    {
        if ($trace) {
            $error = [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ];
        } elseif ($this->exceptionHasError($e)) {
            $error = is_array($e->getError()) ? $e->getError() : [$e->getError()];
        } elseif ($e instanceof CoreException) {
            $error = [$e->getMessage()];
        } elseif ($e instanceof AutenticacaoInvalidaException) {
            $error = [$e->getMessage()];
        } elseif ($e instanceof ModelNotFoundException) {
            $error = [(__('exception.modelNaoEncontrada', ['model' => $this->getModelNotFoundName($e)]))];
        }

        return $error ?? [$e->getCode()];
    }

    private function getMessage(Throwable $e)
    {
        if ($this->exceptionHasError($e)) return $e->getMessage();

        return $this->getMessageFromCustomException($e);
    }

    private function getMessageFromCustomException($e)
    {
        if ($e instanceof CoreException || $e instanceof ModelNotFoundException) {
            $message = (__('exception.erroNaRegraNegocio'));
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $message = (__('exception.metodoNaoPermitido'));
        } elseif ($e instanceof NotFoundHttpException) {
            $message = (__('exception.rotaNaoEncontrada'));
        } elseif ($e instanceof AutenticacaoInvalidaException) {
            $message = (__('auth.autenticacaoInvalida'));
        } else {
            $message = (__('exception.erroNaRequisicao'));
        }
    
        return $message;
    }

    private function getCode(Throwable $e)
    {
        if ($e instanceof CoreException) {
            $code = $e->getCode();
        } elseif ($e instanceof NotFoundHttpException) {
            $code = 404;
        } elseif ($e instanceof ModelNotFoundException) {
            $code = 400;
        } elseif ($e instanceof AutenticacaoInvalidaException) {
            $code = 401;
        } else {
            $code = 500;
        }

        return $code;
    }

    private function getModelNotFoundName(Throwable $e)
    {
        $full_path_model = explode('\\', $e->getModel());
        return end($full_path_model);;
    }

    private function exceptionHasError($e)
    {
        if (method_exists($e, 'hasError') && $e->hasError()) return true;
        return false;
    }
}

?>