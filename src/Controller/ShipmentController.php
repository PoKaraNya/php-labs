<?php

namespace App\Controller;

use App\Services\ShipmentService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * @param ShipmentService $shipmentService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ShipmentService $shipmentService,
        EntityManagerInterface $entityManager)
    {
        $this->shipmentService = $shipmentService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/', name: 'get_shipments', methods: ['GET'])]
    public function getShipments(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : 10;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->shipmentService->getShipments($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
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

        $this->entityManager->flush();

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

        $this->entityManager->flush();

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
