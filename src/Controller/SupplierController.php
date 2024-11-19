<?php

namespace App\Controller;

use App\Services\SupplierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/supplier', name: 'supplier_routes')]
class SupplierController extends AbstractController
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    #[Route('/', name: 'get_suppliers', methods: ['GET'])]
    public function getSuppliers(): JsonResponse
    {
        $suppliers = $this->supplierService->getSuppliers();

        return new JsonResponse($suppliers, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_supplier', methods: ['GET'])]
    public function getSupplier(int $id): JsonResponse
    {
        $supplier = $this->supplierService->getSupplierById($id);

        return new JsonResponse($supplier, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_supplier', methods: ['POST'])]
    public function createSupplier(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $supplier = $this->supplierService->createSupplier($requestData);

        return new JsonResponse($supplier, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_supplier', methods: ['PATCH'])]
    public function updateSupplier(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $supplier = $this->supplierService->updateSupplier($id, $requestData);

        return new JsonResponse($supplier, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_supplier', methods: ['DELETE'])]
    public function deleteSupplier(int $id): JsonResponse
    {
        $this->supplierService->deleteSupplier($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
