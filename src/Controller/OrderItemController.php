<?php

namespace App\Controller;

use App\Services\OrderItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order-item', name: 'order_item_routes')]
class OrderItemController extends AbstractController
{
    private OrderItemService $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    #[Route('/', name: 'get_order_items', methods: ['GET'])]
    public function getOrderItems(): JsonResponse
    {
        $orderItems = $this->orderItemService->getOrderItems();

        return new JsonResponse($orderItems, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_order_item', methods: ['GET'])]
    public function getOrderItem(int $id): JsonResponse
    {
        $orderItem = $this->orderItemService->getOrderItemById($id);

        return new JsonResponse($orderItem, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_order_item', methods: ['POST'])]
    public function createOrderItem(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $orderItem = $this->orderItemService->createOrderItem($requestData);

        return new JsonResponse($orderItem, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_order_item', methods: ['PUT'])]
    public function updateOrderItem(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $orderItem = $this->orderItemService->updateOrderItem($id, $requestData);

        return new JsonResponse($orderItem, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_order_item', methods: ['DELETE'])]
    public function deleteOrderItem(int $id): JsonResponse
    {
        $this->orderItemService->deleteOrderItem($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
