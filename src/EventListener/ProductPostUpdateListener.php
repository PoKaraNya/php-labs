<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 *
 */
class ProductPostUpdateListener
{
    /**
     * @param PreUpdateEventArgs $args
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $product = $args->getObject();

        if (!$product instanceof Product) {
            return;
        }

        $newPrice = $args->getNewValue('price');

        $roundedPrice = floor($newPrice / 10) * 10 - 1;

        $product->setPrice($newPrice); //

        $args->setNewValue('price', $roundedPrice);
    }
}