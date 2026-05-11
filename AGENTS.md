# AGENTS.md - ksf_FA_OrgChart#

## Architecture Overview#

**FA Module** for Organizational Chart - visualize reporting structure from HRM data.

### Core Principles#
- **SOLID**, **DRY**, **TDD**, **DI**, **SRP**#

## Repository Structure#

```
ksf_FA_OrgChart/
├── sql/#
│   └── fa_org_chart_cache.sql#
├── includes/#
│   └── org_chart_db.inc#
├── pages/#
│   └── org_chart.php#
├── hooks.php#
├── composer.json#
└── ProjectDocs/#
```

## Dependencies#

- **ksf_FA_OrgChart_Core** (business logic)#
- **ksf_FA_HRM** (employee reporting structure)#
- **ksf_FA_Teams** (team visualization)#
- **FrontAccounting 2.4+**#
