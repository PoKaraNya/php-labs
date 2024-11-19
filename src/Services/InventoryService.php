<?php

namespace App\Services;

use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InventoryService
{
    public const REQUIRED_INVENTORY_CREATE_FIELDS = [
        'productId',
        'quantity',
        'lastUpdated',
    ];

    private EntityManagerInterface $entityManager;
    private RequestCheckerService $requestCheckerService;
    private ObjectHandlerService $objectHandlerService;
    private ProductService $productService;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        ProductService $productService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->productService = $productService;
    }

    public function getInventories(): array
    {
        return $this->entityManager->getRepository(Inventory::class)->findAll();
    }

    public function getInventoryById(int $id): Inventory
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($id);

        if (!$inventory) {
            throw new NotFoundHttpException('Inventory not found');
        }

        return $inventory;
    }

    public function createInventory(array $data): Inventory
    {
        $this->requestCheckerService::check($data, self::REQUIRED_INVENTORY_CREATE_FIELDS);

        $inventory = new Inventory();

        $product = $this->productService->getProductById($data['productId']);
        $inventory->setProduct($product);

        return $this->objectHandlerService->saveEntity($inventory, $data);
    }

    public function updateInventory(int $id, array $data): Inventory
    {
        $inventory = $this->getInventoryById($id);

        if (isset($data['productId'])) {
            $product = $this->productService->getProductById($data['productId']);
            $inventory->setProduct($product);
        }

        return $this->objectHandlerService->saveEntity($inventory, $data);
    }

    public function deleteInventory(int $id): void
    {
        $inventory = $this->getInventoryById($id);

        $this->entityManager->remove($inventory);
        $this->entityManager->flush();
    }
}
