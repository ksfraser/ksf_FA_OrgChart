<?php
/**
 * ksf_FA_OrgChart — single front controller.
 *
 * URL shape:
 *   index.php                                default tab (positions list)
 *   index.php?view=positions                 read a tab
 *   index.php?tab=positions                  alias
 *   index.php?view=org_chart                 org chart tree view
 *   index.php?view=positions&page=3          tab + paging
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */

chdir(__DIR__);

if (file_exists(__DIR__ . '/bootstrap.php')) {
    require_once __DIR__ . '/bootstrap.php';
}

$path_to_root = "../..";

$frontController = new \Ksfraser\FrontAccounting\OrgChart\App\OrgChartFrontController(
    new \Ksfraser\FrontAccounting\OrgChart\App\OrgChartAppShell(),
    'SA_ksf_FA_OrgChartVIEW',
    $path_to_root
);

$page_security = $frontController->getSecurityArea();
include_once($path_to_root . "/includes/session.inc");
add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");

$frontController->run();
