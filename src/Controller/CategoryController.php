<?php

namespace App\Controller;

use App\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 *
 */
#[Route('/category', name: 'category_routes')]
class CategoryController extends AbstractController
{
    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;


    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_categories', methods: ['GET'])]
    public function getCategories(): JsonResponse
    {
        $categories = $this->categoryService->getCategories();

        return new JsonResponse($categories, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_category', methods: ['GET'])]
    public function getCategory(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $category = $this->categoryService->createCategory($requestData);

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_category', methods: ['PATCH'])]
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $category = $this->categoryService->updateCategory($id, $requestData);

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_category', methods: ['DELETE'])]
    public function deleteCategory(int $id): JsonResponse
    {
        $this->categoryService->deleteCategory($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
