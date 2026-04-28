<?php
$module_id = 'OrgChart'; $module_version = '1.0.0'; $module_name = 'Organization Chart'; $module_description = 'Organizational hierarchy and chart';
$module_tables = ['fa_org_positions']; $module_capabilities = ['SA_ORGCHARTVIEW'=>'View Org Chart','SA_ORGCHARTEDIT'=>'Edit Positions'];
function orgchart_install():bool{return install_module_sql('OrgChart');}function orgchart_enable():bool{return enable_module('OrgChart');}function orgchart_disable():bool{return disable_module('OrgChart');}function orgchart_remove():bool{return remove_module_sql('OrgChart');}
add_module($module_name,$module_version,$module_description);