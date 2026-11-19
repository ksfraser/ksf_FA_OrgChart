<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\Controller;

use ksfraser\FrontAccounting\Common\App\AbstractTabController;
use ksfraser\OrgChart\Service\PositionService;

/**
 * PositionController — controller SRP for Positions tab.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class PositionController extends AbstractTabController
{
    private $service;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->service = new PositionService();
    }

    protected function getPkField(): string
    {
        return 'id';
    }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'position',
            'table'       => '0_ksf_orgchart_positions',
            'label'       => 'Position',
            'labelPlural' => 'Positions',
            'hookPrefix'  => 'position',
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
        return array_slice($this->service->searchPositions([]), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->service->searchPositions([]));
    }

    protected function findRecord(string $pk): ?array
    {
        $dto = $this->service->findPositionById((int)$pk);
        return $dto ? $dto->toArray() : null;
    }

    protected function createRecord(array $data): int
    {
        $dto = $this->service->createPosition($data);
        return $dto->id;
    }

    protected function updateRecord(string $pk, array $data): void
    {
        $this->service->updatePosition((int)$pk, $data);
    }

    protected function deleteRecord(string $pk): void
    {
        $this->service->deletePosition((int)$pk);
    }

    protected function fkOptions(): array
    {
        return [];
    }
}
