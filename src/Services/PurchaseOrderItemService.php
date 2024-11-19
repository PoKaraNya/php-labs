<?php

namespace App\Services;

use App\Entity\PurchaseOrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseOrderItemService
{
    private EntityManagerInterface $entityManager;
    private ObjectHandlerService $objectHandlerService;

    public const REQUIRED_PURCHASE_ORDER_ITEM_CREATE_FIELDS = [
        'purchaseOrderId',
        'productId',
        'quantity',
        'pricePerUnit',
    ];
    private PurchaseOrderService $purchaseOrderService;
    private ProductService $productService;

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

    public function getPurchaseOrderItems(): array
    {
        return $this->entityManager->getRepository(PurchaseOrderItem::class)->findAll();
    }

    public function getPurchaseOrderItemById(int $id): PurchaseOrderItem
    {
        $purchaseOrderItem = $this->entityManager->getRepository(PurchaseOrderItem::class)->find($id);

        if (!$purchaseOrderItem) {
            throw new NotFoundHttpException('Purchase order item not found');
        }

        return $purchaseOrderItem;
    }

    public function createPurchaseOrderItem(array $data): PurchaseOrderItem
    {
        RequestCheckerService::check($data, self::REQUIRED_PURCHASE_ORDER_ITEM_CREATE_FIELDS);

        $purchaseOrderItem = new PurchaseOrderItem();

        $purchaseOrder = $this->purchaseOrderService->getPurchaseOrderById($data['purchaseOrderId']);
        $purchaseOrderItem->setPurchaseOrder($purchaseOrder);

        $product = $this->productService->getProductById($data['productId']);
        $purchaseOrderItem->setProduct($product);

        //total price встановлюється тут

        return $this->objectHandlerService->saveEntity($purchaseOrderItem, $data);
    }

    public function updatePurchaseOrderItem(int $id, array $data): PurchaseOrderItem
    {
        $purchaseOrderItem = $this->getPurchaseOrderItemById($id);

        return $this->objectHandlerService->saveEntity($purchaseOrderItem, $data);
    }

    public function deletePurchaseOrderItem(int $id): void
    {
        $purchaseOrderItem = $this->getPurchaseOrderItemById($id);

        $this->entityManager->remove($purchaseOrderItem);
        $this->entityManager->flush();
    }
}
