<?php

declare(strict_types=1);

namespace Ksfraser\FA\OrgChart\View;

class CustomerOrgView
{
    private string $templatePath = '';
    private array $viewData = [];

    public function __construct(string $templatePath = '')
    {
        $this->templatePath = $templatePath ?: __DIR__ . '/../../templates/';
    }

    public function setViewData(array $data): void
    {
        $this->viewData = $data;
    }

    public function renderCustomerOrgPage(): string
    {
        $html = '<div class="ksf-facrm-org-container">';
        $html .= $this->renderHeader();
        $html .= $this->renderContextToggle();
        $html .= $this->renderCardsGrid();
        $html .= '</div>';
        return $html;
    }

    private function renderHeader(): string
    {
        return <<<HTML
<div class="ksf-facrm-org-header">
    <h2 class="ksf-facrm-org-title">Your Contact Team</h2>
    <div class="ksf-facrm-org-context">
        <button type="button" class="ksf-facrm-org-context-btn active" data-context="all">
            All
        </button>
        <button type="button" class="ksf-facrm-org-context-btn" data-context="salesman">
            Salesman
        </button>
        <button type="button" class="ksf-facrm-org-context-btn" data-context="warranty">
            Warranty
        </button>
        <button type="button" class="ksf-facrm-org-context-btn" data-context="project">
            Project Team
        </button>
    </div>
</div>
HTML;
    }

    private function renderContextToggle(): string
    {
        $hasProject = $this->viewData['hasProjectContext'] ?? false;

        $html = '<div class="ksf-facrm-org-context-section">';

        if ($hasProject) {
            $html .= '<div class="ksf-facrm-org-project-select">';
            $html .= '<label for="facrm-org-project">Project:</label>';
            $html .= '<select id="facrm-org-project" name="project_id">';
            $html .= '<option value="">-- Select Project --</option>';

            $projects = $this->viewData['availableProjects'] ?? [];
            foreach ($projects as $project) {
                $selected = ($this->viewData['currentProjectId'] ?? 0) == $project['id'] ? 'selected' : '';
                $html .= '<option value="' . $project['id'] . '" ' . $selected . '>' .
                         htmlspecialchars($project['name'] ?? 'Project #' . $project['id']) . '</option>';
            }

            $html .= '</select></div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function renderCardsGrid(): string
    {
        $cards = $this->viewData['cards'] ?? [];
        $currentContext = $this->viewData['currentContext'] ?? 'all';

        $html = '<div class="org-cards-grid" id="facrm-org-cards">';

        if (empty($cards)) {
            return $html . '<div class="org-cards-empty">No contacts available</div></div>';
        }

        foreach ($cards as $card) {
            $html .= $this->renderSingleCard($card, $currentContext);
        }

        $html .= '</div>';
        return $html;
    }

    private function renderSingleCard(array $card, string $filter): string
    {
        $type = $card['type'] ?? 'salesman';

        if ($filter !== 'all' && $filter !== $type) {
            return '';
        }

        $typeLabel = $this->getTypeLabel($type);
        $roleBadge = $card['roleBadge'] ?? '';
        $badgeHtml = $roleBadge ? "<span class=\"badge-role\">{$roleBadge}</span>" : "";

        $name = $card['name'] ?? 'Unknown';
        $title = $card['title'] ?? '';
        $email = $card['email'] ?? '';
        $phone = $card['phone'] ?? '';

        $cardClass = "org-card-{$type}";

        return <<<HTML
<div class="org-card {$cardClass}" data-type="{$type}">
    <div class="org-card-header">
        <span class="card-type">{$typeLabel}</span>
        {$badgeHtml}
    </div>
    <div class="org-card-body">
        <h4 class="card-name">{$name}</h4>
        <p class="card-title">{$title}</p>
    </div>
    <div class="org-card-footer">
        <span class="card-email">{$email}</span>
        <span class="card-phone">{$phone}</span>
    </div>
</div>
HTML;
    }

    private function getTypeLabel(string $type): string
    {
        if ($type === 'salesman') {
            return 'Your Salesman';
        }
        if ($type === 'warranty') {
            return 'Warranty Rep';
        }
        if ($type === 'project') {
            return 'Project Team';
        }
        return 'Contact';
    }

    public function renderProjectTeamSection(array $teamMembers): string
    {
        if (empty($teamMembers)) {
            return '';
        }

        $html = '<div class="ksf-facrm-org-project-team">';
        $html .= '<h3>Project Team Members</h3>';
        $html .= '<div class="org-cards-grid">';

        foreach ($teamMembers as $member) {
            $html .= $this->renderProjectMemberCard($member);
        }

        $html .= '</div></div>';
        return $html;
    }

    private function renderProjectMemberCard(array $member): string
    {
        $name = $member['name'] ?? 'Unknown';
        $role = $member['role'] ?? 'Member';
        $email = $member['email'] ?? '';

        return <<<HTML
<div class="org-card org-card-project">
    <div class="org-card-header">
        <span class="card-type">Project Team</span>
        <span class="badge-role">{$role}</span>
    </div>
    <div class="org-card-body">
        <h4 class="card-name">{$name}</h4>
        <p class="card-title">{$role}</p>
    </div>
    <div class="org-card-footer">
        <span class="card-email">{$email}</span>
    </div>
</div>
HTML;
    }

    public function getAjaxHandlers(): array
    {
        return [
            'ksf_facrm_org_load' => 'handleOrgLoad',
            'ksf_facrm_org_filter' => 'handleOrgFilter',
            'ksf_facrm_org_project' => 'handleProjectChange',
        ];
    }

    public function handleOrgLoad(array $data): array
    {
        $debtorNo = intval($data['debtor_no'] ?? 0);
        $projectId = isset($data['project_id']) ? intval($data['project_id']) : null;

        return [
            'success' => true,
            'data' => [
                'debtorNo' => $debtorNo,
                'projectId' => $projectId,
                'hasProjectContext' => $projectId !== null,
            ],
        ];
    }

    public function handleOrgFilter(array $data): array
    {
        $context = $data['context'] ?? 'all';

        return [
            'success' => true,
            'data' => [
                'filteredCards' => [],
                'context' => $context,
            ],
        ];
    }

    public function handleProjectChange(array $data): array
    {
        $projectId = intval($data['project_id'] ?? 0);

        return [
            'success' => true,
            'data' => [
                'projectId' => $projectId,
                'cardCount' => 0,
            ],
        ];
    }
}