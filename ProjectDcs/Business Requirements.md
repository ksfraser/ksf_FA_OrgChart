# Business Requirements - ksf_FA_OrgChart

## Project Overview
ksf_FA_OrgChart provides organizational hierarchy display for FrontAccounting customer portals, showing assigned representative types:

1. **Salesman Mode**: Customer's assigned salesman
2. **Warranty Mode**: Warranty support representative  
3. **Project Mode**: Contract team members (PM, BA, etc.)

## Context Modes

| Mode | Source | Displayed For |
|------|--------|---------------|
| **Salesman Mode** | ksf_CRM Debtor-Salesman mapping | Customer portal debtors |
| **Warranty Mode** | ksf_CRM Warranty assignment | Customer portal debtors |
| **Project Mode** | ksf_ProjectManagement Project-Team mapping | Contract stakeholders |

## Integration Dependencies

### Provided To
| Module | Data Provided |
|--------|---------------|
| ksf_FA | FrontAccounting customer portal integration |

### Consumed From
| Module | Data Consumed |
|--------|---------------|
| ksf_OrgChart | Org node data, reporting structure |
| ksf_CRM | Customer-debtor mappings, assigned salesmen |
| ksf_HRM | Employee public data (salesman/warranty rep details) |
| ksf_ProjectManagement | Project team assignments (PM, BA, etc.) |

## Business Value

### Problem Statement
Customers in the B2B portal cannot easily identify their assigned representative or understand warranty/contract support chain.

### Solution
Display assigned representatives in customer portal with drill-down capability:

1. **Salesman Card** - Customer's assigned sales rep
2. **Warranty Rep Card** - Warranty support rep
3. **Project Team Card** - Contract team (PM, BA, Developer roles)

### User Stories (Salesman)
- UC-001: View My Salesman
- UC-002: Click Salesman for Details
- UC-003: View Manager Chain

### User Stories (Warranty)
- UC-004: View My Warranty Rep
- UC-005: Click Warranty Rep for Details
- UC-006: Navigate to Salesman Page

### User Stories (Project)
- UC-007: View Project Team for Contract
- UC-008: Click Project Team Member for Details
- UC-009: View Project Manager Hierarchy
- UC-010: Contact Project Team (email/phone)

### Compliance
- Only public sales rep data displayed
- Customer-debtor mappings restricted to authenticated users
- Project team data restricted to contract stakeholders

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*