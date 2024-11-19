<?php

namespace App\Services;

use App\Entity\Shipment;
use App\Entity\Order;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentService
{
    private EntityManagerInterface $entityManager;
    private ObjectHandlerService $objectHandlerService;

    public const REQUIRED_SHIPMENT_CREATE_FIELDS = [
        'orderId',
    ];
    private OrderService $orderService;
    private SupplierService $supplierService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        OrderService           $orderService,
        SupplierService        $supplierService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->orderService = $orderService;
        $this->supplierService = $supplierService;
    }

    public function getShipments(): array
    {
        return $this->entityManager->getRepository(Shipment::class)->findAll();
    }

    public function getShipmentById(int $id): Shipment
    {
        $shipment = $this->entityManager->getRepository(Shipment::class)->find($id);

        if (!$shipment) {
            throw new NotFoundHttpException('Shipment not found');
        }

        return $shipment;
    }

    public function createShipment(array $data): Shipment
    {
        RequestCheckerService::check($data, self::REQUIRED_SHIPMENT_CREATE_FIELDS);

        $shipment = new Shipment();

        $order = $this->orderService->getOrderById($data['orderId']);
        $shipment->setOrderId($order);

        // shipmentDate, deliveryDate, status

        return $this->objectHandlerService->saveEntity($shipment, $data);
    }

    public function updateShipment(int $id, array $data): Shipment
    {
        $shipment = $this->getShipmentById($id);

        return $this->objectHandlerService->saveEntity($shipment, $data);
    }

    public function deleteShipment(int $id): void
    {
        $shipment = $this->getShipmentById($id);

        $this->entityManager->remove($shipment);
        $this->entityManager->flush();
    }
}
