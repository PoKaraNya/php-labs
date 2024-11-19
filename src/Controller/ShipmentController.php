<?php

namespace App\Controller;

use App\Services\ShipmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
#[Route('/shipment', name: 'shipment_routes')]
class ShipmentController extends AbstractController
{
    /**
     * @var ShipmentService
     */
    private ShipmentService $shipmentService;

    /**
     * @param ShipmentService $shipmentService
     */
    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_shipments', methods: ['GET'])]
    public function getShipments(): JsonResponse
    {
        $shipments = $this->shipmentService->getShipments();

        return new JsonResponse($shipments, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_shipment', methods: ['GET'])]
    public function getShipment(int $id): JsonResponse
    {
        $shipment = $this->shipmentService->getShipmentById($id);

        return new JsonResponse($shipment, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/', name: 'create_shipment', methods: ['POST'])]
    public function createShipment(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $shipment = $this->shipmentService->createShipment($requestData);

        return new JsonResponse($shipment, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \DateMalformedStringException
     */
    #[Route('/{id}', name: 'update_shipment', methods: ['PATCH'])]
    public function updateShipment(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $shipment = $this->shipmentService->updateShipment($id, $requestData);

        return new JsonResponse($shipment, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_shipment', methods: ['DELETE'])]
    public function deleteShipment(int $id): JsonResponse
    {
        $this->shipmentService->deleteShipment($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
