<?php

declare(strict_types=1);

namespace App\Controller;

use _HumbugBoxda2413717501\React\Http\Message\ResponseException;
use App\Entity\Employee;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateEmployeeController extends AbstractController
{
    public function __construct(
        private EmployeeService $employeeService
    )
    {}

    #[Route(
        path: '/employee',
        name: 'create_employees',
        defaults: [
        '_api_resource_class' => Employee::class,
        '_api_collection_operation_name' => 'create_employees',
    ],
        methods: ['POST'],
    )]
    public function __invoke(Employee $data)
    {
        try {
            $this->employeeService->create($data);
            return new Response('', Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
