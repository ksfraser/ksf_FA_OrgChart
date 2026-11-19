<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\OrgChart\Controller\PositionController;
use Ksfraser\FrontAccounting\OrgChart\Controller\DepartmentController;
use Ksfraser\FrontAccounting\OrgChart\Controller\TeamController;
use Ksfraser\FrontAccounting\OrgChart\Controller\RelationshipController;
use Ksfraser\FrontAccounting\OrgChart\Controller\OrgChartController;

/**
 * OrgChartAppShell — application shell SRP.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class OrgChartAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('orgchart', 'index.php', 'view', 'positions');

        $this->add_module(_("Org Chart"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_OrgChart/index.php',
            'view',
            ''
        );

        $menu->addItem('positions', _("Positions"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartVIEW');
        $menu->addItem('departments', _("Departments"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartVIEW');
        $menu->addItem('teams', _("Teams"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartVIEW');
        $menu->addItem('relationships', _("Relationships"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartVIEW');
        $menu->addItem('org_chart', _("Org Chart"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartVIEW');
        $menu->addItem('org_chart_config', _("Org Chart Config"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_ksf_FA_OrgChartMANAGE');

        $this->add_extensions();
    }
}
