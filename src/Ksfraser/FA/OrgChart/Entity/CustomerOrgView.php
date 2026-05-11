<?php

declare(strict_types=1);

namespace Ksfraser\FA\OrgChart\Entity;

class CustomerOrgView
{
    private int $debtorNo;
    private ?int $salesmanId = null;
    private ?int $warrantyRepId = null;
    private ?int $projectId = null;
    private array $cards = [];
    private string $context = 'crm';

    public function getDebtorNo(): int
    {
        return $this->debtorNo;
    }

    public function setDebtorNo(int $debtorNo): self
    {
        $this->debtorNo = $debtorNo;
        return $this;
    }

    public function getSalesmanId(): ?int
    {
        return $this->salesmanId;
    }

    public function setSalesmanId(?int $salesmanId): self
    {
        $this->salesmanId = $salesmanId;
        return $this;
    }

    public function getWarrantyRepId(): ?int
    {
        return $this->warrantyRepId;
    }

    public function setWarrantyRepId(?int $warrantyRepId): self
    {
        $this->warrantyRepId = $warrantyRepId;
        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(?int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function setCards(array $cards): self
    {
        $this->cards = $cards;
        return $this;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'debtorNo' => $this->debtorNo,
            'salesmanId' => $this->salesmanId,
            'warrantyRepId' => $this->warrantyRepId,
            'projectId' => $this->projectId,
            'cards' => $this->cards,
            'context' => $this->context,
        ];
    }

    public static function fromArray(array $data): self
    {
        $view = new self();
        $view->setDebtorNo($data['debtorNo'] ?? 0);
        $view->setSalesmanId($data['salesmanId'] ?? null);
        $view->setWarrantyRepId($data['warrantyRepId'] ?? null);
        $view->setProjectId($data['projectId'] ?? null);
        $view->setCards($data['cards'] ?? []);
        $view->setContext($data['context'] ?? 'crm');
        return $view;
    }
}