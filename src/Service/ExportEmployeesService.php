<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\EmployeeRepository;

class ExportEmployeesService extends AbstractEmployeeFileOperations
{
    private const EXPORT_DIR = './data/exports/';

    public function __construct(
        private EmployeeRepository $employeeRepository
    )
    {}

    public function export(): void
    {
        try {
            $this->validateDir();
            $employeeList = $this->employeeRepository->findAll();

            $fp = fopen(self::EXPORT_DIR.'payslip-employees.csv', 'wb');

            $employeeArray = $this->prepareDataToExport($employeeList);
            fputcsv($fp, self::FILE_HEADERS);
            foreach ($employeeArray as $key => $line) {
                if (is_int($key)) {
                    fputcsv($fp, array_values($line));
                }
            }
            fputcsv($fp, [
                'Total', null, null, null, null, null,
                $employeeArray['totals']['salary'],
                $employeeArray['totals']['bonus'],
                $employeeArray['totals']['salary'],
                $employeeArray['totals']['totalCost'],
            ]);
            fclose($fp);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function validateDir()
    {
        if (!is_dir(self::EXPORT_DIR) || !is_writable(self::EXPORT_DIR)) {
            throw new \Exception('Directory don\'t exists or is now writable');
        }
    }

    private function prepareDataToExport(array $employeeList): array
    {
        $employeeCsv = [];
        $totalGeneralCost = [];

        for ($i=0, $iMax = count($employeeList); $i< $iMax; $i++) {
            $totalCost = $this->getTotalCost(
                $employeeList[$i]->getSalary(),
                $employeeList[$i]->getBonus(),
                $employeeList[$i]->getInsuranceAmount()
            );

            $totalGeneralCost['salary'][$i] = $employeeList[$i]->getSalary();
            $totalGeneralCost['bonus'][$i] = $employeeList[$i]->getBonus();
            $totalGeneralCost['insuranceAmount'][$i] = $employeeList[$i]->getInsuranceAmount();
            $totalGeneralCost['totalCost'][$i] = $totalCost;

            $employeeCsv[$i] = [
                $employeeList[$i]->getId(),
                $employeeList[$i]->getFirstName(),
                $employeeList[$i]->getLastName(),
                $employeeList[$i]->getEmail(),
                $employeeList[$i]->getCompany()->getName(),
                $employeeList[$i]->getProjects()[0]->getName(),
                $employeeList[$i]->getSalary(),
                $employeeList[$i]->getBonus(),
                $employeeList[$i]->getInsuranceAmount(),
                $totalCost,
                $employeeList[$i]->getEmploymentStartDate()?->format('Y-m-d'),
                $employeeList[$i]->getEmploymentEndDate()?->format('Y-m-d'),
            ];
            $employeeCsv['totals']['salary'] = array_sum($totalGeneralCost['salary']);
            $employeeCsv['totals']['bonus'] = array_sum($totalGeneralCost['bonus']);
            $employeeCsv['totals']['insuranceAmount'] = array_sum($totalGeneralCost['insuranceAmount']);
            $employeeCsv['totals']['totalCost'] = array_sum($totalGeneralCost['totalCost']);
        }

        return $employeeCsv;
    }

    private function getTotalCost(float $salary, float $bonus, float $insuranceAmount)
    {
        return bcadd(bcadd((string) $salary, (string) $bonus), (string) $insuranceAmount);
    }
}