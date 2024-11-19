<?php

namespace App\Controller;

use App\Services\PurchaseOrderItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/purchase-order-item', name: 'purchase_order_item_routes')]
class PurchaseOrderItemController extends AbstractController
{
    /**
     * @var PurchaseOrderItemService
     */
    private PurchaseOrderItemService $purchaseOrderItemService;

    /**
     * @param PurchaseOrderItemService $purchaseOrderItemService
     */
    public function __construct(PurchaseOrderItemService $purchaseOrderItemService)
    {
        $this->purchaseOrderItemService = $purchaseOrderItemService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_purchase_order_items', methods: ['GET'])]
    public function getPurchaseOrderItems(): JsonResponse
    {
        $purchaseOrderItems = $this->purchaseOrderItemService->getPurchaseOrderItems();

        return new JsonResponse($purchaseOrderItems, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_purchase_order_item', methods: ['GET'])]
    public function getPurchaseOrderItem(int $id): JsonResponse
    {
        $purchaseOrderItem = $this->purchaseOrderItemService->getPurchaseOrderItemById($id);

        return new JsonResponse($purchaseOrderItem, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'create_purchase_order_item', methods: ['POST'])]
    public function createPurchaseOrderItem(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrderItem = $this->purchaseOrderItemService->createPurchaseOrderItem($requestData);

        return new JsonResponse($purchaseOrderItem, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update_purchase_order_item', methods: ['PATCH'])]
    public function updatePurchaseOrderItem(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $purchaseOrderItem = $this->purchaseOrderItemService->updatePurchaseOrderItem($id, $requestData);

        return new JsonResponse($purchaseOrderItem, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_purchase_order_item', methods: ['DELETE'])]
    public function deletePurchaseOrderItem(int $id): JsonResponse
    {
        $this->purchaseOrderItemService->deletePurchaseOrderItem($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
