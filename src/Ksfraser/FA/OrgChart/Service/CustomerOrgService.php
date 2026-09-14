<?php

declare(strict_types=1);

namespace Ksfraser\FA\OrgChart\Service;

use Ksfraser\FA\OrgChart\Entity\CustomerOrgView;
use Ksfraser\FA\OrgChart\Entity\TeamMemberCard;
use Ksfraser\CRM\Service\CustomerService;
use Ksfraser\HRM\Service\EmployeeService;
use Ksfraser\ProjectManagement\Service\ProjectService;

class CustomerOrgService
{
    private CustomerService $customerService;
    private EmployeeService $employeeService;
    private ?ProjectService $projectService;

    public function __construct(
        CustomerService $customerService,
        EmployeeService $employeeService,
        ?ProjectService $projectService = null
    ) {
        $this->customerService = $customerService;
        $this->employeeService = $employeeService;
        $this->projectService = $projectService;
    }

    public function loadCustomerOrgData(int $debtorNo, ?int $projectId = null): CustomerOrgView
    {
        $customer = $this->customerService->getCustomer($debtorNo);

        if ($customer === null) {
            throw new \RuntimeException("Customer not found: {$debtorNo}");
        }

        $view = new CustomerOrgView();
        $view->setDebtorNo($debtorNo);

        $salesmanId = $customer->getSalesmanId();
        if ($salesmanId !== null) {
            $view->setSalesmanId($salesmanId);
        }

        $warrantyRepId = $this->getWarrantyRepId($customer);
        if ($warrantyRepId !== null) {
            $view->setWarrantyRepId($warrantyRepId);
        }

        if ($projectId !== null) {
            $view->setProjectId($projectId);
        }

        $cards = $this->buildCards($view);
        $view->setCards($cards);

        return $view;
    }

    private function getWarrantyRepId($customer): ?int
    {
        if (method_exists($customer, 'getWarrantyRepId')) {
            return $customer->getWarrantyRepId();
        }
        if (method_exists($customer, 'getDefaultLocation')) {
            $locationId = $customer->getDefaultLocation();
            if ($locationId !== null && $this->projectService !== null) {
                return $this->projectService->getLocationWarrantyRep($locationId);
            }
        }
        return null;
    }

    private function buildCards(CustomerOrgView $view): array
    {
        $cards = [];

        if ($view->getSalesmanId() !== null) {
            $salesmanCard = $this->buildSalesmanCard($view->getSalesmanId());
            if ($salesmanCard !== null) {
                $cards[] = $salesmanCard;
            }
        }

        if ($view->getWarrantyRepId() !== null) {
            $warrantyCard = $this->buildWarrantyCard($view->getWarrantyRepId());
            if ($warrantyCard !== null) {
                $cards[] = $warrantyCard;
            }
        }

        if ($view->getProjectId() !== null && $this->projectService !== null) {
            $projectCards = $this->buildProjectTeamCards($view->getProjectId());
            $cards = array_merge($cards, $projectCards);
        }

        return $cards;
    }

    private function buildSalesmanCard(int $salesmanId): ?TeamMemberCard
    {
        $employee = $this->employeeService->getEmployee($salesmanId);
        if ($employee === null) {
            return null;
        }

        $card = new TeamMemberCard();
        $card->setEmployeeId($salesmanId);
        $card->setType(TeamMemberCard::TYPE_SALESMAN);
        $card->setName($this->formatEmployeeName($employee));
        $card->setTitle($employee->getJobTitle() ?? 'Salesman');
        $card->setEmail($employee->getCompanyEmail() ?? '');
        $card->setPhone($employee->getPhoneExt() ?? '');

        return $card;
    }

    private function buildWarrantyCard(int $warrantyRepId): ?TeamMemberCard
    {
        $employee = $this->employeeService->getEmployee($warrantyRepId);
        if ($employee === null) {
            return null;
        }

        $card = new TeamMemberCard();
        $card->setEmployeeId($warrantyRepId);
        $card->setType(TeamMemberCard::TYPE_WARRANTY);
        $card->setName($this->formatEmployeeName($employee));
        $card->setTitle($employee->getJobTitle() ?? 'Support');
        $card->setEmail($employee->getCompanyEmail() ?? '');
        $card->setPhone($employee->getPhoneExt() ?? '');

        return $card;
    }

    private function buildProjectTeamCards(int $projectId): array
    {
        $cards = [];

        if ($this->projectService === null) {
            return $cards;
        }

        $team = $this->projectService->getProjectTeam($projectId);
        if ($team === null || empty($team)) {
            return $cards;
        }

        $roleLabels = [
            'project_manager' => 'Project Manager',
            'project_coordinator' => 'Project Coordinator', 
            'business_analyst' => 'Business Analyst',
            'developer' => 'Developer',
            'tester' => 'Tester',
            'technical_lead' => 'Tech Lead',
        ];

        foreach ($team as $member) {
            $employeeId = $member['employeeId'] ?? null;
            if ($employeeId === null) {
                continue;
            }

            $employee = $this->employeeService->getEmployee($employeeId);
            if ($employee === null) {
                continue;
            }

            $card = new TeamMemberCard();
            $card->setEmployeeId($employeeId);
            $card->setType(TeamMemberCard::TYPE_PROJECT);
            $card->setName($this->formatEmployeeName($employee));
            $card->setTitle($member['role'] ?? 'Team Member');
            $card->setEmail($employee->getCompanyEmail() ?? '');
            $card->setPhone($employee->getPhoneExt() ?? '');

            $role = $member['role'] ?? '';
            $card->setRoleBadge($roleLabels[$role] ?? ucfirst(str_replace('_', ' ', $role)));

            $cards[] = $card;
        }

        return $cards;
    }

    private function formatEmployeeName($employee): string
    {
        $firstName = $employee->getFirstName() ?? '';
        $lastName = $employee->getLastName() ?? '';
        return trim("{$firstName} {$lastName}") ?: 'Unknown';
    }

    public function hasProjectContext(int $debtorNo): bool
    {
        if ($this->projectService === null) {
            return false;
        }

        $projects = $this->projectService->getCustomerProjects($debtorNo);
        return !empty($projects);
    }

    public function getAvailableProjects(int $debtorNo): array
    {
        if ($this->projectService === null) {
            return [];
        }

        return $this->projectService->getCustomerProjects($debtorNo);
    }
}