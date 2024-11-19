<?php

namespace App\Services;

use App\Entity\PurchaseOrder;
use DateMalformedStringException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class PurchaseOrderService
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
    public const REQUIRED_PURCHASE_ORDER_CREATE_FIELDS = [
        'supplierId',
    ];
    /**
     * @var SupplierService
     */
    private SupplierService $supplierService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     * @param SupplierService $supplierService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        SupplierService        $supplierService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->supplierService = $supplierService;
    }

    /**
     * @return array
     */
    public function getPurchaseOrders(): array
    {
        return $this->entityManager->getRepository(PurchaseOrder::class)->findAll();
    }

    /**
     * @param int $id
     * @return PurchaseOrder
     */
    public function getPurchaseOrderById(int $id): PurchaseOrder
    {
        $purchaseOrder = $this->entityManager->getRepository(PurchaseOrder::class)->find($id);

        if (!$purchaseOrder) {
            throw new NotFoundHttpException('Purchase order not found');
        }

        return $purchaseOrder;
    }

    /**
     * @param array $data
     * @return PurchaseOrder
     * @throws DateMalformedStringException
     */
    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        RequestCheckerService::check($data, self::REQUIRED_PURCHASE_ORDER_CREATE_FIELDS);

        $purchaseOrder = new PurchaseOrder();

        $supplier = $this->supplierService->getSupplierById($data['supplierId']);
        $purchaseOrder->setSupplier($supplier);

        $purchaseOrder->setOrderDate(new DateTime());
        $purchaseOrder->setStatus('Pending');

        return $this->objectHandlerService->saveEntity($purchaseOrder, $data);
    }


    /**
     * @param int $id
     * @param array $data
     * @return PurchaseOrder
     * @throws DateMalformedStringException
     */
    public function updatePurchaseOrder(int $id, array $data): PurchaseOrder
    {
        $purchaseOrder = $this->getPurchaseOrderById($id);

        return $this->objectHandlerService->saveEntity($purchaseOrder, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePurchaseOrder(int $id): void
    {
        $purchaseOrder = $this->getPurchaseOrderById($id);

        $this->entityManager->remove($purchaseOrder);
        $this->entityManager->flush();
    }
}
