<?php
declare(strict_types=1);

namespace Tests\Unit;

use ksfraser\OrgChart\Dto\PositionDto;
use ksfraser\OrgChart\Dto\DepartmentDto;
use ksfraser\OrgChart\Dto\TeamDto;
use ksfraser\OrgChart\Dto\PositionRelationshipDto;
use ksfraser\OrgChart\Service\PositionService;
use ksfraser\OrgChart\Service\DepartmentService;
use ksfraser\OrgChart\Service\TeamService;
use ksfraser\OrgChart\Service\PositionRelationshipService;
use ksfraser\OrgChart\Service\OrgChartService;
use PHPUnit\Framework\TestCase;

/**
 * @BABOK Related: UT-ORG-001-001-002
 * @since 1.0.0
 */
class OrgChartAdapterIntegrationTest extends TestCase
{
    public function testPositionServiceIsInstantiable(): void
    {
        $service = new PositionService();
        $this->assertInstanceOf(PositionService::class, $service);
    }

    public function testDepartmentServiceIsInstantiable(): void
    {
        $service = new DepartmentService();
        $this->assertInstanceOf(DepartmentService::class, $service);
    }

    public function testTeamServiceIsInstantiable(): void
    {
        $service = new TeamService();
        $this->assertInstanceOf(TeamService::class, $service);
    }

    public function testPositionRelationshipServiceIsInstantiable(): void
    {
        $service = new PositionRelationshipService();
        $this->assertInstanceOf(PositionRelationshipService::class, $service);
    }

    public function testOrgChartServiceIsInstantiable(): void
    {
        $positionService = new PositionService();
        $service = new OrgChartService($positionService);
        $this->assertInstanceOf(OrgChartService::class, $service);
    }

    public function testFullOrgChartWorkflow(): void
    {
        $positionService = new PositionService();
        $departmentService = new DepartmentService();
        $teamService = new TeamService();
        $relationshipService = new PositionRelationshipService();
        $orgChartService = new OrgChartService($positionService);

        // Create department
        $dept = $departmentService->createDepartment([
            'name' => 'Engineering',
            'description' => 'Software engineering',
        ]);

        // Create positions
        $ceo = $positionService->createPosition([
            'title' => 'CEO',
            'department_id' => $dept->id,
            'employee_id' => 100,
            'status' => 'filled',
        ]);

        $cto = $positionService->createPosition([
            'title' => 'CTO',
            'department_id' => $dept->id,
            'employee_id' => 101,
            'reports_to_position_id' => $ceo->id,
            'status' => 'filled',
        ]);

        // Create reporting relationship
        $rel = $relationshipService->createRelationship([
            'source_position_id' => $cto->id,
            'target_position_id' => $ceo->id,
            'relationship_type' => 'reports_to',
            'description' => 'CTO reports to CEO',
        ]);

        // Build org chart tree
        $tree = $orgChartService->buildTree($ceo->id);
        $this->assertCount(1, $tree);
        $this->assertSame('CEO', $tree[0]['position']['title']);
        $this->assertCount(1, $tree[0]['children']);
        $this->assertSame('CTO', $tree[0]['children'][0]['position']['title']);

        // Get path to CTO
        $path = $orgChartService->getPathToPosition($cto->id);
        $this->assertCount(2, $path);
        $this->assertSame('CEO', $path[0]->title);
        $this->assertSame('CTO', $path[1]->title);

        // Get positions at depth 1
        $depth1 = $orgChartService->getPositionsAtDepth(1);
        $this->assertCount(1, $depth1);
        $this->assertSame('CTO', $depth1[0]->title);

        // Verify relationships
        $rels = $relationshipService->getRelationshipsForPosition($cto->id);
        $this->assertCount(1, $rels);
        $this->assertSame('reports_to', $rels[0]->relationship_type);
    }
}
