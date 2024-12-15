<?php
declare(strict_types=1);

namespace App\Extension\Order;

use App\Entity\Order;
use App\Extension\UserRelationExtension;
use Doctrine\ORM\QueryBuilder;

/**
 *
 */
class OrderUserRelationExtension extends UserRelationExtension
{
    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return Order::class;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function buildQuery(QueryBuilder $queryBuilder): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[self::FIRST_ELEMENT_ARRAY];
        $queryBuilder
            ->andWhere($rootAlias . '.customer = :user')
            ->setParameter('user', $this->security->getUser());
    }
}