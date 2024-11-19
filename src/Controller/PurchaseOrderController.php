<?php

namespace App\Controller;

use App\Services\PurchaseOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/purchase-order', name: 'purchase_order_routes')]
class PurchaseOrderController extends AbstractController
{
    /**
     * @var PurchaseOrderService
     */
    private PurchaseOrderService $purchaseOrderService;

    /**
     * @param PurchaseOrderService $purchaseOrderService
     */
    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_purchase_orders', methods: ['GET'])]
    public function getPurchaseOrders(): JsonResponse
    {
        $purchaseOrders = $this->purchaseOrderService->getPurchaseOrders();

        return new JsonResponse($purchaseOrders, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_purchase_order', methods: ['GET'])]
    public function getPurchaseOrder(int $id): JsonResponse
    {
        $purchaseOrder = $this->purchaseOrderService->getPurchaseOrderById($id);

        return new JsonResponse($purchaseOrder, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_purchase_order', methods: ['POST'])]
    public function createPurchaseOrder(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($requestData);

        return new JsonResponse($purchaseOrder, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_purchase_order', methods: ['PATCH'])]
    public function updatePurchaseOrder(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrder = $this->purchaseOrderService->updatePurchaseOrder($id, $requestData);

        return new JsonResponse($purchaseOrder, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_purchase_order', methods: ['DELETE'])]
    public function deletePurchaseOrder(int $id): JsonResponse
    {
        $this->purchaseOrderService->deletePurchaseOrder($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
