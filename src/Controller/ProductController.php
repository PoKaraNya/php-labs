<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->json($products);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->json($product);
    }

    #[Route('/products', name: 'product_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        // Перевірка необхідних даних
        if (!isset($data['name'], $data['description'], $data['price'], $data['category_id'], $data['supplier_id'])) {
            return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        // Знаходження категорії та постачальника
        $category = $em->getRepository(Category::class)->find($data['category_id']);
        $supplier = $em->getRepository(Supplier::class)->find($data['supplier_id']);

        if (!$category || !$supplier) {
            return $this->json(['error' => 'Category or Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $product->setCategory($category);
        $product->setSupplier($supplier);

        $em->persist($product);
        $em->flush();

        return $this->json($product, Response::HTTP_CREATED);
    }


    #[Route('/products/{id}', name: 'product_update', methods: ['PUT'])]
    public function update(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (isset($data['description'])) {
            $product->setDescription($data['description']);
        }
        if (isset($data['price'])) {
            $product->setPrice($data['price']);
        }
        if (isset($data['category_id'])) {
            $category = $em->getRepository(Category::class)->find($data['category_id']);
            if ($category) {
                $product->setCategory($category);
            }
        }
        if (isset($data['supplier_id'])) {
            $supplier = $em->getRepository(Supplier::class)->find($data['supplier_id']);
            if ($supplier) {
                $product->setSupplier($supplier);
            }
        }

        $em->flush();

        return $this->json($product);
    }

    #[Route('/products/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $em->remove($product);
        $em->flush();

        return $this->json(['message' => 'Product deleted successfully'], Response::HTTP_NO_CONTENT);
    }


}
