<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\FA\OrgChart\Service;

use Ksfraser\FA\OrgChart\Entity\CustomerOrgView;
use Ksfraser\FA\OrgChart\Entity\TeamMemberCard;
use Ksfraser\FA\OrgChart\Service\CustomerOrgService;
use PHPUnit\Framework\TestCase;

class CustomerOrgServiceTest extends TestCase
{
    private CustomerOrgService $service;
    private $mockCustomerService;
    private $mockEmployeeService;
    private $mockProjectService;

    protected function setUp(): void
    {
        $this->mockCustomerService = $this->createMock(\Ksfraser\CRM\Service\CustomerService::class);
        $this->mockEmployeeService = $this->createMock(\Ksfraser\HRM\Service\EmployeeService::class);
        $this->mockProjectService = null;

        $this->service = new CustomerOrgService(
            $this->mockCustomerService,
            $this->mockEmployeeService,
            $this->mockProjectService
        );
    }

    public function testServiceInstantiation(): void
    {
        $this->assertInstanceOf(CustomerOrgService::class, $this->service);
    }

    public function testHasProjectContextReturnsFalseWithoutProjectService(): void
    {
        $result = $this->service->hasProjectContext(123);

        $this->assertFalse($result);
    }

    public function testHasProjectContextReturnsFalseWithProjectServiceNoProjects(): void
    {
        $mockProjectService = $this->createMock(\Ksfraser\ProjectManagement\Service\ProjectService::class);
        $mockProjectService->method('getCustomerProjects')
            ->willReturn([]);

        $service = new CustomerOrgService(
            $this->mockCustomerService,
            $this->mockEmployeeService,
            $mockProjectService
        );

        $result = $service->hasProjectContext(123);

        $this->assertFalse($result);
    }

    public function testHasProjectContextReturnsTrueWhenProjectsExist(): void
    {
        $mockProjectService = $this->createMock(\Ksfraser\ProjectManagement\Service\ProjectService::class);
        $mockProjectService->method('getCustomerProjects')
            ->willReturn([['id' => 1, 'name' => 'Test Project']]);

        $service = new CustomerOrgService(
            $this->mockCustomerService,
            $this->mockEmployeeService,
            $mockProjectService
        );

        $result = $service->hasProjectContext(123);

        $this->assertTrue($result);
    }

    public function testGetAvailableProjectsReturnsEmptyWithoutProjectService(): void
    {
        $result = $this->service->getAvailableProjects(123);

        $this->assertSame([], $result);
    }

    public function testGetAvailableProjectsReturnsProjects(): void
    {
        $projects = [
            ['id' => 1, 'name' => 'Project A'],
            ['id' => 2, 'name' => 'Project B'],
        ];

        $mockProjectService = $this->createMock(\Ksfraser\ProjectManagement\Service\ProjectService::class);
        $mockProjectService->method('getCustomerProjects')
            ->willReturn($projects);

        $service = new CustomerOrgService(
            $this->mockCustomerService,
            $this->mockEmployeeService,
            $mockProjectService
        );

        $result = $service->getAvailableProjects(123);

        $this->assertCount(2, $result);
        $this->assertSame('Project A', $result[0]['name']);
    }
}