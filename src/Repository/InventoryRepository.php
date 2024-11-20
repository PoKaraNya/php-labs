<?php

namespace App\Repository;

use App\Entity\Inventory;
use App\Services\Utility\PaginationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Inventory::class);
        $this->paginationService = $paginationService;
    }

    /**
     * @param array $data
     * @param int $itemsPerPage
     * @param int $page
     * @return array
     */
    #[ArrayShape([
        'inventories' => "array",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getAllByFilter(array $data, int $itemsPerPage, int $page): array
    {
        $queryBuilder = $this->createQueryBuilder('inventory');

        return $this->paginationService->paginate($queryBuilder, $itemsPerPage, $page);
    }

}
