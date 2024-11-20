<?php

namespace App\Services;

use App\Entity\Shipment;
use App\Repository\ShipmentRepository;
use App\Services\Utility\ObjectHandlerService;
use App\Services\Utility\RequestCheckerService;
use DateMalformedStringException;
use DateTime;
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
    private ShipmentRepository $shipmentRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     * @param OrderService $orderService
     * @param ShipmentRepository $shipmentRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        OrderService           $orderService,
        ShipmentRepository     $shipmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->orderService = $orderService;
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param array $filters
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    public function getShipments(array $filters, int $itemsPerPage, int $page): array
    {
        return $this->shipmentRepository->getAllByFilter($filters, $itemsPerPage, $page);
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
     * @throws DateMalformedStringException
     */
    public function createShipment(array $data): Shipment
    {
        RequestCheckerService::check($data, self::REQUIRED_SHIPMENT_CREATE_FIELDS);

        $shipment = new Shipment();

        $order = $this->orderService->getOrderById($data['orderId']);
        $shipment->setOrder($order);


        $shipment->setShipmentDate(new DateTime());

        $deliveryDate = new DateTime();
        $deliveryDate->modify('+4 days');
        $shipment->setDeliveryDate($deliveryDate);

        $shipment->setStatus('Pending');

        return $this->objectHandlerService->saveEntity($shipment, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Shipment
     * @throws DateMalformedStringException
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
