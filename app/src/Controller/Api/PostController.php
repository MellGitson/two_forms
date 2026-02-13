<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts', name: 'api_posts')]
class PostController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): JsonResponse
    {
        $posts = $postRepository->findAll();
        
        $data = array_map(function (Post $post) {
            return [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'category' => [
                    'id' => $post->getCategory()->getId(),
                    'name' => $post->getCategory()->getName(),
                ],
            ];
        }, $posts);

        return $this->json(['success' => true, 'data' => $data]);
    }

    #[Route('', name: '_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || empty($data['title'])) {
            return $this->json(['success' => false, 'error' => 'Title is required'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['content']) || empty($data['content'])) {
            return $this->json(['success' => false, 'error' => 'Content is required'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['category_id'])) {
            return $this->json(['success' => false, 'error' => 'Category ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $category = $categoryRepository->find($data['category_id']);
        if (!$category) {
            return $this->json(['success' => false, 'error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setCategory($category);

        $em->persist($post);
        $em->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'category' => [
                    'id' => $post->getCategory()->getId(),
                    'name' => $post->getCategory()->getName(),
                ],
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(Post $post): JsonResponse
    {
        return $this->json([
            'success' => true,
            'data' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'category' => [
                    'id' => $post->getCategory()->getId(),
                    'name' => $post->getCategory()->getName(),
                ],
                'comments_count' => count($post->getComments()),
            ]
        ]);
    }

    #[Route('/{id}', name: '_update', methods: ['PUT'])]
    public function update(Request $request, Post $post, EntityManagerInterface $em, CategoryRepository $categoryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['title']) && !empty($data['title'])) {
            $post->setTitle($data['title']);
        }

        if (isset($data['content']) && !empty($data['content'])) {
            $post->setContent($data['content']);
        }

        if (isset($data['category_id'])) {
            $category = $categoryRepository->find($data['category_id']);
            if ($category) {
                $post->setCategory($category);
            }
        }

        $em->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'category' => [
                    'id' => $post->getCategory()->getId(),
                    'name' => $post->getCategory()->getName(),
                ],
            ]
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(Post $post, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($post);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Post deleted successfully']);
    }
}
