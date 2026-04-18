<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use DateTime;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /** @param User $user */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user->getBannedUntil() === null) {
            return;
        }

        $now = new DateTime();

        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedException('The user is banned!');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // TODO: Implement checkPostAuth() method.
    }
}
