<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Enum\FlashType;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MicroPostController extends AbstractController
{
    public function __construct(
        private readonly MicroPostRepository    $microPostRepository,
        private readonly EntityManagerInterface $entityManager,
    ){
    }

    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(): Response
    {
        $posts = $this->microPostRepository->findAllWithComments();

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/micro-post/top-liked', name: 'app_micro_post_topliked')]
    public function topLiked(): Response
    {
        $posts = $this->microPostRepository->findAllWithMinLikes(1);

        return $this->render('micro_post/top_liked.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/micro-post/follows', name: 'app_micro_post_follows')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function follows(): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $posts = $this->microPostRepository->findAllByAuthors($currentUser->getFollows());

        return $this->render('micro_post/follows.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/micro-post/{postId}', name: 'app_micro_post_show_one')]
    public function showOne(int $postId): Response
    {
        $post = $this->microPostRepository->findOneWithCommentsById($postId);

        if ($post === null) {
            $this->addFlash(FlashType::FAIL->value, 'Post was not found!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    #[IsGranted('ROLE_WRITER')]
    public function add(Request $request): Response
    {
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your micro post has been added!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/add.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your micro post has been updated!');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post,
            ]
        );
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post, Request $request): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your comment has been added!');

            return $this->redirectToRoute(
                'app_micro_post_show_one',
                ['postId' => $post->getId()],
            );
        }

        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post,
            ]
        );
    }
}
