<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\Controller;

use ksfraser\FrontAccounting\Common\App\AbstractTabController;
use ksfraser\OrgChart\Service\PositionRelationshipService;

/**
 * RelationshipController — controller SRP for Position Relationships tab.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class RelationshipController extends AbstractTabController
{
    private $service;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->service = new PositionRelationshipService();
    }

    protected function getPkField(): string
    {
        return 'id';
    }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'relationship',
            'table'       => '0_ksf_orgchart_relationships',
            'label'       => 'Relationship',
            'labelPlural' => 'Relationships',
            'hookPrefix'  => 'relationship',
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
        return array_slice($this->service->searchRelationships([]), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->service->searchRelationships([]));
    }

    protected function findRecord(string $pk): ?array
    {
        $dto = $this->service->findRelationshipById((int)$pk);
        return $dto ? $dto->toArray() : null;
    }

    protected function createRecord(array $data): int
    {
        $dto = $this->service->createRelationship($data);
        return $dto->id;
    }

    protected function updateRecord(string $pk, array $data): void
    {
        $this->service->updateRelationship((int)$pk, $data);
    }

    protected function deleteRecord(string $pk): void
    {
        $this->service->deleteRelationship((int)$pk);
    }

    protected function fkOptions(): array
    {
        return [];
    }
}
