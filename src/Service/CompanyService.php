<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Company;
use App\Entity\Employee;
use App\Repository\CompanyRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class CompanyService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CompanyRepository $companyRepository,
        private ValidatorInterface $validator
    )
    {}

    public function create(Company $companyPayload): Company
    {
        try {
            $this->validate($companyPayload);
            $company = new Company();
            $company->setName($companyPayload->getName());
            $company->setPosition($companyPayload->getPosition());
            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $company;
        } catch (UniqueConstraintViolationException) {
            // @TODO create a specific exception to throw here
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function validate(Company $company): void
    {
        $errors = $this->validator->validate($company);

        if (null !== $errors && count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
    }
}