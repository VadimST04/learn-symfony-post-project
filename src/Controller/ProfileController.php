<?php

namespace App\Controller;

use App\Controller\Exception\UserNotFoundException;
use App\Repository\MicroPostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MicroPostRepository $microPostRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    #[Route('/profile/{id}', name: 'app_profile')]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new UserNotFoundException(
                sprintf('User with id %d was now found', $id),
                137462563,
            );
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'posts' => $this->microPostRepository->findAllByAuthor($user),
        ]);
    }

    #[Route('/profile/{id}/follows', name: 'app_profile_follows')]
    public function follows(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new UserNotFoundException(
                sprintf('User with id %d was now found', $id),
                137462563,
            );
        }

        return $this->render('profile/follows.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/{id}/followers', name: 'app_profile_followers')]
    public function followers(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new UserNotFoundException(
                sprintf('User with id %d was now found', $id),
                137462563,
            );
        }

        return $this->render('profile/followers.html.twig', [
            'user' => $user,
        ]);
    }
}
