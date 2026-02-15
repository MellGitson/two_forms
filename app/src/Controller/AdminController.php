<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: '_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/posts-pending', name: '_posts_pending')]
    public function postsPending(PostRepository $postRepository): Response
    {
        $pendingPosts = $postRepository->findBy(['approved' => false]);
        
        return $this->render('admin/posts_pending.html.twig', [
            'posts' => $pendingPosts,
        ]);
    }

    #[Route('/comments-pending', name: '_comments_pending')]
    public function commentsPending(CommentRepository $commentRepository): Response
    {
        $pendingComments = $commentRepository->findBy(['approved' => false]);
        
        return $this->render('admin/comments_pending.html.twig', [
            'comments' => $pendingComments,
        ]);
    }

    #[Route('/post/{id}/approve', name: '_post_approve', methods: ['POST'])]
    public function approvePost(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve' . $post->getId(), $request->getPayload()->get('_token'))) {
            $post->setApproved(true);
            $entityManager->flush();
            $this->addFlash('success', 'Article approuvé avec succès');
        }

        return $this->redirectToRoute('app_admin_posts_pending');
    }

    #[Route('/post/{id}/reject', name: '_post_reject', methods: ['POST'])]
    public function rejectPost(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('reject' . $post->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash('success', 'Article supprimé');
        }

        return $this->redirectToRoute('app_admin_posts_pending');
    }

    #[Route('/comment/{id}/approve', name: '_comment_approve', methods: ['POST'])]
    public function approveComment(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('approve' . $comment->getId(), $request->getPayload()->get('_token'))) {
            $comment->setApproved(true);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire approuvé avec succès');
        }

        return $this->redirectToRoute('app_admin_comments_pending');
    }

    #[Route('/comment/{id}/reject', name: '_comment_reject', methods: ['POST'])]
    public function rejectComment(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('reject' . $comment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé');
        }

        return $this->redirectToRoute('app_admin_comments_pending');
    }
}
