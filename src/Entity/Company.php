<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    private const POSITION_CONTRACTOR = 'Contractor';
    private const POSITION_EMPLOYEE = 'Employee';
    private const POSITION_MANAGER = 'Manager';
    private const POSITION_DIRECTOR = 'Director';

    public const COMPANY_POSITIONS = [
        self::POSITION_CONTRACTOR,
        self::POSITION_EMPLOYEE,
        self::POSITION_MANAGER,
        self::POSITION_DIRECTOR
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Type(type="string")
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Payslip",
     *         }
     *     }
     * )
     */
    private string $name;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=20)
     * @Assert\Type("string")
     * @Assert\Choice(
     *     choices=Company::COMPANY_POSITIONS,
     *     message="Invalid value for Company position. Valid choices are: Contractor, Employee, Manager, Director"
     * )
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Employee",
     *         }
     *     }
     * )
     */
    private string $position;

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
}
