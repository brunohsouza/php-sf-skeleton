<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeeFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=0;$i<=100;$i++) {
            $company = new Company();
            $company->setName($faker->unique()->company);
            $company->setPosition(array_rand(array_flip(Company::COMPANY_POSITIONS)));
            $manager->persist($company);

            $employee = new Employee();
            $employee->setFirstName($faker->firstName);
            $employee->setLastName($faker->lastName);
            $employee->setEmail($faker->email);

            $salary = $faker->randomFloat(2);
            $employee->setSalary($faker->randomFloat(2), 30000.00, 150000.00);
            $employee->setBonus(($salary*5)/100);
            $employee->setInsuranceAmount(($salary*2)/100);

            $startDate = new \DateTime($faker->date());
            $endDate = (clone $startDate)->modify('+'.random_int(1,50).' years');
            $employee->setEmploymentStartDate($startDate);
            $employee->setEmploymentEndDate($endDate);

            $employee->setCompany($company);
            $manager->persist($employee);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['employee'];
    }
}
