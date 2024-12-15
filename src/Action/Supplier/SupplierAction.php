<?php

namespace App\Action\Supplier;

use App\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 *
 */
#[AsController]
class SupplierAction extends AbstractController
{
    /**
     * @param Supplier $data
     * @return JsonResponse
     */
    public function __invoke(Supplier $data): JsonResponse
    {
        $products = $data->getProducts()->toArray();
        $productNames = array_map(fn($product) => $product->getName(), $products);

        return new JsonResponse([
            'supplier' => $data->getName(),
            'products' => $productNames,
        ]);
    }
}