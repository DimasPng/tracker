<?php

namespace App\Core\Exception;

use App\Core\Response;
use Exception;

class ValidationException extends Exception
{
    public function __construct(
        private Response $response
    ) {
        parent::__construct('Validation failed');
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
