<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\FAOrgChart;

use PHPUnit\Framework\TestCase;

class ModuleStructureTest extends TestCase
{
    private string $moduleDir;
    
    protected function setUp(): void
    {
        $this->moduleDir = dirname(__DIR__, 2);
    }
    
    public function testIncludesDirectoryExists(): void
    {
        $this->assertDirectoryExists($this->moduleDir . '/includes');
    }
    
    public function testOrgchartDbIncExists(): void
    {
        $this->assertFileExists($this->moduleDir . '/includes/orgchart_db.inc');
    }
    
    public function testOrgchartDbContainsFunctions(): void
    {
        $content = file_get_contents($this->moduleDir . '/includes/orgchart_db.inc');
        $this->assertNotEmpty($content);
    }
    
    public function testProjectDcsExists(): void
    {
        $this->assertDirectoryExists($this->moduleDir . '/ProjectDcs');
    }
}
