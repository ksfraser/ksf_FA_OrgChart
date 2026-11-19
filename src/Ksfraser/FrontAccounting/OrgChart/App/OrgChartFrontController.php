<?php
declare(strict_types=1);

namespace Ksfraser\FrontAccounting\OrgChart\App;

use ksfraser\FrontAccounting\Common\App\AbstractFrontController;

/**
 * OrgChartFrontController — single front controller.
 *
 * @package ksf_FA_OrgChart
 * @since   1.0.0
 */
class OrgChartFrontController extends AbstractFrontController
{
    public function __construct(
        OrgChartAppShell $appShell,
        string $fallbackSecurityArea,
        string $path_to_root
    ) {
        parent::__construct($appShell, $fallbackSecurityArea, $path_to_root);
    }
}
