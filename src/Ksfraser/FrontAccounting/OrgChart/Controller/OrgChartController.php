<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\Controller;

use ksfraser\FrontAccounting\Common\App\AbstractTabController;
use ksfraser\OrgChart\Service\OrgChartService;
use ksfraser\OrgChart\Service\PositionService;

/**
 * OrgChartController — controller SRP for the interactive Org Chart view.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class OrgChartController extends AbstractTabController
{
    private $orgChartService;
    private $positionService;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->positionService = new PositionService();
        $this->orgChartService = new OrgChartService($this->positionService);
    }

    protected function getPkField(): string
    {
        return 'id';
    }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'org_chart',
            'table'       => '0_ksf_orgchart_positions',
            'label'       => 'Org Chart',
            'labelPlural' => 'Org Charts',
            'hookPrefix'  => 'org_chart',
            'pk'          => 'id',
            'fields'      => [],
            'fk_ddls'     => [],
            'ddlHooks'    => [],
            'tableSettings' => [],
        ];
    }

    protected function listRows(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        return array_slice($this->orgChartService->getFlatChart(), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->orgChartService->getFlatChart());
    }

    protected function findRecord(string $pk): ?array
    {
        $dto = $this->positionService->findPositionById((int)$pk);
        return $dto ? $dto->toArray() : null;
    }

    protected function createRecord(array $data): int
    {
        return 0;
    }

    protected function updateRecord(string $pk, array $data): void
    {
    }

    protected function deleteRecord(string $pk): void
    {
    }

    protected function fkOptions(): array
    {
        return [];
    }

    /**
     * Get the full org chart tree for the interactive view.
     *
     * @return array Tree nodes
     */
    public function getTree(?int $rootPositionId = null, int $maxDepth = 10): array
    {
        return $this->orgChartService->buildTree($rootPositionId, $maxDepth);
    }

    /**
     * Get the path from root to a position.
     *
     * @param int $positionId
     * @return array Path from root to position
     */
    public function getPathToPosition(int $positionId): array
    {
        return $this->orgChartService->getPathToPosition($positionId);
    }

    /**
     * Get positions at a specific depth level.
     *
     * @param int $depth Depth level
     * @return array Positions at depth
     */
    public function getPositionsAtDepth(int $depth): array
    {
        return $this->orgChartService->getPositionsAtDepth($depth);
    }
}
