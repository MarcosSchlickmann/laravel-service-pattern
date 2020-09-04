<?php

namespace App\Core\Exceptions;

use App\Core\Exceptions\CoreException;

class AutenticacaoInvalidaException extends CoreException
{
    public function __construct()
    {
        $this->message = __('auth.autenticacaoInvalida');
        $this->error = 401;
        parent::__construct($this->message, 401);
    }
}