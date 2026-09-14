# UAT Plan - ksf_FA_OrgChart

## Overview
ksf_FA_OrgChart provides org hierarchy display for FrontAccounting customer portals, showing Salesman, Warranty Rep, and Project Team cards.

## Test Strategy

### Approach
- Manual testing by QA team
- Test scenarios executed in FrontAccounting test environment
- Precondition: FA with customer portal enabled, test debtor accounts

### Test Environment
- FrontAccounting 2.4.x with ksf_FA_OrgChart extension
- Test customer accounts: 3 debtors with assigned salesmen, 2 with warranty reps, 1 with project team
- ksf_CRM, ksf_HRM, ksf_PM test data loaded

---

## UAT Scenarios

### UAT-001: View My Salesman Card
**Objective**: Verify customer can view their assigned salesman

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login to customer portal as debtor #100 | Dashboard displayed |
| 2 | Navigate to "My Salesman" page | Salesman card rendered |
| 3 | Verify card shows name, title, dept | John Smith, Sales Rep, Sales |
| 4 | Verify contact links | Email click, Ext shown |

**Pass Criteria**: Salesman card displays correctly with public data

---

### UAT-002: Click Salesman for Details
**Objective**: Verify single click shows extended info

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | On "My Salesman" page, click card | Info panel overlays |
| 2 | Verify extended details | Full dept, Manager name, Direct phone |
| 3 | Click X to dismiss | Panel closes |

**Pass Criteria**: Extended info panel displays

---

### UAT-003: View Manager Chain
**Objective**: Verify customer can view rep's manager hierarchy

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | On "My Salesman" page, click card | Info panel shows |
| 2 | Click "View Manager Chain" | Org hierarchy viewer opens |
| 3 | Verify 3 levels max | Up to 3 manager levels shown |
| 4 | Click manager box | Double-click re-centers |

**Pass Criteria**: Manager chain viewer displays correctly

---

### UAT-004: View My Warranty Rep Card
**Objective**: Verify warranty rep card displays for assigned customers

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as debtor #101 | Dashboard displayed |
| 2 | Navigate to "My Warranty Rep" page | Warranty rep card shown |
| 3 | Verify card shows "Warranty Support" | Dept labeled correctly |
| 4 | Verify contact links | Email, Ext shown |

**Pass Criteria**: Warranty rep card displays with correct department

---

### UAT-005: Navigate from Warranty to Salesman
**Objective**: Verify bi-directional navigation between cards

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | On Warranty rep page, click link | "View My Salesman" link visible |
| 2 | Click link | Salesman page displayed |
| 3 | Verify link back | "View My Warranty Rep" link shown |

**Pass Criteria**: Bi-directional navigation works

---

### UAT-006: View Project Team for Contract
**Objective**: Verify customer can see assigned project team

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as debtor #102 with active contract | Dashboard displayed |
| 2 | Navigate to "My Project Team" page | Project team cards load |
| 3 | Verify cards grouped by contract | "Acme Corp" section shown |
| 4 | Verify role badges | PM (blue), BA (green), Dev (purple) |

**Pass Criteria**: Project team cards display with role badges

---

### UAT-007: Click Project Team Member
**Objective**: Verify single click shows project member details

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | On project team page, click PM card | Info panel shows |
| 2 | Verify extended data | Role, Project assignment, Manager |
| 3 | Verify manager chain | Manager name shown |

**Pass Criteria**: Extended info panel displays for team member

---

### UAT-008: Empty State No Rep Assigned
**Objective**: Verify empty state displays when no rep assigned

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as debtor #103 (no salesman) | Dashboard displayed |
| 2 | Navigate to "My Salesman" page | Empty state card shown |
| 3 | Verify message | "No salesman assigned" message |
| 4 | Verify link | "Contact Support" link shown |

**Pass Criteria**: Empty state card displays with appropriate message

---

### UAT-009: Customer Portal Security
**Objective**: Verify customer can only see their own reps

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login as debtor #100 | Dashboard displayed |
| 2 | Attempt to access debtor #101's data | Access denied |
| 3 | Verify only own data shown | Only #100's rep visible |

**Pass Criteria**: Customer-debtor mapping enforced

---

## UAT Summary

| UC | Name | Tester | Date | Result |
|----|------|--------|------|--------|
| UAT-001 | View My Salesman Card | | | |
| UAT-002 | Click Salesman for Details | | | |
| UAT-003 | View Manager Chain | | | |
| UAT-004 | View My Warranty Rep Card | | | |
| UAT-005 | Navigate from Warranty to Salesman | | | |
| UAT-006 | View Project Team for Contract | | | |
| UAT-007 | Click Project Team Member | | | |
| UAT-008 | Empty State No Rep Assigned | | | |
| UAT-009 | Customer Portal Security | | | |

**Sign-off**: ______________________ Date: ____________

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*