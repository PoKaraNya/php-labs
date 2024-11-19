<?php

namespace App\Controller;

use App\Services\PurchaseOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/purchase-order', name: 'purchase_order_routes')]
class PurchaseOrderController extends AbstractController
{
    private PurchaseOrderService $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }

    #[Route('/', name: 'get_purchase_orders', methods: ['GET'])]
    public function getPurchaseOrders(): JsonResponse
    {
        $purchaseOrders = $this->purchaseOrderService->getPurchaseOrders();

        return new JsonResponse($purchaseOrders, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_purchase_order', methods: ['GET'])]
    public function getPurchaseOrder(int $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->getPurchaseOrderById($id);

        return new JsonResponse($purchaseOrder, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_purchase_order', methods: ['POST'])]
    public function createPurchaseOrder(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($requestData);

        return new JsonResponse($purchaseOrder, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_purchase_order', methods: ['PUT'])]
    public function updatePurchaseOrder(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrder = $this->purchaseOrderService->updatePurchaseOrder($id, $requestData);

        return new JsonResponse($purchaseOrder, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_purchase_order', methods: ['DELETE'])]
    public function deletePurchaseOrder(int $id): JsonResponse
    {
        $this->purchaseOrderService->deletePurchaseOrder($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
