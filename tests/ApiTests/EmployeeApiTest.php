<?php

declare(strict_types=1);

namespace App\Tests\ApiTests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class EmployeeApiTest extends ApiTestCase
{
    /**
     * @group employee-collection
     */
    public function testGetEmployeesCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/employees');

        $responseArray = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/employees', '@context' => '/api/contexts/Employee']);
        $this->assertArrayHasKey('firstName', $responseArray['hydra:member'][0]);
        $this->assertGreaterThan(0, $responseArray['hydra:member']);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @group create-employee
     */
    public function testCreateEmployee(): void
    {
        $body = file_get_contents('./tests/ApiTests/fixtures/create_employee_with_success.json');
        $response = static::createClient()->request('POST', '/api/employee', [
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
