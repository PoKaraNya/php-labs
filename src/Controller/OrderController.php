<?php

namespace App\Controller;

use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/order', name: 'order_routes')]
class OrderController extends AbstractController
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * @param OrderService $orderService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        OrderService $orderService,
        EntityManagerInterface $entityManager)
    {
        $this->orderService = $orderService;
        $this->entityManager = $entityManager;
    }


    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_orders', methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrders();

        return new JsonResponse($orders, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        return new JsonResponse($order, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $order = $this->orderService->createOrder($requestData);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_order', methods: ['PATCH'])]
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $order = $this->orderService->updateOrder($id, $requestData);

        $this->entityManager->flush();

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_order', methods: ['DELETE'])]
    public function deleteOrder(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
