<?php

namespace App\Controller;

use App\Services\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * @param CategoryService $categoryService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CategoryService        $categoryService,
        EntityManagerInterface $entityManager)
    {
        $this->categoryService = $categoryService;
        $this->entityManager = $entityManager;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_categories', methods: ['GET'])]
    public function getCategories(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->categoryService->getCategories($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
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
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $category = $this->categoryService->createCategory($requestData);

        $this->entityManager->flush();

        return new JsonResponse($category, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_category', methods: ['PATCH'])]
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $category = $this->categoryService->updateCategory($id, $requestData);

        $this->entityManager->flush();

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
