<?php
/**
 * FA_OrgChart Module Hooks for FrontAccounting
 */

define('SS_ORGCHART', 129 << 8);

class hooks_ksf_FA_OrgChart extends hooks {

    private function ensure_composer_dependencies() {
        $module_dir = dirname(__FILE__);
        $autoload_path = $module_dir . '/vendor/autoload.php';
        
        if (!file_exists($autoload_path)) {
            $composer_path = $module_dir . '/composer.json';
            if (file_exists($composer_path)) {
                chdir($module_dir);
                $output = [];
                $return_code = 0;
                exec('composer install --no-interaction --prefer-dist 2>&1', $output, $return_code);
                if ($return_code !== 0) {
                    error_log('KSF Module: composer install failed: ' . implode("\n", $output));
                }
            }
        }
    }

    function install_options($app) {
        global $path_to_root;

        switch($app->id) {
            case 'HR':
                $app->add_lapp_function(0, _("Organization Chart"),
                    $path_to_root."/modules/".$this->module_name."/orgchart.php", 'SA_ORGCHARTVIEW', MENU_MAIN);
                $app->add_lapp_function(1, _("Positions"),
                    $path_to_root."/modules/".$this->module_name."/positions.php", 'SA_ORGCHARTEDIT', MENU_ENTRY);
                break;
        }
    }

    function install_access() {
        $security_sections[SS_ORGCHART] = _("Organization Chart");
        $security_areas['SA_ORGCHARTVIEW'] = array(SS_ORGCHART | 1, _("View Org Chart"));
        $security_areas['SA_ORGCHARTEDIT'] = array(SS_ORGCHART | 2, _("Edit Positions"));
        return array($security_areas, $security_sections);
    }

    function install_extension($check_only=true) {
        return true;
    }

    function install_tabs($app) {
    }

    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        $ok = $this->update_databases($company, $updates, $check_only);
        if ($check_only || !$ok) {
            return $ok;
        }
        $this->ensure_orgchart_schema();
        return $ok;
    }

    private function table_exists($table) {
        $sql = "SHOW TABLES LIKE " . db_escape($table);
        $res = db_query($sql, 'Failed checking table existence');
        return db_num_rows($res) > 0;
    }

    private function ensure_orgchart_schema() {
        $tables = array(
            TB_PREF . "fa_org_positions" => "
                CREATE TABLE IF NOT EXISTS `" . TB_PREF . "fa_org_positions` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `position_title` VARCHAR(100) NOT NULL,
                    `department` VARCHAR(50) DEFAULT NULL,
                    `parent_position_id` INT(11) DEFAULT NULL,
                    `employee_id` VARCHAR(100) DEFAULT NULL,
                    `job_description_id` INT(11) DEFAULT NULL,
                    `is_active` TINYINT(1) DEFAULT 1,
                    `sort_order` INT(11) DEFAULT 0,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `idx_parent` (`parent_position_id`),
                    KEY `idx_employee` (`employee_id`),
                    KEY `idx_department` (`department`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        foreach ($tables as $table_name => $sql) {
            db_query($sql, "Could not create OrgChart table: $table_name");
        }
    }

    function db_prevoid($trans_type, $trans_no) {
        // Handle voiding if needed
    }
}
?>
