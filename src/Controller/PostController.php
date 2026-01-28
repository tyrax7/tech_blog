<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post/show')]
    public function show(Post $post, EntityManagerInterface $entityManager,Request $request): Response
    {   
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            $comment->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_post/show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }
            return $this->render('page/show.html.twig', [
    'post' => $post,
    'commentForm' => $commentForm->createView(),
    'controller_name' => 'PostController',
]);
        
    }
}
