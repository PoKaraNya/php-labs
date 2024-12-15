<?php

namespace App\Action\Product;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 *
 */
#[AsController]
class ProductAction extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['price'])) {
            $newPrice = $data['price'];

            $product->setPrice($newPrice);

            $this->entityManager->flush();
            return new JsonResponse([
                'product' => $product->getName(),
                'newPrice' => $product->getPrice(),
            ]);
        }

        return new JsonResponse([
            'error' => 'Price not provided',
        ], 400);
    }
}

//{
//    "price": 934
//}