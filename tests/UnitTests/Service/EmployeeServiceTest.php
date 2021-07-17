<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Service;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Company;
use App\Entity\Employee;
use App\Service\CompanyService;
use App\Service\EmployeeService;
use App\Service\ProjectService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @coversDefaultClass \App\Service\EmployeeService
 */
class EmployeeServiceTest extends TestCase
{

    use ProphecyTrait;

    private EmployeeService $employeeService;

    /**
     * @var ObjectProphecy | EntityManager
     */
    private ObjectProphecy $entityManager;

    /**
     * @var ObjectProphecy | CompanyService
     */
    private ObjectProphecy $companyService;

    /**
     * @var ObjectProphecy | ProjectService
     */
    private ObjectProphecy $projectService;

    /**
     * @var ObjectProphecy | ValidatorInterface
     */
    private ObjectProphecy $validator;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->companyService = $this->prophesize(CompanyService::class);
        $this->projectService = $this->prophesize(ProjectService::class);
        $this->validator = $this->prophesize(ValidatorInterface::class);
        $this->employeeService = new EmployeeService(
            $this->entityManager->reveal(),
            $this->companyService->reveal(),
            $this->projectService->reveal(),
            $this->validator->reveal()
        );
    }

    /**
     * @group employee
     * @dataProvider createDataProvider
     * @covers ::create
     */
    public function testCreate($employee, callable $prophecies, callable $asserts): void
    {
        $prophecies($employee, $this);
        $this->employeeService->create($employee);

        $asserts($this, $employee);
    }

    public function createDataProvider(): \Generator
    {
        $employee = new Employee();

        $company = $this->prophesize(Company::class);
        $company->id = 123;
        $company->name = 'Test Company';
        $company->position = array_rand(array_flip(Company::COMPANY_POSITIONS));

        yield 'create-employee-without-end-date' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john@example.com');
                $employee->setSalary(60000);
                $employee->setBonus(5000);
                $employee->setInsuranceAmount(0);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldHaveBeenCalled();
                $test->entityManager->flush()->shouldHaveBeenCalled();
            })
        ];

        yield 'create-employee-without-salary' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john@example.com');
                $employee->setBonus(5000);
                $employee->setInsuranceAmount(0);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldHaveBeenCalled();
                $test->entityManager->flush()->shouldHaveBeenCalled();
            })
        ];

        yield 'create-employee-without-bonus' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john@example.com');
                $employee->setSalary(60000);
                $employee->setInsuranceAmount(0);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldHaveBeenCalled();
                $test->entityManager->flush()->shouldHaveBeenCalled();
            })
        ];

        yield 'create-employee-without-insurance-amount' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john@example.com');
                $employee->setSalary(60000);
                $employee->setBonus(5000);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldHaveBeenCalled();
                $test->entityManager->flush()->shouldHaveBeenCalled();
            })
        ];

        yield 'create-employee-with-all-fields' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john@example.com');
                $employee->setSalary(60000);
                $employee->setBonus(5000);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setEmploymentStartDate(new \DateTime('2011-01-05'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldHaveBeenCalled();
                $test->entityManager->flush()->shouldHaveBeenCalled();
            })
        ];

        yield 'create-employee-with-invalid-email' => [
            (function($employee) use ($company) {
                $employee->setFirstName('John');
                $employee->setLastName('Due');
                $employee->setEmail('john.com');
                $employee->setSalary(60000);
                $employee->setBonus(5000);
                $employee->setEmploymentStartDate(new \DateTime('1999-12-25'));
                $employee->setEmploymentStartDate(new \DateTime('2011-01-05'));
                $employee->setCompany($company->reveal());

                return $employee;
            })(clone $employee),
            (function($employee, $test) use ($company) {
                $test->companyService->create(Argument::type(Company::class))->willReturn($company);
                $test->validator->validate($employee)->willThrow(ValidationException::class);
                $test->entityManager->rollback()->shouldNotHaveBeenCalled();
                $test->expectException(\Exception::class);
            }),
            (function($test, $employee) {
                $test->entityManager->persist($employee)->shouldNotHaveBeenCalled();
                $test->entityManager->flush()->shouldNotHaveBeenCalled();
            })
        ];
    }
}
