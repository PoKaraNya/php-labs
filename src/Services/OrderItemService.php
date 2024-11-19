<?php

namespace App\Services;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderItemService
{
    private EntityManagerInterface $entityManager;
    private ObjectHandlerService $objectHandlerService;

    public const REQUIRED_ORDER_ITEM_CREATE_FIELDS = [
        'orderId',
        'productId',
        'quantity',
        'pricePerUnit',
    ];
    private OrderService $orderService;
    private ProductService $productService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        OrderService           $orderService,
        ProductService         $productService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

    public function getOrderItems(): array
    {
        return $this->entityManager->getRepository(OrderItem::class)->findAll();
    }

    public function getOrderItemById(int $id): OrderItem
    {
        $orderItem = $this->entityManager->getRepository(OrderItem::class)->find($id);

        if (!$orderItem) {
            throw new NotFoundHttpException('Order item not found');
        }

        return $orderItem;
    }

    public function createOrderItem(array $data): OrderItem
    {
        RequestCheckerService::check($data, self::REQUIRED_ORDER_ITEM_CREATE_FIELDS);

        $orderItem = new OrderItem();

        $order = $this->orderService->getOrderById($data['orderId']);
        $orderItem->setOrderId($order);

        $product = $this->productService->getProductById($data['productId']);
        $orderItem->setProduct($product);

        return $this->objectHandlerService->saveEntity($orderItem, $data);
    }

    public function updateOrderItem(int $id, array $data): OrderItem
    {
        $orderItem = $this->getOrderItemById($id);

        return $this->objectHandlerService->saveEntity($orderItem, $data);
    }

    public function deleteOrderItem(int $id): void
    {
        $orderItem = $this->getOrderItemById($id);

        $this->entityManager->remove($orderItem);
        $this->entityManager->flush();
    }
}
