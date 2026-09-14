<?php

declare(strict_types=1);

namespace Ksfraser\FA\OrgChart\Entity;

class TeamMemberCard
{
    public const TYPE_SALESMAN = 'salesman';
    public const TYPE_WARRANTY = 'warranty';
    public const TYPE_PROJECT = 'project';

    private int $employeeId;
    private string $type;
    private ?string $roleBadge = null;
    private string $name = '';
    private string $title = '';
    private string $email = '';
    private string $phone = '';

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function setEmployeeId(int $employeeId): self
    {
        $this->employeeId = $employeeId;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $validTypes = [self::TYPE_SALESMAN, self::TYPE_WARRANTY, self::TYPE_PROJECT];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException("Invalid card type: {$type}");
        }
        $this->type = $type;
        return $this;
    }

    public function getRoleBadge(): ?string
    {
        return $this->roleBadge;
    }

    public function setRoleBadge(?string $roleBadge): self
    {
        $this->roleBadge = $roleBadge;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function isSalesman(): bool
    {
        return $this->type === self::TYPE_SALESMAN;
    }

    public function isWarrantyRep(): bool
    {
        return $this->type === self::TYPE_WARRANTY;
    }

    public function isProjectMember(): bool
    {
        return $this->type === self::TYPE_PROJECT;
    }

    public function toArray(): array
    {
        return [
            'employeeId' => $this->employeeId,
            'type' => $this->type,
            'roleBadge' => $this->roleBadge,
            'name' => $this->name,
            'title' => $this->title,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    public static function fromArray(array $data): self
    {
        $card = new self();
        $card->setEmployeeId($data['employeeId'] ?? 0);
        $card->setType($data['type'] ?? self::TYPE_SALESMAN);
        $card->setRoleBadge($data['roleBadge'] ?? null);
        $card->setName($data['name'] ?? '');
        $card->setTitle($data['title'] ?? '');
        $card->setEmail($data['email'] ?? '');
        $card->setPhone($data['phone'] ?? '');
        return $card;
    }
}