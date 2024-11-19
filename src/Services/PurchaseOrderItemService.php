<?php

namespace App\Services;

use App\Entity\PurchaseOrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class PurchaseOrderItemService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;

    /**
     *
     */
    public const REQUIRED_PURCHASE_ORDER_ITEM_CREATE_FIELDS = [
        'purchaseOrderId',
        'productId',
        'quantity',
        'pricePerUnit',
    ];
    /**
     * @var PurchaseOrderService
     */
    private PurchaseOrderService $purchaseOrderService;
    /**
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     * @param PurchaseOrderService $purchaseOrderService
     * @param ProductService $productService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        PurchaseOrderService   $purchaseOrderService,
        ProductService         $productService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->purchaseOrderService = $purchaseOrderService;
        $this->productService = $productService;
    }

    /**
     * @return array
     */
    public function getPurchaseOrderItems(): array
    {
        return $this->entityManager->getRepository(PurchaseOrderItem::class)->findAll();
    }

    /**
     * @param int $id
     * @return PurchaseOrderItem
     */
    public function getPurchaseOrderItemById(int $id): PurchaseOrderItem
    {
        $purchaseOrderItem = $this->entityManager->getRepository(PurchaseOrderItem::class)->find($id);

        if (!$purchaseOrderItem) {
            throw new NotFoundHttpException('Purchase order item not found');
        }

        return $purchaseOrderItem;
    }

    /**
     * @param array $data
     * @return PurchaseOrderItem
     */
    public function createPurchaseOrderItem(array $data): PurchaseOrderItem
    {
        RequestCheckerService::check($data, self::REQUIRED_PURCHASE_ORDER_ITEM_CREATE_FIELDS);

        $purchaseOrderItem = new PurchaseOrderItem();

        $purchaseOrder = $this->purchaseOrderService->getPurchaseOrderById($data['purchaseOrderId']);
        $purchaseOrderItem->setPurchaseOrder($purchaseOrder);

        $product = $this->productService->getProductById($data['productId']);
        $purchaseOrderItem->setProduct($product);

        return $this->objectHandlerService->saveEntity($purchaseOrderItem, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return PurchaseOrderItem
     */
    public function updatePurchaseOrderItem(int $id, array $data): PurchaseOrderItem
    {
        $purchaseOrderItem = $this->getPurchaseOrderItemById($id);

        return $this->objectHandlerService->saveEntity($purchaseOrderItem, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePurchaseOrderItem(int $id): void
    {
        $purchaseOrderItem = $this->getPurchaseOrderItemById($id);

        $this->entityManager->remove($purchaseOrderItem);
        $this->entityManager->flush();
    }
}
