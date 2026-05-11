# Use Cases - ksf_FA_OrgChart

## Overview
ksf_FA_OrgChart supports three context modes for FrontAccounting customer portal.

---

## UC-001: View My Salesman

**Actor**: Customer (Authenticated debtor user)  
**Precondition**: Customer logged in, debtor associated  
**Trigger**: Customer navigates to "My Salesman" page  
**Basic Flow**:
1. System loads debtor No from session
2. System queries Debtor-Salesman mapping from ksf_CRM
3. System loads salesman employee data from ksf_HRM
4. System renders salesman card component
5. Card displays: Name, Title, Dept, Email, Ext

**Postcondition**: Salesman card displayed to customer

---

## UC-002: Click Salesman for Details

**Actor**: Customer  
**Precondition**: Salesman card displayed  
**Trigger**: Customer single-clicks on salesman card  
**Basic Flow**:
1. System highlights selected card
2. Info panel overlays card
3. Panel shows: Full dept, All contacts, Manager name
4. Customer clicks X or outside to dismiss

**Postcondition**: Info panel displayed

---

## UC-003: View Manager Chain

**Actor**: Customer  
**Precondition**: Salesman card displayed  
**Trigger**: Customer clicks "View Manager Chain" button  
**Basic Flow**:
1. System identifies salesman org node from ksf_OrgChart
2. System loads manager chain (up to 3 levels)
3. System renders simplified org hierarchy viewer
4. Viewer centered on salesman
5. Manager boxes shown above, no reports

**Postcondition**: Manager chain viewer displayed

---

## UC-004: View My Warranty Rep

**Actor**: Customer  
**Precondition**: Customer logged in, warranty assignment exists  
**Trigger**: Customer navigates to "My Warranty Rep" page  
**Basic Flow**:
1. System loads debtor No from session
2. System queries warranty assignment from ksf_CRM
3. System loads warranty rep employee data from ksf_HRM
4. System renders warranty rep card component
5. Card displays: Name, Title, "Warranty Support", Email, Phone

**Postcondition**: Warranty rep card displayed

---

## UC-005: Click Warranty Rep for Details

**Actor**: Customer  
**Precondition**: Warranty rep card displayed  
**Trigger**: Customer single-clicks on warranty card  
**Basic Flow**:
1. System highlights selected card
2. Info panel shows extended details
3. Customer dismisses panel

**Postcondition**: Info panel displayed

---

## UC-006: Navigate to Salesman Page

**Actor**: Customer  
**Precondition**: Warranty rep card displayed  
**Trigger**: Customer clicks "View My Salesman" link  
**Basic Flow**:
1. System renders salesman card component
2. Customer sees both reps linked

**Postcondition**: Salesman card displayed

---

## UC-007: View Project Team for Contract

**Actor**: Customer  
**Precondition**: Customer logged in, has active contract with project  
**Trigger**: Customer navigates to "My Project Team" page  
**Basic Flow**:
1. System loads debtor No from session
2. System queries active contracts from ksf_ProjectManagement
3. System loads project team assignments (PM, BA, Developers)
4. System renders project team cards grouped by contract
5. Each card shows: Name, Role badge, Dept, Email

**Postcondition**: Project team cards displayed

---

## UC-008: Click Project Team Member for Details

**Actor**: Customer  
**Precondition**: Project team cards displayed  
**Trigger**: Customer single-clicks on team member card  
**Basic Flow**:
1. System highlights selected card
2. Info panel shows extended details
3. Details include: Role, Project assignment, Manager chain

**Postcondition**: Info panel displayed

---

## UC-009: View Project Manager Hierarchy

**Actor**: Customer  
**Precondition**: Project team card selected  
**Trigger**: Customer clicks "View Manager Chain"  
**Basic Flow**:
1. System identifies selected team member org node
2. System loads org hierarchy from ksf_OrgChart
3. System renders simplified hierarchy viewer
4. Shows up to 3 levels of managers (no reports)

**Postcondition**: Manager hierarchy viewer displayed

---

## UC-010: Contact Project Team Member

**Actor**: Customer  
**Precondition**: Project team card displayed  
**Trigger**: Customer clicks email/phone link  
**Basic Flow**:
1. System pre-fills email template or initiates call
2. Customer confirms action
3. Contact logged in system

**Postcondition**: Contact initiated

---

## UC-011: View My Salesman from Project Page

**Actor**: Customer  
**Precondition**: Customer on project team page  
**Trigger**: Customer clicks "View My Salesman" link  
**Basic Flow**:
1. System renders salesman card
2. Bi-directional navigation established

**Postcondition**: Salesman card displayed

---

## UC-012: Empty State No Rep Assigned

**Actor**: Customer  
**Precondition**: Customer logged in, but no rep assigned  
**Trigger**: System renders card with empty state  
**Basic Flow**:
1. System queries mapping, returns null
2. System renders empty state card
3. Message: "No [type] rep assigned to your account"
4. "Contact Support" link displayed

**Postcondition**: Empty state card displayed

---

## Use Case Summary Table

| UC | Name | Mode | Actor | Trigger |
|----|------|------|-------|---------|
| UC-001 | View My Salesman | Salesman | Customer | Page load |
| UC-002 | Click Salesman for Details | Salesman | Customer | Single click |
| UC-003 | View Manager Chain | Salesman | Customer | Button click |
| UC-004 | View My Warranty Rep | Warranty | Customer | Page load |
| UC-005 | Click Warranty Rep for Details | Warranty | Customer | Single click |
| UC-006 | Navigate to Salesman Page | Warranty | Customer | Link click |
| UC-007 | View Project Team for Contract | Project | Customer | Page load |
| UC-008 | Click Project Team Member for Details | Project | Customer | Single click |
| UC-009 | View Project Manager Hierarchy | Project | Customer | Button click |
| UC-010 | Contact Project Team Member | Project | Customer | Contact click |
| UC-011 | View My Salesman from Project Page | Project | Customer | Link click |
| UC-012 | Empty State No Rep Assigned | All | System | No mapping |

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*