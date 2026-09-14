# Architecture - ksf_FA_OrgChart

## Overview
ksf_FA_OrgChart is a FrontAccounting adapter providing organizational hierarchy display for customer portals, showing:
1. Assigned salesman
2. Warranty support representative  
3. Project team members (PM, BA, etc.) for contracts

## Context Modes

| Mode | Source | Displayed For |
|------|--------|---------------|
| **Salesman Mode** | ksf_CRM Debtor-Salesman mapping | Customer portal debtors |
| **Warranty Mode** | ksf_CRM Warranty assignment | Customer portal debtors |
| **Project Mode** | ksf_ProjectManagement Project-Team mapping | Contract stakeholders |

## Component Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         UI Layer                                 │
│  ┌─────────────────────────┐     ┌─────────────────────────────┐│
│  │ SalesmanCardComponent   │     │ WarrantyRepCardComponent   ││
│  │ (Assigned salesman box) │     │ (Warranty rep box)          ││
│  └─────────────────────────┘     └─────────────────────────────┘│
│  ┌─────────────────────────────────────────────────────────────┐│
│  │ ProjectTeamCardComponent                                     ││
│  │ (PM, BA, Developer roles)                                    ││
│  └─────────────────────────────────────────────────────────────┘│
│  ┌─────────────────────────────────────────────────────────────┐│
│  │ OrgHierarchyViewer (simplified org chart for portal)        ││
│  └─────────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Presenter Layer                             │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ CustomerOrgPresenter                                        │
│  │ - getSalesmanForDebtor(debtorNo)                             │
│  │ - getWarrantyRepForDebtor(debtorNo)                          │
│  │ - getProjectTeamForContract(contractId, debtorNo)           │
│  │ - getRepPublicData(repNodeId)                               │
│  │ - getManagerChain(repNodeId)                                │
│  └─────────────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Adapter Layer                               │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ CustomerPortalPage (FA extension page)                       │
│  │ - render_salesman_card($debtorNo)                           │
│  │ - render_warranty_card($debtorNo)                          │
│  │ - render_project_team_card($contractId, $debtorNo)         │
│  │ - render_org_hierarchy($centerNodeId)                       │
│  └─────────────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Business Logic (Consumed)                    │
│  ┌─────────────────────┐     ┌─────────────────────┐             │
│  │ ksf_OrgChart       │     │ ksf_CRM             │             │
│  │ - OrgNode           │     │ - DebtorRepository  │             │
│  │ - OrgChartService   │     │ - DebtorSalesman    │             │
│  └─────────────────────┘     │   Mapping           │             │
│                              │ - CustomerRepo       │             │
│                              └─────────────────────┘             │
│  ┌─────────────────────┐     ┌─────────────────────┐             │
│  │ ksf_HRM             │     │ ksf_ProjectManagement │             │
│  │ - EmployeePublic    │     │ - ProjectAssignment │             │
│  │   DataAdapter        │     │ - ProjectEmployee   │             │
│  └─────────────────────┘     │   Repo               │             │
│                              └─────────────────────┘             │
└─────────────────────────────────────────────────────────────────┘
```
┌─────────────────────────────────────────────────────────────────┐
│                         UI Layer                                 │
│  ┌─────────────────────────┐     ┌─────────────────────────────┐ │
│  │ SalesmanCardComponent   │     │ WarrantyRepCardComponent   │ │
│  │ (Assigned salesman box) │     │ (Warranty rep box)         │ │
│  └─────────────────────────┘     └─────────────────────────────┘ │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ OrgHierarchyViewer (simplified org chart for portal)       │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Presenter Layer                             │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ CustomerOrgPresenter                                        │
│  │ - getSalesmanForDebtor(debtorNo)                             │
│  │ - getWarrantyRepForDebtor(debtorNo)                          │
│  │ - loadManagerChain(repNodeId)                                │
│  │ - getRepPublicData(repNodeId)                               │
│  └─────────────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Adapter Layer                               │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │ CustomerPortalPage (FA extension page)                      │
│  │ - render_salesman_card($debtorNo)                           │
│  │ - render_warranty_card($debtorNo)                          │
│  │ - render_org_hierarchy($centerNodeId)                       │
│  └─────────────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Business Logic (Consumed)                   │
│  ┌─────────────────────┐     ┌─────────────────────┐             │
│  │ ksf_OrgChart       │     │ ksf_CRM             │             │
│  │ - OrgNode           │     │ - DebtorRepository  │             │
│  │ - OrgChartService   │     │ - DebtorSalesman    │             │
│  └─────────────────────┘     │   Mapping           │             │
│                              │ - CustomerRepo       │             │
│                              └─────────────────────┘             │
│  ┌─────────────────────┐                                        │
│  │ ksf_HRM             │                                        │
│  │ - EmployeePublic    │                                        │
│  │   DataAdapter        │                                        │
│  └─────────────────────┘                                        │
└─────────────────────────────────────────────────────────────────┘
```

## View Components

### SalesmanCardComponent
- **Purpose**: Display assigned salesman for debtor
- **Data**: Rep public data from ksf_HRM
- **States**: Default (showing rep), Empty (no rep assigned), Clicked (showing info)

### WarrantyRepCardComponent  
- **Purpose**: Display warranty support rep for debtor
- **Data**: Rep public data from ksf_HRM
- **States**: Default (showing rep), Empty (no warranty rep), Clicked (showing info)

### OrgHierarchyViewer
- **Purpose**: Simplified org chart showing rep's manager chain
- **Data**: 1-3 levels from ksf_OrgChart
- **Interaction**: Single-click for info, double-click drill-down

## Presenter Contracts

