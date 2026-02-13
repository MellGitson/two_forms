<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'api_categories')]
class CategoryController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        
        $data = array_map(function (Category $category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }, $categories);

        return $this->json(['success' => true, 'data' => $data]);
    }

    #[Route('', name: '_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || empty($data['name'])) {
            return $this->json(['success' => false, 'error' => 'Name is required'], Response::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->setName($data['name']);

        $em->persist($category);
        $em->flush();

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json([
            'success' => true,
            'data' => [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'posts_count' => count($category->getPosts()),
            ]
        ]);
    }

    #[Route('/{id}', name: '_update', methods: ['PUT'])]
    public function update(Request $request, Category $category, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name']) && !empty($data['name'])) {
            $category->setName($data['name']);
            $em->flush();
        }

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ]
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($category);
        $em->flush();

        return $this->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}
