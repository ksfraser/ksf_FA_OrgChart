<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\Controller;

use ksfraser\FrontAccounting\Common\App\AbstractTabController;
use ksfraser\OrgChart\Service\TeamService;

/**
 * TeamController — controller SRP for Teams tab.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class TeamController extends AbstractTabController
{
    private $service;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->service = new TeamService();
    }

    protected function getPkField(): string
    {
        return 'id';
    }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'team',
            'table'       => '0_ksf_orgchart_teams',
            'label'       => 'Team',
            'labelPlural' => 'Teams',
            'hookPrefix'  => 'team',
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
        return array_slice($this->service->searchTeams([]), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->service->searchTeams([]));
    }

    protected function findRecord(string $pk): ?array
    {
        $dto = $this->service->findTeamById((int)$pk);
        return $dto ? $dto->toArray() : null;
    }

    protected function createRecord(array $data): int
    {
        $dto = $this->service->createTeam($data);
        return $dto->id;
    }

    protected function updateRecord(string $pk, array $data): void
    {
        $this->service->updateTeam((int)$pk, $data);
    }

    protected function deleteRecord(string $pk): void
    {
        $this->service->deleteTeam((int)$pk);
    }

    protected function fkOptions(): array
    {
        return [];
    }
}
