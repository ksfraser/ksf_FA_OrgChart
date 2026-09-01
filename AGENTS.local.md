<!-- Repo-specific appendix to the shared AGENTS.md. Generic conventions live in AGENTS_ARCH.md (hardlinked). -->

```markdown
# AGENTS.local.md — ksf_FA_OrgChart
## Overview
**FA Module** for Organizational Chart — visualize reporting structure from HRM data.
## Repository Structure
```
ksf_FA_OrgChart/
├── sql/
│   └── fa_org_chart_cache.sql
├── includes/
│   └── org_chart_db.inc
├── pages/
│   └── org_chart.php
├── hooks.php
├── composer.json
└── ProjectDocs/
```
## Dependencies
- **ksf_FA_OrgChart_Core** (business logic)
- **ksf_FA_HRM** (employee reporting structure)
- **ksf_FA_Teams** (team visualization)
- **FrontAccounting 2.4+**
```
