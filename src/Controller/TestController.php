<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        dd(phpinfo());
        return $this->render();
    }

    #[Route('/get', name: 'app_test_get', methods: ['GET'])]
    public function get(Request $request): JsonResponse
    {
        $queryParams = $request->query->all();

        return new JsonResponse($queryParams);
    }

    #[Route('/post', name: 'app_test_post', methods: ['POST'])]
    public function post(Request $request): JsonResponse
    {
        $requestBody = json_decode($request->getContent(), true);

        return new JsonResponse($requestBody);
    }

    #[Route('/get-item{id}', name: 'app_test_get_item', methods: ['GET'])]
    public function getItem(string $id): JsonResponse
    {
        return new JsonResponse($id);
    }
}
