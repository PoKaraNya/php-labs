<?php

namespace App\Repository;

use App\Entity\PurchaseOrderItem;
use App\Services\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<PurchaseOrderItem>
 */
class PurchaseOrderItemRepository extends ServiceEntityRepository
{
    /**
     * @var PaginationService
     */
    private PaginationService $paginationService;

    /**
     * @param ManagerRegistry $registry
     * @param PaginationService $paginationService
     */
    public function __construct(
        ManagerRegistry   $registry,
        PaginationService $paginationService)
    {
        parent::__construct($registry, PurchaseOrderItem::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'purchaseOrderItems' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('purchase_order_item');

        if (!empty($data['purchase_order_id'])) {
            $queryBuilder->andWhere('purchase_order_item.purchaseOrder = :purchaseOrderId')
                ->setParameter('purchaseOrderId', (int)$data['purchase_order_id']);
        }

        if (!empty($data['product_id'])) {
            $queryBuilder->andWhere('purchase_order_item.product = :productId')
                ->setParameter('productId', (int)$data['product_id']);
        }

        if (!empty($data['min_quantity'])) {
            $queryBuilder->andWhere('purchase_order_item.quantity >= :minQuantity')
                ->setParameter('minQuantity', (int)$data['min_quantity']);
        }

        if (!empty($data['max_quantity'])) {
            $queryBuilder->andWhere('purchase_order_item.quantity <= :maxQuantity')
                ->setParameter('maxQuantity', (int)$data['max_quantity']);
        }

        if (!empty($data['min_price'])) {
            $queryBuilder->andWhere('purchase_order_item.pricePerUnit >= :minPrice')
                ->setParameter('minPrice', (float)$data['min_price']);
        }

        if (!empty($data['max_price'])) {
            $queryBuilder->andWhere('purchase_order_item.pricePerUnit <= :maxPrice')
                ->setParameter('maxPrice', (float)$data['max_price']);
        }

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }
}
