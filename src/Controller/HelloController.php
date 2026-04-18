<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HelloController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MicroPostRepository $microPostRepository,
    ){
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
//        $post = new MicroPost();
//        $post->setTitle('Hello');
//        $post->setText('Hello');
//        $post->setCreated(new DateTime());
//        $post = $this->microPostRepository->find(7);
//        $comment = $post->getComments()[0];
//
//        $this->entityManager->remove($comment);
//        $this->entityManager->flush();

//        $user = new User();
//        $user->setEmail('email@gmail.com');
//        $user->setPassword('12345');
//
//        $profile = new UserProfile();
//        $profile->setName('TestUser1');
//        $profile->setUser($user);
//
//        $this->entityManager->persist($profile);
//        $this->entityManager->flush();


        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => '',
                'limit' => 0,
            ]
        );
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => '',
            ]
        );
    }
}
