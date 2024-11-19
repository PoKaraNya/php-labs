<?php

namespace App\Services;

use App\Entity\Supplier;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class SupplierService
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
    public const REQUIRED_SUPPLIER_CREATE_FIELDS = [
        'name',
        'contactName',
        'contactPhone',
        'contactEmail',
        'address',
    ];

    /**
     * @param EntityManagerInterface $entityManager
     * @param ObjectHandlerService $objectHandlerService
     */
    public function __construct(EntityManagerInterface $entityManager, ObjectHandlerService $objectHandlerService)
    {
        $this->entityManager = $entityManager;
        $this->objectHandlerService = $objectHandlerService;
    }

    /**
     * @return array
     */
    public function getSuppliers(): array
    {
        return $this->entityManager->getRepository(Supplier::class)->findAll();
    }

    /**
     * @param int $id
     * @return Supplier
     */
    public function getSupplierById(int $id): Supplier
    {
        $supplier = $this->entityManager->getRepository(Supplier::class)->find($id);

        if (!$supplier) {
            throw new NotFoundHttpException('Supplier not found');
        }

        return $supplier;
    }


    /**
     * @param array $data
     * @return Supplier
     * @throws DateMalformedStringException
     */
    public function createSupplier(array $data): Supplier
    {
        RequestCheckerService::check($data, self::REQUIRED_SUPPLIER_CREATE_FIELDS);

        $supplier = new Supplier();

        return $this->objectHandlerService->saveEntity($supplier, $data);
    }


    /**
     * @param int $id
     * @param array $data
     * @return Supplier
     * @throws DateMalformedStringException
     */
    public function updateSupplier(int $id, array $data): Supplier
    {
        $supplier = $this->getSupplierById($id);

        return $this->objectHandlerService->saveEntity($supplier, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteSupplier(int $id): void
    {
        $supplier = $this->getSupplierById($id);

        $this->entityManager->remove($supplier);
        $this->entityManager->flush();
    }
}
