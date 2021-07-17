<?php

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Employee;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectRepository $projectRepository,
        private ValidatorInterface $validator
    )
    {}

    public function create(array $projectsPayload, Employee $employee): void
    {
        if ($projectsPayload[0]->getName() === null || $projectsPayload[0]->getPosition() === null) {
            return;
        }

        foreach ($projectsPayload as $data) {
            $project = new Project();
            $project->setName($data->getName());
            $project->setPosition($data->getPosition());
            $project->setEmployee($employee);
            $this->validate($project);

            $this->entityManager->persist($project);
        }
        $this->entityManager->flush();
    }

    public function getProjectByNameAndPosition($projectName, $projectPosition): Project
    {
        return $this->projectRepository->findOneBy(['name' => $projectName, 'position' => $projectPosition]);
    }

    private function validate(Project $project)
    {
        $errors = $this->validator->validate($project);

        if (null !== $errors && count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }
    }
}