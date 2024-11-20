<?php

namespace App\Services;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Services\Utility\ObjectHandlerService;
use App\Services\Utility\RequestCheckerService;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class OrderService
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
    public const REQUIRED_ORDER_CREATE_FIELDS = [
        'customerId'
    ];
    /**
     * @var CustomerService
     */
    private CustomerService $customerService;
    private OrderRepository $orderRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     * @param CustomerService $customerService
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        CustomerService        $customerService,
        OrderRepository        $orderRepository)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->customerService = $customerService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getOrders(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->orderRepository->getAllByFilter($filters, $itemsPerPage, $page);
    }

    /**
     * @param int $id
     * @return Order
     */
    public function getOrderById(int $id): Order
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        return $order;
    }


    /**
     * @param array $data
     * @return Order
     * @throws DateMalformedStringException
     */
    public function createOrder(array $data): Order
    {
        RequestCheckerService::check($data, self::REQUIRED_ORDER_CREATE_FIELDS);

        $order = new Order();

        $customer = $this->customerService->getCustomerById($data['customerId']);
        $order->setCustomer($customer);

        $order->setOrderDate(new DateTime());
        $order->setStatus('Pending');

        return $this->objectHandlerService->saveEntity($order, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Order
     * @throws DateMalformedStringException
     */
    public function updateOrder(int $id, array $data): Order
    {
        $order = $this->getOrderById($id);

        return $this->objectHandlerService->saveEntity($order, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteOrder(int $id): void
    {
        $order = $this->getOrderById($id);

        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }
}
