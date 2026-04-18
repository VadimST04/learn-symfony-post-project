<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class LikeController extends AbstractController
{
    public function __construct(
        private readonly MicroPostRepository    $microPostRepository,
        private readonly EntityManagerInterface $entityManager,
    ){
    }

    #[Route('/like/{postId}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(int $postId, Request $request): Response
    {
        $post = $this->microPostRepository->findOneBy(['id' => $postId]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $post->addLikedBy($currentUser);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unlike/{postId}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(int $postId, Request $request): Response
    {
        $post = $this->microPostRepository->findOneBy(['id' => $postId]);

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $post->removeLikedBy($currentUser);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
