<?php

namespace App\Services;

use App\Entity\PurchaseOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseOrderService
{
    private EntityManagerInterface $entityManager;
    private ObjectHandlerService $objectHandlerService;

    public const REQUIRED_PURCHASE_ORDER_CREATE_FIELDS = [
        'supplierId',
    ];
    private SupplierService $supplierService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectHandlerService   $objectHandlerService,
        SupplierService        $supplierService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
        $this->supplierService = $supplierService;
    }

    public function getPurchaseOrders(): array
    {
        return $this->entityManager->getRepository(PurchaseOrder::class)->findAll();
    }

    public function getPurchaseOrderById(int $id): PurchaseOrder
    {
        $purchaseOrder = $this->entityManager->getRepository(PurchaseOrder::class)->find($id);

        if (!$purchaseOrder) {
            throw new NotFoundHttpException('Purchase order not found');
        }

        return $purchaseOrder;
    }

    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        RequestCheckerService::check($data, self::REQUIRED_PURCHASE_ORDER_CREATE_FIELDS);

        $purchaseOrder = new PurchaseOrder();

        $supplier = $this->supplierService->getSupplierById($data['supplierId']);
        $purchaseOrder->setSupplier($supplier);

        //order_date status total_cost встановлюються тут

        return $this->objectHandlerService->saveEntity($purchaseOrder, $data);
    }

    public function updatePurchaseOrder(int $id, array $data): PurchaseOrder
    {
        $purchaseOrder = $this->getPurchaseOrderById($id);

        return $this->objectHandlerService->saveEntity($purchaseOrder, $data);
    }

    public function deletePurchaseOrder(int $id): void
    {
        $purchaseOrder = $this->getPurchaseOrderById($id);

        $this->entityManager->remove($purchaseOrder);
        $this->entityManager->flush();
    }
}
