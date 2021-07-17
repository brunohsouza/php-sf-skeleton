<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $generator = Factory::create();
        foreach (Company::COMPANY_POSITIONS as $position) {
            $company = new Company();
            $company->setName($generator->unique()->company);
            $company->setPosition($position);
            $manager->persist($company);
        }

        $manager->flush();
    }
}
