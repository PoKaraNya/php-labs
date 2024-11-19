<?php

namespace App\Controller;

use App\Services\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order', name: 'order_routes')]
class OrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }


    #[Route('/', name: 'get_orders', methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrders();

        return new JsonResponse($orders, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_order', methods: ['GET'])]
    public function getOrder(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        return new JsonResponse($order, Response::HTTP_OK);
    }


    #[Route('/', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $order = $this->orderService->createOrder($requestData);

        return new JsonResponse($order, Response::HTTP_CREATED);
    }


    #[Route('/{id}', name: 'update_order', methods: ['PATCH'])]
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $order = $this->orderService->updateOrder($id, $requestData);

        return new JsonResponse($order, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_order', methods: ['DELETE'])]
    public function deleteOrder(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
