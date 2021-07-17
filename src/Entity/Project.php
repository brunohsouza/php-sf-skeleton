<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"name", "employee_id", "position"})
 *     }
 * )
 */
class Project
{
    private const PROJECT_DEVELOPER = 'Developer';
    private const PROJECT_DESIGNER = 'Designer';
    private const PROJECT_MANAGER = 'Manager';
    private const PROJECT_SALESPERSON = 'Salesperson';

    public const PROJECT_POSITIONS = [
        self::PROJECT_DEVELOPER,
        self::PROJECT_DESIGNER,
        self::PROJECT_MANAGER,
        self::PROJECT_SALESPERSON
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Celestial Interface",
     *         }
     *     }
     * )
     */
    private ?string $name = null;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=20)
     * @Assert\Type("string")
     * @Assert\Choice(
     *     choices=Project::PROJECT_POSITIONS,
     *     message="Invalid value for project position. Valid choices are: Developer, Designer, Manager, Salesperson"
     * )
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Developer"
     *         }
     *     }
     * )
     */
    private string $position;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class, inversedBy="projects")
     */
    private Employee $employee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
