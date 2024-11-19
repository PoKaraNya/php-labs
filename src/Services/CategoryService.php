<?php

namespace App\Services;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public const REQUIRED_CATEGORY_CREATE_FIELDS = [
        'name',
        'description',
    ];

    private EntityManagerInterface $entityManager;
    private RequestCheckerService $requestCheckerService;
    private ObjectHandlerService $objectHandlerService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
    }

    public function getCategories(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function getCategoryById(int $id): Category
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException('Category not found');
        }

        return $category;
    }

    public function createCategory(array $data): Category
    {
        $this->requestCheckerService::check($data, self::REQUIRED_CATEGORY_CREATE_FIELDS);

        $category = new Category();

        return $this->objectHandlerService->saveEntity($category, $data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        $category = $this->getCategoryById($id);

        return $this->objectHandlerService->saveEntity($category, $data);
    }

    public function deleteCategory(int $id): void
    {
        $category = $this->getCategoryById($id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}
