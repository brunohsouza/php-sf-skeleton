<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class ImportEmployeeService extends AbstractEmployeeFileOperations
{
    private const HEADER_OFFSET = 0;

    public function __construct(
        private ValidatorInterface $validator,
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $entityManager
    )
    {}

    public function import(string $filepath)
    {
        $records = $this->readFile($filepath);

        foreach ($records as $record) {
            $employee = $this->employeeRepository->findOneBy(['email' => $record['Email']]);

            if (empty($employee)) {
                return;
            }

            $this->update($employee, $record);
        }
    }

    private function readFile(string $filepath): \Iterator
    {
        $reader = Reader::createFromPath($filepath);
        $reader->setHeaderOffset(self::HEADER_OFFSET);

        if ($reader->getHeader() !== self::FILE_HEADERS) {
            throw new \Exception('Invalid file headers');
        }

        return $reader->getRecords(self::FILE_HEADERS);
    }

    private function update(Employee $employee, array $record)
    {
        $employee->setFirstName($record['First Name']);
        $employee->setLastName($record['Last Name']);
        $employee->getCompany()?->setName($record['Company']);
        $employee->getProjects()[0]?->setName($record['Project']);
        $employee->setSalary((float) $record['Salary']);
        $employee->setBonus((float) $record['Bonus']);
        $employee->setInsuranceAmount((float) $record['Insurance Amount']);
        $employee->setEmploymentStartDate(new \DateTime($record['Employment Start']));
        $employee->setEmploymentEndDate(new \DateTime($record['Employment End']));

        $errors = $this->validator->validate($employee);

        if ($errors) {
            throw new \InvalidArgumentException(json_encode($errors, JSON_THROW_ON_ERROR));
        }

        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

}