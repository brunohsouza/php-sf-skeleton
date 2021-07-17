<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Controller\CreateEmployeeController;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context'=> [
                   'groups'=> ['employee:read'],
                   'swagger_definition_name' => 'Read',
               ]
        ],
        'post' => [
            'path' => '/employee',
            'status' => Response::HTTP_CREATED,
            'controller' => CreateEmployeeController::class,
            'denormalization_context'=> [
                   'groups'=> ['employee:write'],
                   'swagger_definition_name' => 'Write',
               ],
        ]
    ],
    itemOperations: ['get'],
)]
class Employee
{
    /**
     * @Groups("employee:read")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="John"
     *         }
     *     }
     * )
     */
    private string $firstName;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="Due"
     *         }
     *     }
     * )
     */
    private string $lastName;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="string", length=70, unique=true)
     * @Assert\Email(
     *     mode="strict",
     *     message="The e-mail '{{ value }}' is not valid according the RFC-5322"
     * )
     * @Assert\NotBlank
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "example"="john@due.com"
     *         }
     *     }
     * )
     */
    private string $email;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="float", nullable=true)
     * @Assert\PositiveOrZero
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="float",
     *             "example"="60000.00"
     *         }
     *     }
     * )
     */
    private ?float $salary = null;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="float", nullable=true)
     * @Assert\PositiveOrZero
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="float",
     *             "example"="5000.00"
     *         }
     *     }
     * )
     */
    private ?float $bonus = null;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="float", nullable=true)
     * @Assert\PositiveOrZero
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="float",
     *             "example"="155.90"
     *         }
     *     }
     * )
     */
    private ?float $insuranceAmount = null;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="date")
     * @Assert\Type("datetime")
     * @Assert\NotBlank
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="date",
     *             "example"="2015-09-15"
     *         }
     *     }
     * )
     */
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private \DateTimeInterface $employmentStartDate;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type("datetime")
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="date",
     *             "example"="2019-12-15"
     *         }
     *     }
     * )
     */
    private ?\DateTimeInterface $employmentEndDate = null;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\OneToOne(targetEntity=Company::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Company $company;

    /**
     * @Groups({"employee:write", "employee:read"})
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="employee")
     */
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(?float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getBonus(): ?float
    {
        return $this->bonus;
    }

    public function setBonus(?float $bonus): self
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getInsuranceAmount(): ?float
    {
        return $this->insuranceAmount;
    }

    public function setInsuranceAmount(?float $insuranceAmount): self
    {
        $this->insuranceAmount = $insuranceAmount;

        return $this;
    }

    public function getEmploymentStartDate(): ?\DateTimeInterface
    {
        return $this->employmentStartDate;
    }

    public function setEmploymentStartDate(\DateTimeInterface $employmentStartDate): self
    {
        $this->employmentStartDate = $employmentStartDate;

        return $this;
    }

    public function getEmploymentEndDate(): ?\DateTimeInterface
    {
        return $this->employmentEndDate;
    }

    public function setEmploymentEndDate(?\DateTimeInterface $employmentEndDate): self
    {
        $this->employmentEndDate = $employmentEndDate;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getProjects(): array
    {
        return $this->projects->getValues();
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setEmployee($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getEmployee() === $this) {
                $project->setEmployee(null);
            }
        }

        return $this;
    }
}