```php
interface CustomerOrgPresenterInterface
{
    public function getSalesmanForDebtor(int $debtorNo): ?Employee;
    public function getWarrantyRepForDebtor(int $debtorNo): ?Employee;
    public function getRepPublicData(int $employeeId): array;
    public function getManagerChain(int $repNodeId): array;
    public function getDirectReports(int $managerNodeId): array;
}
```

## Data Flow

1. **Customer portal loads** → Presenter.getSalesmanForDebtor(debtorNo) → ksf_CRM → ksf_HRM → Display salesman card
2. **Customer clicks salesman** → Presenter.getRepPublicData(repId) → ksf_HRM → Show dept/title/email/phone
3. **Customer clicks "See manager chain"** → Presenter.getManagerChain(repNodeId) → ksf_OrgChart → Display hierarchy viewer
4. **Customer double-clicks any box** → Re-center on clicked node

## Customer Portal Integration

### Page Types

#### My Salesman Page (debtor/salesman)
```php
// includes/my-salesman.php
class MySalesmanPage {
    public function render($debtorNo) {
        $salesman = $this->presenter->getSalesmanForDebtor($debtorNo);
        $card = new SalesmanCardComponent($salesman);
        
        if ($this->isClicked($salesman->getId())) {
            $info = $this->presenter->getRepPublicData($salesman->getId());
            $card->showInfoPanel($info);
        }
        
        return $card->render();
    }
}
```

#### My Warranty Rep Page (debtor/warranty)
```php
// includes/my-warranty-rep.php  
class MyWarrantyRepPage {
    public function render($debtorNo) {
        $rep = $this->presenter->getWarrantyRepForDebtor($debtorNo);
        $card = new WarrantyRepCardComponent($rep);
        
        if ($this->isClicked($rep->getId())) {
            $info = $this->presenter->getRepPublicData($rep->getId());
            $card->showInfoPanel($info);
        }
        
        return $card->render();
    }
}
```

#### Support Hierarchy Page
```php
// pages/customer-support-hierarchy.php
class CustomerSupportHierarchyPage {
    public function render($centerNodeId, $levelsUp = 3, $levelsDown = 0) {
        $hierarchy = $this->presenter->loadHierarchy($centerNodeId, $levelsUp, $levelsDown);
        return new OrgHierarchyViewer($hierarchy);
    }
}
```

## Card Layout

```
┌────────────────────────────────────────────────┐
│  ┌──────────────────────────────────────────┐  │
│  │  [Icon]  John Smith                     │  │
│  │           Sales Representative          │  │
│  │           Sales Department              │  │
│  └──────────────────────────────────────────┘  │
│                                                 │
│  📧 john.smith@company.com                      │
│  📞 Ext: 234                                   │
│                                                 │
│  [View Manager Chain]                          │
└────────────────────────────────────────────────┘
```

## FA Extension Integration

### Extension Hooks
```php
// includes/ksf_fa_orgchart_extension.php
function ksf_register_orgchart_pages($controller) {
    // Register "My Salesman" page
    $controller->registerPage('crm/debtors/my-salesman', [
        'title' => 'My Salesman',
        'render' => [MySalesmanPage::class, 'render']
    ]);
    
    // Register "My Warranty Rep" page  
    $controller->registerPage('crm/debtors/my-warranty-rep', [
        'title' => 'My Warranty Representative',
        'render' => [MyWarrantyRepPage::class, 'render']
    ]);
}
```

## Security Considerations

1. **Data Access**: Only debtor's assigned rep data exposed
2. **Auth Required**: Customer portal pages require FA authentication
3. **Customer Restriction**: Only see own assigned reps, not all employees
4. **No Private Data**: Only public rep fields displayed

## File Structure

```
ksf_FA_OrgChart/
├── src/Ksfraser/FA/OrgChart/
│   ├── Entity/
│   │   ├── CustomerOrgView.php        # Customer portal view model
│   │   └── RepCardView.php             # Rep card view model
│   ├── Service/
│   │   └── CustomerOrgService.php     # FA-specific org services
│   ├── Presenter/
│   │   └── CustomerOrgPresenter.php   # MVP presenter
│   ├── View/
│   │   ├── SalesmanCardComponent.php  # Salesman card component
│   │   ├── WarrantyRepCardComponent.php # Warranty rep component
│   │   └── OrgHierarchyViewer.php    # Hierarchy viewer component
│   ├── Adapter/
│   │   └── FAPageAdapter.php          # FA page adapter
│   └── Events/
│       ├── RepSelectedEvent.php       # Single click event
│       └── HierarchyDrilledEvent.php   # Double click event
├── tests/Unit/
│   ├── Presenter/
│   │   └── CustomerOrgPresenterTest.php
│   └── Service/
│       └── CustomerOrgServiceTest.php
├── includes/
│   ├── my-salesman.php                # My Salesman page
│   ├── my-warranty-rep.php            # My Warranty Rep page
│   └── ksf_fa_orgchart_extension.php  # FA extension hooks
├── pages/
│   └── customer-support-hierarchy.php # Support hierarchy page
├── ProjectDcs/                        # Documentation
└── composer.json                      # Dependencies
```

## Comparison: WP vs FA Adapters

| Feature | ksf_WP_OrgChart | ksf_FA_OrgChart |
|---------|-----------------|-----------------|
| Users | Internal employees | External customers |
| Search | Full org search | Assigned rep only |
| Levels | 1-5 configurable | 1-3 (portal context) |
| Interaction | Single/double click | Single/double click |
| Data Shown | All public employees | Assigned reps only |
| Use Case | ESS org chart | Customer portal rep |

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*