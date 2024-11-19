<?php

namespace App\Services;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class ProductService
{
    /**
     *
     */
    public const REQUIRED_PRODUCT_CREATE_FIELDS = [
        'name',
        'description',
        'price',
        'categoryId',
        'supplierId',
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
     * @var CategoryService
     */
    private CategoryService $categoryService;
    /**
     * @var SupplierService
     */
    private SupplierService $supplierService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param CategoryService $categoryService
     * @param SupplierService $supplierService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService  $requestCheckerService,
        ObjectHandlerService   $objectHandlerService,
        CategoryService        $categoryService,
        SupplierService        $supplierService)
    {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    /**
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $product;
    }

    /**
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        $this->requestCheckerService::check($data, self::REQUIRED_PRODUCT_CREATE_FIELDS);

        $product = new Product();

        $category = $this->categoryService->getCategoryById($data['categoryId']);
        $product->setCategory($category);

        $supplier = $this->supplierService->getSupplierById($data['supplierId']);
        $product->setSupplier($supplier);

        return $this->objectHandlerService->saveEntity($product, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->getProductById($id);

        if (isset($data['categoryId'])) {
            $category = $this->categoryService->getCategoryById($data['categoryId']);
            $product->setCategory($category);
        }

        if (isset($data['supplierId'])) {
            $supplier = $this->supplierService->getSupplierById($data['supplierId']);
            $product->setSupplier($supplier);
        }

        return $this->objectHandlerService->saveEntity($product, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteProduct(int $id): void
    {
        $product = $this->getProductById($id);

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}