<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const PROJECT_NAMES = [
        'Hex Clan',
        'Code Poltergeists',
        'Robust Routine',
        'Debug Entity',
        'White Feather',
        'Wide Stringer',
        'Wombat',
        'Software Chasers',
        'Gob Geeklords',
        'Celestial Interface',
        'Open Source Pundits',
        'Java Dalia',
        'Static Startup',
        'Indie Profilers',
        'Search Engine Bandits',
    ];

    public function load(ObjectManager $manager)
    {
        $employeeList = $manager->getRepository(Employee::class)->findAll();
        for ($i=0, $iMax = count($employeeList); $i< $iMax; $i++) {
            foreach (Project::PROJECT_POSITIONS as $position) {
                $project = new Project();
                $project->setName(array_rand(array_flip(self::PROJECT_NAMES)));
                $project->setPosition($position);
                $project->setEmployee($employeeList[$i]);
                $manager->persist($project);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['project', 'employee'];
    }

    public function getDependencies(): array
    {
        return [EmployeeFixture::class];
    }
}
