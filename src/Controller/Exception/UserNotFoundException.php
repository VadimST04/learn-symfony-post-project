<?php

declare(strict_types=1);

namespace App\Controller\Exception;

use Exception;
use Throwable;

class UserNotFoundException extends Exception
{
    public function __construct(
        string $message,
        int $code,
        Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
