# Test Plan - ksf_FA_OrgChart

## Overview
ksf_FA_OrgChart provides org hierarchy display for FrontAccounting customer portals, supporting Salesman, Warranty, and Project contexts.

## Test Scope

### In Scope
- CustomerOrgPresenter (Salesman + Warranty + Project modes)
- FA page adapters (MySalesmanPage, MyWarrantyRepPage, MyProjectTeamPage)
- RepCardComponent renderers
- OrgHierarchyViewer simplified renderer

### Out of Scope
- ksf_OrgChart business logic
- ksf_CRM debtor mappings
- ksf_HRM employee data
- ksf_PM project assignments

---

## Unit Test Cases

### TC-001: Get Salesman for Debtor
```php
public function testGetSalesmanForDebtor(): void
{
    // Arrange
    $debtorNo = 100;
    $presenter = new CustomerOrgPresenter($this->mockCrmService, $this->mockHrmService);
    
    // Act
    $salesman = $presenter->getSalesmanForDebtor($debtorNo);
    
    // Assert
    $this->assertInstanceOf(Employee::class, $salesman);
    $this->assertNotEmpty($salesman->getId());
}
```

### TC-002: Get Warranty Rep for Debtor
```php
public function testGetWarrantyRepForDebtor(): void
{
    // Arrange
    $debtorNo = 100;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $rep = $presenter->getWarrantyRepForDebtor($debtorNo);
    
    // Assert
    $this->assertInstanceOf(Employee::class, $rep);
}
```

### TC-003: Get Project Team for Contract
```php
public function testGetProjectTeamForContract(): void
{
    // Arrange
    $contractId = 50;
    $debtorNo = 100;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $team = $presenter->getProjectTeamForContract($contractId, $debtorNo);
    
    // Assert
    $this->assertIsArray($team);
    foreach ($team as $member) {
        $this->assertArrayHasKey('employeeId', $member);
        $this->assertArrayHasKey('role', $member);
    }
}
```

### TC-004: Get Rep Public Data
```php
public function testGetRepPublicData(): void
{
    // Arrange
    $employeeId = 200;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $data = $presenter->getRepPublicData($employeeId);
    
    // Assert
    $this->assertArrayHasKey('department', $data);
    $this->assertArrayHasKey('title', $data);
    $this->assertArrayHasKey('email', $data);
    $this->assertArrayNotHasKey('homeAddress', $data); // GDPR
}
```

### TC-005: Get Manager Chain
```php
public function testGetManagerChain(): void
{
    // Arrange
    $repNodeId = 300;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $chain = $presenter->getManagerChain($repNodeId);
    
    // Assert
    $this->assertIsArray($chain);
}
```

### TC-006: Get Direct Reports
```php
public function testGetDirectReports(): void
{
    // Arrange
    $managerNodeId = 400;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $reports = $presenter->getDirectReports($managerNodeId);
    
    // Assert
    $this->assertIsArray($reports);
}
```

### TC-007: Empty State No Salesman
```php
public function testEmptyStateNoSalesman(): void
{
    // Arrange
    $debtorNo = 999; // No mapping
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $salesman = $presenter->getSalesmanForDebtor($debtorNo);
    
    // Assert
    $this->assertNull($salesman);
}
```

### TC-008: Empty State No Warranty Rep
```php
public function testEmptyStateNoWarrantyRep(): void
{
    // Arrange
    $debtorNo = 999;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $rep = $presenter->getWarrantyRepForDebtor($debtorNo);
    
    // Assert
    $this->assertNull($rep);
}
```

### TC-009: Card Render Salesman
```php
public function testRenderSalesmanCard(): void
{
    // Arrange
    $salesman = new Employee(['id' => 1, 'name' => 'John']);
    $renderer = new SalesmanCardRenderer();
    
    // Act
    $html = $renderer->render($salesman);
    
    // Assert
    $this->assertStringContains('John', $html);
    $this->assertStringContains('Sales Rep', $html);
}
```

### TC-010: Card Render Project Team
```php
public function testRenderProjectTeamCard(): void
{
    // Arrange
    $member = [
        'employeeId' => 1,
        'name' => 'Jane',
        'role' => 'Project Manager'
    ];
    $renderer = new ProjectTeamCardRenderer();
    
    // Act
    $html = $renderer->render($member);
    
    // Assert
    $this->assertStringContains('Jane', $html);
    $this->assertStringContains('Project Manager', $html);
    $this->assertStringContains('#1976D2', $html); // Badge color
}
```

### TC-011: Card Render Empty State
```php
public function testRenderEmptyStateCard(): void
{
    // Arrange
    $renderer = new RepCardRenderer();
    
    // Act
    $html = $renderer->renderEmpty('salesman');
    
    // Assert
    $this->assertStringContains('No salesman assigned', $html);
    $this->assertStringContains('Contact Support', $html);
}
```

### TC-012: Info Panel Data
```php
public function testGetInfoPanelData(): void
{
    // Arrange
    $employeeId = 500;
    $presenter = new CustomerOrgPresenter(...);
    
    // Act
    $panelData = $presenter->getInfoPanelData($employeeId);
    
    // Assert
    $this->assertArrayHasKey('fullDepartment', $panelData);
    $this->assertArrayHasKey('directPhone', $panelData);
    $this->assertArrayHasKey('managerName', $panelData);
}
```

---

## Integration Test Cases

### IT-001: My Salesman Page Render
```
Setup: FA customer portal authenticated
Input: Navigate to /customer-portal/my-salesman
Expected: Salesman card rendered for logged-in debtor
```

### IT-002: My Warranty Rep Page Render
```
Setup: FA customer portal authenticated
Input: Navigate to /customer-portal/my-warranty-rep
Expected: Warranty rep card rendered
```

### IT-003: Project Team Page Render
```
Setup: FA customer portal authenticated with active contract
Input: Navigate to /customer-portal/my-project-team
Expected: Project team cards grouped by contract
```

### IT-004: Link Navigation Salesman to Warranty
```
Setup: Salesman page rendered
Input: Click "View My Warranty Rep" link
Expected: Warranty rep card page rendered
```

### IT-005: Manager Chain Viewer
```
Setup: Salesman card rendered
Input: Click "View Manager Chain" button
Expected: Simplified org hierarchy viewer (3 levels max)
```

---

## Test Execution

### Command
```bash
cd /home/kevin/Documents/ksf_FA_OrgChart
./vendor/bin/phpunit --testdox
```

### Coverage Target
- Presenter: 100%
- Renderers: Instantiation + HTML structure
- Page adapters: Instantiation only

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*