<?php

namespace App\Services;

use App\Entity\Inventory;
use App\Repository\InventoryRepository;
use App\Services\Utility\ObjectHandlerService;
use App\Services\Utility\RequestCheckerService;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class InventoryService
{
    /**
     *
     */
    public const REQUIRED_INVENTORY_CREATE_FIELDS = [
        'productId',
        'quantity',
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
     * @var ProductService
     */
    private ProductService $productService;
    private InventoryRepository $inventoryRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     * @param ProductService $productService
     * @param InventoryRepository $inventoryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService,
        ProductService $productService,
        InventoryRepository $inventoryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
        $this->productService = $productService;
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getInventories(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->inventoryRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Inventory
     */
    public function getInventoryById(int $id): Inventory
    {
        $inventory = $this->entityManager->getRepository(Inventory::class)->find($id);

        if (!$inventory) {
            throw new NotFoundHttpException('Inventory not found');
        }

        return $inventory;
    }


    /**
     * @param array $data
     * @return Inventory
     * @throws DateMalformedStringException
     */
    public function createInventory(array $data): Inventory
    {
        $this->requestCheckerService::check($data, self::REQUIRED_INVENTORY_CREATE_FIELDS);

        $inventory = new Inventory();

        $product = $this->productService->getProductById($data['productId']);
        $inventory->setProduct($product);

        $inventory->setLastUpdated(new DateTime());

        return $this->objectHandlerService->saveEntity($inventory, $data);
    }


    /**
     * @param int $id
     * @param array $data
     * @return Inventory
     * @throws DateMalformedStringException
     */
    public function updateInventory(int $id, array $data): Inventory
    {
        $inventory = $this->getInventoryById($id);

        if (isset($data['productId'])) {
            $product = $this->productService->getProductById($data['productId']);
            $inventory->setProduct($product);
        }

        $inventory->setLastUpdated(new DateTime());

        return $this->objectHandlerService->saveEntity($inventory, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteInventory(int $id): void
    {
        $inventory = $this->getInventoryById($id);

        $this->entityManager->remove($inventory);
        $this->entityManager->flush();
    }
}
