# Requirements Traceability Matrix (RTM) - ksf_FA_OrgChart

## Traceability Grid

| Requirement ID | Requirement | UC | TC | Status |
|----------------|-------------|----|----|--------|
| FR-001 | Display Assigned Salesman | UC-001 | TC-001 | |
| FR-002 | Display Assigned Warranty Rep | UC-004 | TC-002 | |
| FR-003 | Display Project Team Members | UC-007 | TC-003, TC-010 | |
| FR-004 | Single Click Info Panel | UC-002, UC-005, UC-008 | TC-004, TC-012 | |
| FR-005 | View Manager Chain | UC-003, UC-006, UC-009 | TC-005, TC-006 | |
| FR-006 | Project Card Link to Salesman | UC-011 | TC-005 | |
| NFR-001 | Performance | UC-All | IT-001 | |
| NFR-002 | Security | UC-009 | IT-004 | |
| NFR-003 | Accessibility | UC-All | IT-002 | |

## Requirement Details

### Functional Requirements

| ID | Description | Source Document |
|----|-------------|----------------|
| FR-001 | Card shows Name, Title, Department, Email, Phone Ext | FR-001 |
| FR-002 | Warranty card shows "Warranty Support" department | FR-002 |
| FR-003 | Project cards show role badges: PM (blue), BA (green), Dev (purple) | FR-003 |
| FR-004 | Info panel shows Full dept, All contacts, Manager name | FR-004 |
| FR-005 | Manager chain viewer shows up to 3 levels (managers only) | FR-005 |
| FR-006 | Project card has "View My Salesman" link, Warranty has "View Project Team" link | FR-006 |
| FR-007 | Rep Type badge: "Sales Rep" or "Warranty Rep" | FR-007 |
| FR-008 | Empty state shows message + "Contact Support" link | FR-008 |
| FR-009 | FA authentication required, customer-debtor mapping enforced | FR-009 |
| FR-010 | Warranty card has "View My Salesman" link, bidirectional | FR-010 |

### Non-Functional Requirements

| ID | Description | Source Document |
|----|-------------|----------------|
| NFR-001 | Page load < 1.5s, card render < 200ms, chain load < 500ms | NFR-001 |
| NFR-002 | Customer can only see own reps, mapping enforced | NFR-002 |
| NFR-003 | WCAG 2.1 AA, keyboard navigation, screen reader | NFR-003 |

## Coverage Summary

| Category | Total | Covered | Coverage % |
|----------|-------|---------|------------|
| Functional Requirements | 10 | 0 | 0% |
| Non-Functional Requirements | 3 | 0 | 0% |
| **Total** | **13** | **0** | **0%** |

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*