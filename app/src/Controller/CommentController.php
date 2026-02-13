<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment')]
#[IsGranted('ROLE_USER')]
class CommentController extends AbstractController
{
    #[Route('/post/{postId}/new', name: 'app_comment_new', methods: ['POST'])]
    public function new(
        int $postId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($this->getUser());
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setApproved(false);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès, en attente d\'approbation');
        } else {
            // If form not valid, get content from textarea directly
            $payload = $request->getPayload();
            if ($payload->get('comment_type')) {
                $content = $payload->get('comment_type')['content'] ?? null;
                if ($content) {
                    $comment->setContent($content);
                    $entityManager->persist($comment);
                    $entityManager->flush();
                    
                    $this->addFlash('success', 'Commentaire ajouté avec succès, en attente d\'approbation');
                }
            }
        }

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/{id}/delete', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $postId = $comment->getPost()->getId();

        // Check if user is the author or has ROLE_ADMIN
        if ($this->getUser() !== $comment->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this comment');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire supprimé');
        }

        return $this->redirectToRoute('app_post_show', ['id' => $postId]);
    }
}
