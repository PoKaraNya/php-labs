<?php

namespace App\Services;

use App\Entity\Category;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class CategoryService
{
    /**
     *
     */
    public const REQUIRED_CATEGORY_CREATE_FIELDS = [
        'name',
        'description',
    ];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    /**
     * @param int $id
     * @return Category
     */
    public function getCategoryById(int $id): Category
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw new NotFoundHttpException('Category not found');
        }

        return $category;
    }


    /**
     * @param array $data
     * @return Category
     * @throws DateMalformedStringException
     */
    public function createCategory(array $data): Category
    {
        $this->requestCheckerService::check($data, self::REQUIRED_CATEGORY_CREATE_FIELDS);

        $category = new Category();

        return $this->objectHandlerService->saveEntity($category, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Category
     * @throws DateMalformedStringException
     */
    public function updateCategory(int $id, array $data): Category
    {
        $category = $this->getCategoryById($id);

        return $this->objectHandlerService->saveEntity($category, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteCategory(int $id): void
    {
        $category = $this->getCategoryById($id);

        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }
}
