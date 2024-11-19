<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService
{
    private EntityManagerInterface $entityManager;
    private ObjectHandlerService $objectHandlerService;

    public const REQUIRED_ORDER_CREATE_FIELDS = [
        'customerId',
        'orderItems',
    ];
    private CustomerService $customerService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        CustomerService        $customerService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->customerService = $customerService;
    }

    public function getOrders(): array
    {
        return $this->entityManager->getRepository(Order::class)->findAll();
    }

    public function getOrderById(int $id): Order
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        return $order;
    }

    public function createOrder(array $data): Order
    {
        RequestCheckerService::check($data, self::REQUIRED_ORDER_CREATE_FIELDS);

        $order = new Order();

        $customer = $this->customerService->getCustomerById($data['customerId']);
        $order->setCustomer($customer);

        // сюди мають приходити OrderItem-и і записуватися в бд

        //order_date and status мають встановлюватися тут

        return $this->objectHandlerService->saveEntity($order, $data);
    }

    public function updateOrder(int $id, array $data): Order
    {
        $order = $this->getOrderById($id);

        return $this->objectHandlerService->saveEntity($order, $data);
    }

    public function deleteOrder(int $id): void
    {
        $order = $this->getOrderById($id);

        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }
}
