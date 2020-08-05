<?php

namespace App\Core\Exceptions;

use App\Core\Exceptions\CoreException;

class GenericException extends CoreException
{
    public function __construct($message, $error = [])
    {
        $this->message = $message;
        $this->error = $error;
        parent::__construct($message, 400);
    }
}