<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CompanyService $companyService,
        private ProjectService $projectService,
        private ValidatorInterface $validator
    )
    {
    }

    public function create(Employee $employeePayload): void
    {
        try {
            $this->validate($employeePayload);
            $this->entityManager->beginTransaction();
            $company = $this->companyService->create($employeePayload->getCompany());

            $employee = new Employee();
            $employee->setFirstName($employeePayload->getFirstName());
            $employee->setLastName($employeePayload->getLastName());
            $employee->setEmail($employeePayload->getEmail());
            $employee->setSalary($employeePayload->getSalary());
            $employee->setBonus($employeePayload->getBonus());
            $employee->setInsuranceAmount($employeePayload->getInsuranceAmount());
            $employee->setEmploymentStartDate($employeePayload->getEmploymentStartDate());
            $employee->setEmploymentEndDate($employeePayload->getEmploymentEndDate());

            $employee->setCompany($company);
            $this->entityManager->persist($employee);
            $this->entityManager->flush();

            $this->projectService->create($employeePayload->getProjects(), $employee);

            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            throw new \Exception($exception->getMessage());
        }
    }

    private function validate(Employee $employeePayload)
    {
        $errors = $this->validator->validate($employeePayload);

        if (null !== $errors && count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
    }
}
