<?php

namespace App\Controller;

use App\Services\InventoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/inventory', name: 'inventory_routes')]
class InventoryController extends AbstractController
{
    /**
     * @var InventoryService
     */
    private InventoryService $inventoryService;

    /**
     * @param InventoryService $inventoryService
     */
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_inventories', methods: ['GET'])]
    public function getInventories(): JsonResponse
    {
        $inventories = $this->inventoryService->getInventories();

        return new JsonResponse($inventories, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_inventory', methods: ['GET'])]
    public function getInventory(int $id): JsonResponse
    {
        $inventory = $this->inventoryService->getInventoryById($id);

        return new JsonResponse($inventory, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_inventory', methods: ['POST'])]
    public function createInventory(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $inventory = $this->inventoryService->createInventory($requestData);

        return new JsonResponse($inventory, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_inventory', methods: ['PATCH'])]
    public function updateInventory(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $inventory = $this->inventoryService->updateInventory($id, $requestData);

        return new JsonResponse($inventory, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_inventory', methods: ['DELETE'])]
    public function deleteInventory(int $id): JsonResponse
    {
        $this->inventoryService->deleteInventory($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
