<?php
declare(strict_types=1);

/**
 * ksf_FA_OrgChart - FrontAccounting Module
 *
 * FA adapter over ksfraser/ksf-org-chart standalone business logic.
 *
 * @package   ksfraser\FrontAccounting\OrgChart
 * @author    Kevin Fraser
 * @version   2.4.19-0
 * @license   GPL-3.0
 *
 * @BABOK Related: FR-ORG-001
 *
 * @hook      install           Called when module is installed.
 * @hook      uninstall         Called when module is deleted.
 * @hook      install_options   Register pages under Org Chart app.
 * @hook      install_access    Define security areas.
 * @hook      activate_extension Install SQL schema.
 */

define('SS_ksf_FA_OrgChart', 148 << 8);

$autoload = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

use Ksfraser\FrontAccounting\OrgChart\App\OrgChartAppShell;
use Ksfraser\FrontAccounting\OrgChart\App\OrgChartFrontController;

class hooks_ksf_FA_OrgChart extends hooks
{
    use \Ksfraser\Traits\HookQueryProviderTrait;

    var $module_name = 'ksf_FA_OrgChart';
    var $version     = '2.4.19-0';

    protected function _getAdvertisedValues(): array
    {
        return [
            'orgchart.hooks_version' => '1.0',
            'orgchart.module_version' => '1.0.0',
            'orgchart.features' => ['positions', 'departments', 'teams', 'relationships', 'org_chart_tree'],
        ];
    }

    public function getModuleConstants(&$data, $opts = null)
    {
        $constants = [
            'KSF_ORG_CHART_MODULE_NAME' => $this->module_name,
            'KSF_ORG_CHART_CAPABILITIES' => 'position_create,department_create,team_create,relationship_create,org_chart_view',
        ];
        $data['constants'] = $constants;
        $data['_module'] = $this->module_name;
        return $constants;
    }

    public function getModuleCapabilities(&$data, $opts = null)
    {
        $capabilities = [
            'position_create' => [
                'description' => 'Create org chart positions',
                'methods' => ['CREATE_POSITION', 'POSITION_CREATED'],
            ],
            'department_create' => [
                'description' => 'Create org chart departments',
                'methods' => ['CREATE_DEPARTMENT', 'DEPARTMENT_CREATED'],
            ],
            'team_create' => [
                'description' => 'Create org chart teams',
                'methods' => ['CREATE_TEAM', 'TEAM_CREATED'],
            ],
            'relationship_create' => [
                'description' => 'Create RACI relationships between positions',
                'methods' => ['CREATE_RELATIONSHIP', 'RELATIONSHIP_CREATED'],
            ],
            'org_chart_view' => [
                'description' => 'View interactive org chart tree',
                'methods' => ['VIEW_ORG_CHART', 'ORG_CHART_RENDERED'],
            ],
        ];
        $data['capabilities'] = $capabilities;
        $data['_module'] = $this->module_name;
        return $capabilities;
    }

    public function hasCapability(&$data, $opts = null)
    {
        $cap = $opts['capability'] ?? null;
        if ($cap === null) {
            return false;
        }
        $available = array_keys($this->getModuleCapabilities($data)['capabilities'] ?? []);
        return in_array($cap, $available, true);
    }

    public function respondToCapabilityRequest(&$data, $opts = null)
    {
        $cap = $opts['capability'] ?? null;
        if ($cap === null || !$this->hasCapability($data, $opts)) {
            $data['supported'] = false;
            return;
        }
        $data['supported'] = true;
        $data['module'] = $this->module_name;
    }

