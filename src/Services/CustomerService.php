<?php

namespace App\Services;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class CustomerService
{
    /**
     *
     */
    public const REQUIRED_CUSTOMER_CREATE_FIELDS = [
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var RequestCheckerService
     */
    private RequestCheckerService $requestCheckerService;
    /**
     * @var ObjectHandlerService
     */
    private ObjectHandlerService $objectHandlerService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RequestCheckerService $requestCheckerService
     * @param ObjectHandlerService $objectHandlerService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestCheckerService $requestCheckerService,
        ObjectHandlerService $objectHandlerService
    ) {
        $this->entityManager = $entityManager;
        $this->requestCheckerService = $requestCheckerService;
        $this->objectHandlerService = $objectHandlerService;
    }

    /**
     * @return array
     */
    public function getCustomers(): array
    {
        return $this->entityManager->getRepository(Customer::class)->findAll();
    }

    /**
     * @param int $id
     * @return Customer
     */
    public function getCustomerById(int $id): Customer
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);

        if (!$customer) {
            throw new NotFoundHttpException('Customer not found');
        }

        return $customer;
    }

    /**
     * @param array $data
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        $this->requestCheckerService::check($data, self::REQUIRED_CUSTOMER_CREATE_FIELDS);

        $customer = new Customer();

        return $this->objectHandlerService->saveEntity($customer, $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Customer
     */
    public function updateCustomer(int $id, array $data): Customer
    {
        $customer = $this->getCustomerById($id);

        return $this->objectHandlerService->saveEntity($customer, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteCustomer(int $id): void
    {
        $customer = $this->getCustomerById($id);

        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}
