<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/approvals', name: 'app_admin_approvals')]
#[IsGranted('ROLE_ADMIN')]
class ApprovalsController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(
        PostRepository $postRepository,
        CommentRepository $commentRepository
    ): Response {
        $pendingPosts = $postRepository->findBy(['approved' => false]);
        $pendingComments = $commentRepository->findBy(['approved' => false]);
        $approvedPosts = $postRepository->findBy(['approved' => true]);
        $approvedComments = $commentRepository->findBy(['approved' => true]);

        return $this->render('admin/approvals/index.html.twig', [
            'pending_posts' => $pendingPosts,
            'pending_comments' => $pendingComments,
            'approved_posts' => $approvedPosts,
            'approved_comments' => $approvedComments,
        ]);
    }

    #[Route('/post/{id}/approve', name: '_post_approve', methods: ['POST'])]
    public function approvePost(
        Request $request,
        int $id,
        PostRepository $postRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('approve_post'.$id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $post = $postRepository->find($id);
        if (!$post) {
            $this->addFlash('error', 'Post non trouvé!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $post->setApproved(true);
        $em->flush();
        $this->addFlash('success', 'Post approuvé!');

        return $this->redirectToRoute('app_admin_approvals');
    }

    #[Route('/post/{id}/reject', name: '_post_reject', methods: ['POST'])]
    public function rejectPost(
        Request $request,
        int $id,
        PostRepository $postRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('reject_post'.$id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $post = $postRepository->find($id);
        if (!$post) {
            $this->addFlash('error', 'Post non trouvé!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Post rejeté et supprimé!');

        return $this->redirectToRoute('app_admin_approvals');
    }

    #[Route('/comment/{id}/approve', name: '_comment_approve', methods: ['POST'])]
    public function approveComment(
        Request $request,
        int $id,
        CommentRepository $commentRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('approve_comment'.$id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $comment = $commentRepository->find($id);
        if (!$comment) {
            $this->addFlash('error', 'Commentaire non trouvé!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $comment->setApproved(true);
        $em->flush();
        $this->addFlash('success', 'Commentaire approuvé!');

        return $this->redirectToRoute('app_admin_approvals');
    }

    #[Route('/comment/{id}/reject', name: '_comment_reject', methods: ['POST'])]
    public function rejectComment(
        Request $request,
        int $id,
        CommentRepository $commentRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$this->isCsrfTokenValid('reject_comment'.$id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $comment = $commentRepository->find($id);
        if (!$comment) {
            $this->addFlash('error', 'Commentaire non trouvé!');
            return $this->redirectToRoute('app_admin_approvals');
        }

        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'Commentaire rejeté et supprimé!');

        return $this->redirectToRoute('app_admin_approvals');
    }
}
