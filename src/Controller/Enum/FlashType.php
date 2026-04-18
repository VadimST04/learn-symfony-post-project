<?php

declare(strict_types=1);

namespace App\Controller\Enum;

enum FlashType: string
{
    case SUCCESS = 'success';
    case FAIL = 'fail';
    case VERIFY_EMAIL_ERRORS = 'verify_email_error';
}
