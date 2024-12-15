<?php

namespace App\EventListener;

use App\Entity\Customer;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Psr\Log\LoggerInterface;

/**
 *
 */
class CustomerPostUpdateListener
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    /**
     * @param PostUpdateEventArgs $args
     * @return void
     */
    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $customer = $args->getObject();

        if (!$customer instanceof Customer) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $changeSet = $entityManager->getUnitOfWork()->getEntityChangeSet($customer);

        $message = sprintf(
            "Клієнт #%d (%s) був оновлений. Зміни: %s",
            $customer->getId(),
            $customer->getName(),
            json_encode($changeSet)
        );

        $this->logger->info($message);
        // var/log/dev.log
    }
}