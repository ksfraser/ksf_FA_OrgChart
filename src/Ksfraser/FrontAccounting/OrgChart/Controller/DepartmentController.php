<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\Controller;

use ksfraser\FrontAccounting\Common\App\AbstractTabController;
use ksfraser\OrgChart\Service\DepartmentService;

/**
 * DepartmentController — controller SRP for Departments tab.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class DepartmentController extends AbstractTabController
{
    private $service;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->service = new DepartmentService();
    }

    protected function getPkField(): string
    {
        return 'id';
    }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'department',
            'table'       => '0_ksf_orgchart_departments',
            'label'       => 'Department',
            'labelPlural' => 'Departments',
            'hookPrefix'  => 'department',
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
        return array_slice($this->service->searchDepartments([]), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->service->searchDepartments([]));
    }

    protected function findRecord(string $pk): ?array
    {
        $dto = $this->service->findDepartmentById((int)$pk);
        return $dto ? $dto->toArray() : null;
    }

    protected function createRecord(array $data): int
    {
        $dto = $this->service->createDepartment($data);
        return $dto->id;
    }

    protected function updateRecord(string $pk, array $data): void
    {
        $this->service->updateDepartment((int)$pk, $data);
    }

    protected function deleteRecord(string $pk): void
    {
        $this->service->deleteDepartment((int)$pk);
    }

    protected function fkOptions(): array
    {
        return [];
    }
}