    /**
     * Register module menus under Org Chart application
     */
    function install_options($app) {
        if ($app->id == 'orders' && method_exists($this, 'add_module_app')) {
            $this->add_module_app('orgchart_positions', _("Positions"), 'modules/ksf_FA_OrgChart/index.php?view=positions', SA_ksf_FA_OrgChartVIEW);
            $this->add_module_app('orgchart_departments', _("Departments"), 'modules/ksf_FA_OrgChart/index.php?view=departments', SA_ksf_FA_OrgChartVIEW);
            $this->add_module_app('orgchart_teams', _("Teams"), 'modules/ksf_FA_OrgChart/index.php?view=teams', SA_ksf_FA_OrgChartVIEW);
            $this->add_module_app('orgchart_relationships', _("Relationships"), 'modules/ksf_FA_OrgChart/index.php?view=relationships', SA_ksf_FA_OrgChartVIEW);
            $this->add_module_app('orgchart_view', _("Org Chart"), 'modules/ksf_FA_OrgChart/index.php?view=org_chart', SA_ksf_FA_OrgChartVIEW);
            $this->add_module_app('orgchart_config', _("Org Chart Config"), 'modules/ksf_FA_OrgChart/index.php?view=config', SA_ksf_FA_OrgChartMANAGE);
        }
    }

    /**
     * Define security permission areas
     */
    function install_access() {
        $security_sections[SS_ksf_FA_OrgChart] = _("Org Chart");
        $security_areas['SA_ksf_FA_OrgChartVIEW'] = array(
            SS_ksf_FA_OrgChart | 1,
            _("View Org Chart")
        );
        $security_areas['SA_ksf_FA_OrgChartMANAGE'] = array(
            SS_ksf_FA_OrgChart | 2,
            _("Manage Org Chart Config")
        );
        return array($security_areas, $security_sections);
    }

    /**
     * Install database schema on activation
     */
    function activate_extension($company, $check_only=true) {
        $this->ensure_composer_dependencies();

        if (!$this->ksf_fa_common_available()) {
            display_warning(_('Org Chart requires the ksf-fa-common Composer package. Run composer install, then re-activate Org Chart.'));
            return false;
        }

        if (!$check_only) {
            $this->install_common_table('ksf_modules.sql');
        }

        if (file_exists(dirname(__FILE__) . '/sql/install.sql')) {
            $updates = array(
                'ksf_orgchart_positions.sql'    => array('orgchart_positions'),
                'ksf_orgchart_departments.sql'  => array('orgchart_departments'),
                'ksf_orgchart_teams.sql'        => array('orgchart_teams'),
                'ksf_orgchart_relationships.sql'=> array('orgchart_relationships'),
            );
            return $this->update_databases($company, $updates, $check_only);
        }

        return true;
    }

    private function ksf_fa_common_available()
    {
        $autoload = dirname(__FILE__) . '/vendor/autoload.php';
        if (!file_exists($autoload)) {
            return false;
        }
        return class_exists('ksfraser\\FrontAccounting\\Common\\Utils\\SchemaInstaller', false);
    }

    private function install_common_table(string $tableFile): bool
    {
        $vendorDir = dirname(__FILE__) . '/vendor/ksfraser/ksf-fa-common';
        $sqlPath   = $vendorDir . '/sql/' . $tableFile;

        if (!file_exists($sqlPath)) {
            error_log('KSF Module: common table SQL not found: ' . $sqlPath);
            return false;
        }

        $sql = file_get_contents($sqlPath);
        if ($sql === false) {
            return false;
        }

        $sql = preg_replace('/--[^\n]*/', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $statements = array_map('trim', explode(';', $sql));

        $ok = true;
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt === '') {
                continue;
            }
            if (!db_query($stmt, 'Common table install failed: ' . substr($stmt, 0, 80))) {
                $ok = false;
            }
        }
        return $ok;
    }

    private function ensure_composer_dependencies() {
        $module_dir = dirname(__FILE__);
        $autoload_path = $module_dir . '/vendor/autoload.php';

        if (file_exists($autoload_path)) {
            return;
        }

        $composer_path = $module_dir . '/composer.json';
        if (!file_exists($composer_path)) {
            return;
        }

        chdir($module_dir);
        $output = array();
        $return_code = 0;
        exec('composer install --no-interaction --prefer-dist 2>&1', $output, $return_code);
        if ($return_code !== 0) {
            error_log('KSF Module: composer install failed: ' . implode("\n", $output));
        }
    }
}
