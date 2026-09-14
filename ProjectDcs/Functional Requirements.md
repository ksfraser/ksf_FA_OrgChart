# Functional Requirements - ksf_FA_OrgChart

## Overview
ksf_FA_OrgChart provides organizational hierarchy display for FrontAccounting customer portals, supporting three context modes:

| Mode | Cards Shown |
|------|-------------|
| Salesman | Customer's assigned sales rep |
| Warranty | Warranty support rep |
| Project | Contract team (PM, BA, Developer) |

---

## FR-001: Display Assigned Salesman

### Description
Customer portal shows the debtor's assigned salesman card.

### Acceptance Criteria
- [ ] Page loads debtor-specific salesman card
- [ ] Card shows: Name, Title, Department, Email, Phone Ext
- [ ] Empty state shown if no salesman assigned
- [ ] Only authenticated customer can view their rep

### Data Fields
| Field | Source | Format |
|-------|--------|--------|
| Name | ksf_HRM.employee | Text |
| Title | ksf_HRM.employee.jobTitle | Text |
| Department | ksf_HRM.employee.department | Text |
| Email | ksf_HRM.employee.companyEmail | mailto: link |
| Phone Extension | ksf_HRM.employee.phoneExt | Text |

---

## FR-002: Display Assigned Warranty Rep

### Description
Customer portal shows warranty support representative.

### Acceptance Criteria
- [ ] Page loads debtor-specific warranty rep card
- [ ] Card shows: Name, Title, Support Department, Email, Phone
- [ ] Separate from salesman card
- [ ] Empty state if no warranty rep assigned

---

## FR-003: Display Project Team Members

### Description
Customer portal shows assigned project team members for a contract.

### Acceptance Criteria
- [ ] Page loads debtor-specific project team cards
- [ ] Cards grouped by contract/project
- [ ] Each card shows role badge: PM, BA, Developer, etc.
- [ ] Card shows: Name, Role, Department, Email
- [ ] Empty state if no project assigned

### Role Badges
| Role | Badge Color |
|------|-------------|
| Project Manager | Blue (#1976D2) |
| Business Analyst | Green (#388E3C) |
| Developer | Purple (#7B1FA2) |
| Technical Lead | Orange (#F57C00) |
| Designer | Pink (#C2185B) |

### Card HTML Structure
```html
<div class="project-team-card" data-employee-id="123">
  <div class="rep-type-badge" style="background: #1976D2">Project Manager</div>
  <div class="rep-info">
    <div class="rep-name">John Smith</div>
    <div class="rep-role">Project Manager</div>
    <div class="rep-dept">Professional Services</div>
  </div>
  <div class="rep-contact">
    <a href="mailto:...">john@company.com</a>
    <span>Ext: 234</span>
  </div>
</div>
```

---

## FR-004: Single Click Info Panel

### Description
Single-clicking a rep card displays detailed info panel.

### Acceptance Criteria
- [ ] Click detected on rep card (not just card display)
- [ ] Panel overlays card with details
- [ ] Shows: Full department, All contact methods, Manager name
- [ ] Close button and click-outside dismisses
- [ ] Smooth fade-in animation

---

## FR-005: View Manager Chain

### Description
Customer can view manager hierarchy for their assigned rep.

### Acceptance Criteria
- [ ] "View Manager Chain" button on rep card
- [ ] Click opens simplified org chart viewer
- [ ] Shows 1-3 levels of hierarchy (managers only, no reports)
- [ ] Centered on selected rep
- [ ] Manager boxes clickable for drill-down

---

## FR-006: Project Card Link to Salesman

### Description
Project team cards link to salesman page for full context.

### Acceptance Criteria
- [ ] Project card has "View My Salesman" link
- [ ] Warranty card has "View Project Team" link
- [ ] Bi-directional navigation between cards

---

## Non-Functional Requirements

### NFR-001: Performance
- Page load: < 1.5 seconds
- Card render: < 200ms per card
- Manager chain load: < 500ms

### NFR-002: Security
- FA authentication required
- Customer-debtor mapping enforced
- No private employee data exposed
- Contract team data restricted to stakeholders

### NFR-003: Accessibility
- WCAG 2.1 AA compliant
- Keyboard navigation
- Screen reader compatible

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*