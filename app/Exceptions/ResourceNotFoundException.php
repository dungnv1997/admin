<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    protected $code = 404;

    public function __construct($message = 'Resource not found')
    {
        parent::__construct($message);
    }
}
