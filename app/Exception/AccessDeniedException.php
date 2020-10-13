<?php

namespace App\Exception;

class AccessDeniedException extends \RuntimeException
{
    public function __construct(
        string $message = 'Access Denied.',
        \Throwable $previous = null
    ) {
        parent::__construct($message, 403, $previous);
    }
}
