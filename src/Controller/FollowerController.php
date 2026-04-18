<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FollowerController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ManagerRegistry $managerRegistry,
    ){
    }

    #[Route('/follow/{userId}', name: 'app_follow')]
    public function follow(int $userId, Request $request): Response
    {
        $userToFollow = $this->userRepository->findOneBy(['id' => $userId]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userId !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);

            $this->managerRegistry->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{userId}', name: 'app_unfollow')]
    public function unfollow(int $userId, Request $request): Response
    {
        $userToUnfollow = $this->userRepository->findOneBy(['id' => $userId]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userId !== $currentUser->getId()) {
            $currentUser->unfollow($userToUnfollow);

            $this->managerRegistry->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
