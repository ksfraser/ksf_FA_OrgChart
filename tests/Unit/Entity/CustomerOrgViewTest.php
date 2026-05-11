<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\FA\OrgChart\Entity;

use Ksfraser\FA\OrgChart\Entity\CustomerOrgView;
use PHPUnit\Framework\TestCase;

class CustomerOrgViewTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $view = new CustomerOrgView();

        $this->assertSame(0, $view->getDebtorNo());
        $this->assertNull($view->getSalesmanId());
        $this->assertNull($view->getWarrantyRepId());
        $this->assertNull($view->getProjectId());
        $this->assertSame([], $view->getCards());
        $this->assertSame('crm', $view->getContext());
    }

    public function testSetDebtorNo(): void
    {
        $view = new CustomerOrgView();
        $result = $view->setDebtorNo(123);

        $this->assertInstanceOf(CustomerOrgView::class, $result);
        $this->assertSame(123, $view->getDebtorNo());
    }

    public function testSetSalesmanId(): void
    {
        $view = new CustomerOrgView();
        $result = $view->setSalesmanId(456);

        $this->assertInstanceOf(CustomerOrgView::class, $result);
        $this->assertSame(456, $view->getSalesmanId());
    }

    public function testSetProjectId(): void
    {
        $view = new CustomerOrgView();
        $result = $view->setProjectId(789);

        $this->assertInstanceOf(CustomerOrgView::class, $result);
        $this->assertSame(789, $view->getProjectId());
    }

    public function testSetContext(): void
    {
        $view = new CustomerOrgView();
        $result = $view->setContext('project');

        $this->assertInstanceOf(CustomerOrgView::class, $result);
        $this->assertSame('project', $view->getContext());
    }

    public function testToArray(): void
    {
        $view = new CustomerOrgView();
        $view->setDebtorNo(1)
             ->setSalesmanId(10)
             ->setWarrantyRepId(20)
             ->setProjectId(30)
             ->setContext('crm');

        $array = $view->toArray();

        $this->assertArrayHasKey('debtorNo', $array);
        $this->assertArrayHasKey('salesmanId', $array);
        $this->assertArrayHasKey('warrantyRepId', $array);
        $this->assertArrayHasKey('projectId', $array);
        $this->assertArrayHasKey('cards', $array);
        $this->assertArrayHasKey('context', $array);
    }

    public function testFromArray(): void
    {
        $data = [
            'debtorNo' => 10,
            'salesmanId' => 100,
            'warrantyRepId' => 200,
            'projectId' => 300,
            'cards' => [['id' => 1]],
            'context' => 'project',
        ];

        $view = CustomerOrgView::fromArray($data);

        $this->assertSame(10, $view->getDebtorNo());
        $this->assertSame(100, $view->getSalesmanId());
        $this->assertSame(200, $view->getWarrantyRepId());
        $this->assertSame(300, $view->getProjectId());
        $this->assertCount(1, $view->getCards());
        $this->assertSame('project', $view->getContext());
    }
}