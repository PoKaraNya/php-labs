<?php

namespace App\Controller;

use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/product', name: 'product_routes')]
class ProductController extends AbstractController
{
    /**
     * @var ProductService
     */
    private ProductService $productService;


    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/', name: 'get_products', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $products = $this->productService->getProducts();

        return new JsonResponse($products, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return new JsonResponse($product, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $product = $this->productService->createProduct($requestData);

        return new JsonResponse($product, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $product = $this->productService->updateProduct($id, $requestData);

        return new JsonResponse($product, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
