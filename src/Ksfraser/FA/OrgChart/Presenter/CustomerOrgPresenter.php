<?php

declare(strict_types=1);

namespace Ksfraser\FA\OrgChart\Presenter;

use Ksfraser\FA\OrgChart\Entity\CustomerOrgView;
use Ksfraser\FA\OrgChart\Entity\TeamMemberCard;
use Ksfraser\FA\OrgChart\Service\CustomerOrgService;

class CustomerOrgPresenter
{
    private const CONTEXT_SALESMAN = 'salesman';
    private const CONTEXT_WARRANTY = 'warranty';
    private const CONTEXT_PROJECT = 'project';

    private CustomerOrgService $orgService;
    private int $currentDebtorNo = 0;
    private ?int $currentProjectId = null;
    private string $currentContext = self::CONTEXT_SALESMAN;

    public function __construct(CustomerOrgService $orgService)
    {
        $this->orgService = $orgService;
    }

    public function getCurrentDebtorNo(): int
    {
        return $this->currentDebtorNo;
    }

    public function setCurrentDebtorNo(int $debtorNo): self
    {
        $this->currentDebtorNo = $debtorNo;
        return $this;
    }

    public function getCurrentProjectId(): ?int
    {
        return $this->currentProjectId;
    }

    public function setCurrentProjectId(?int $projectId): self
    {
        $this->currentProjectId = $projectId;
        return $this;
    }

    public function getCurrentContext(): string
    {
        return $this->currentContext;
    }

    public function setCurrentContext(string $context): self
    {
        $valid = [self::CONTEXT_SALESMAN, self::CONTEXT_WARRANTY, self::CONTEXT_PROJECT];
        if (!in_array($context, $valid)) {
            throw new \InvalidArgumentException("Invalid context: {$context}");
        }
        $this->currentContext = $context;
        return $this;
    }

    public function loadCustomerView(?int $debtorNo = null, ?int $projectId = null): CustomerOrgView
    {
        $debtorNo = $debtorNo ?? $this->currentDebtorNo;
        $projectId = $projectId ?? $this->currentProjectId;

        if ($debtorNo === 0) {
            throw new \RuntimeException("DebtorNo required");
        }

        $this->currentDebtorNo = $debtorNo;
        $this->currentProjectId = $projectId;

        return $this->orgService->loadCustomerOrgData($debtorNo, $projectId);
    }

    public function getFilteredCards(string $context = null): array
    {
        $view = $this->loadCustomerView();
        $cards = $view->getCards();

        if ($context === null) {
            $context = $this->currentContext;
        }

        if ($context === self::CONTEXT_SALESMAN) {
            return array_filter($cards, fn($c) => $c instanceof TeamMemberCard && $c->isSalesman());
        }
        if ($context === self::CONTEXT_WARRANTY) {
            return array_filter($cards, fn($c) => $c instanceof TeamMemberCard && $c->isWarrantyRep());
        }
        if ($context === self::CONTEXT_PROJECT) {
            return array_filter($cards, fn($c) => $c instanceof TeamMemberCard && $c->isProjectMember());
        }

        return $cards;
    }

    public function getCardCount(?string $context = null): int
    {
        return count($this->getFilteredCards($context));
    }

    public function hasProjectContext(): bool
    {
        if ($this->currentDebtorNo === 0) {
            return false;
        }
        return $this->orgService->hasProjectContext($this->currentDebtorNo);
    }

    public function getAvailableProjects(): array
    {
        if ($this->currentDebtorNo === 0) {
            return [];
        }
        return $this->orgService->getAvailableProjects($this->currentDebtorNo);
    }

    public function renderCardsHtml(): string
    {
        $cards = $this->getFilteredCards();
        $html = '<div class="org-cards-grid">';

        foreach ($cards as $card) {
            $html .= $this->renderSingleCard($card);
        }

        $html .= '</div>';
        return $html;
    }

    private function renderSingleCard(TeamMemberCard $card): string
    {
        $typeLabel = $this->getCardTypeLabel($card->getType());
        $roleBadge = $card->getRoleBadge();
        $badgeHtml = $roleBadge ? "<span class='badge-role'>{$roleBadge}</span>" : "";

        return <<<HTML
<div class="org-card org-card-{$card->getType()}">
    <div class="org-card-header">
        <span class="card-type">{$typeLabel}</span>
        {$badgeHtml}
    </div>
    <div class="org-card-body">
        <h4 class="card-name">{$card->getName()}</h4>
        <p class="card-title">{$card->getTitle()}</p>
    </div>
    <div class="org-card-footer">
        <span class="card-email">{$card->getEmail()}</span>
        <span class="card-phone">{$card->getPhone()}</span>
    </div>
</div>
HTML;
    }

    private function getCardTypeLabel(string $type): string
    {
        return match ($type) {
            TeamMemberCard::TYPE_SALESMAN => 'Your Salesman',
            TeamMemberCard::TYPE_WARRANTY => 'Warranty Rep',
            TeamMemberCard::TYPE_PROJECT => 'Project Team',
            default => 'Contact',
        };
    }

    public function getViewArray(): array
    {
        $view = $this->loadCustomerView();
        return [
            'debtorNo' => $view->getDebtorNo(),
            'salesmanId' => $view->getSalesmanId(),
            'warrantyRepId' => $view->getWarrantyRepId(),
            'projectId' => $view->getProjectId(),
            'cardCount' => count($view->getCards()),
            'cards' => array_map(fn($c) => $c->toArray(), $view->getCards()),
        ];
    }
}