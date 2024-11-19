<?php

namespace App\Services;

use App\Entity\Shipment;
use App\Entity\Order;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class ShipmentService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;

    /**
     *
     */
    public const REQUIRED_SHIPMENT_CREATE_FIELDS = [
        'orderId',
    ];
    /**
     * @var OrderService
     */
    private OrderService $orderService;
    /**
     * @var SupplierService
     */
    private SupplierService $supplierService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     * @param OrderService $orderService
     * @param SupplierService $supplierService
     */
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

    /**
     * @return array
     */
    public function getShipments(): array
    {
        return $this->entityManager->getRepository(Shipment::class)->findAll();
    }

    /**
     * @param int $id
     * @return Shipment
     */
    public function getShipmentById(int $id): Shipment
    {
        $shipment = $this->entityManager->getRepository(Shipment::class)->find($id);

        if (!$shipment) {
            throw new NotFoundHttpException('Shipment not found');
        }

        return $shipment;
    }

    /**
     * @param array $data
     * @return Shipment
     */
    public function createShipment(array $data): Shipment
    {
        RequestCheckerService::check($data, self::REQUIRED_SHIPMENT_CREATE_FIELDS);

        $shipment = new Shipment();

        $order = $this->orderService->getOrderById($data['orderId']);
        $shipment->setOrder($order);

        // shipmentDate, deliveryDate, status

        return $this->objectHandlerService->saveEntity($shipment, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Shipment
     */
    public function updateShipment(int $id, array $data): Shipment
    {
        $shipment = $this->getShipmentById($id);

        return $this->objectHandlerService->saveEntity($shipment, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteShipment(int $id): void
    {
        $shipment = $this->getShipmentById($id);

        $this->entityManager->remove($shipment);
        $this->entityManager->flush();
    }
}
