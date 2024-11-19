<?php

namespace App\Controller;

use App\Services\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer', name: 'customer_routes')]
class CustomerController extends AbstractController
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    #[Route('/', name: 'get_customers', methods: ['GET'])]
    public function getCustomers(): JsonResponse
    {
        $customers = $this->customerService->getCustomers();

        return new JsonResponse($customers, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'get_customer', methods: ['GET'])]
    public function getCustomer(int $id): JsonResponse
    {
        $customer = $this->customerService->getCustomerById($id);

        return new JsonResponse($customer, Response::HTTP_OK);
    }

    #[Route('/', name: 'create_customer', methods: ['POST'])]
    public function createCustomer(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $customer = $this->customerService->createCustomer($requestData);

        return new JsonResponse($customer, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update_customer', methods: ['PUT'])]
    public function updateCustomer(Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $customer = $this->customerService->updateCustomer($id, $requestData);

        return new JsonResponse($customer, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_customer', methods: ['DELETE'])]
    public function deleteCustomer(int $id): JsonResponse
    {
        $this->customerService->deleteCustomer($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
