<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\FA\OrgChart\Presenter;

use Ksfraser\FA\OrgChart\Entity\CustomerOrgView;
use Ksfraser\FA\OrgChart\Entity\TeamMemberCard;
use Ksfraser\FA\OrgChart\Presenter\CustomerOrgPresenter;
use Ksfraser\FA\OrgChart\Service\CustomerOrgService;
use PHPUnit\Framework\TestCase;

class CustomerOrgPresenterTest extends TestCase
{
    private CustomerOrgPresenter $presenter;
    private $mockService;

    protected function setUp(): void
    {
        $this->mockService = $this->createMock(CustomerOrgService::class);
        $this->presenter = new CustomerOrgPresenter($this->mockService);
    }

    public function testPresenterInstantiation(): void
    {
        $this->assertInstanceOf(CustomerOrgPresenter::class, $this->presenter);
    }

    public function testDefaultValues(): void
    {
        $this->assertSame(0, $this->presenter->getCurrentDebtorNo());
        $this->assertNull($this->presenter->getCurrentProjectId());
        $this->assertSame('salesman', $this->presenter->getCurrentContext());
    }

    public function testSetCurrentDebtorNo(): void
    {
        $result = $this->presenter->setCurrentDebtorNo(123);

        $this->assertInstanceOf(CustomerOrgPresenter::class, $result);
        $this->assertSame(123, $this->presenter->getCurrentDebtorNo());
    }

    public function testSetCurrentProjectId(): void
    {
        $result = $this->presenter->setCurrentProjectId(456);

        $this->assertInstanceOf(CustomerOrgPresenter::class, $result);
        $this->assertSame(456, $this->presenter->getCurrentProjectId());
    }

    public function testSetCurrentContext(): void
    {
        $result = $this->presenter->setCurrentContext('warranty');

        $this->assertInstanceOf(CustomerOrgPresenter::class, $result);
        $this->assertSame('warranty', $this->presenter->getCurrentContext());
    }

    public function testInvalidContextThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->presenter->setCurrentContext('invalid');
    }

    public function testValidContextModes(): void
    {
        $validModes = ['salesman', 'warranty', 'project'];

        foreach ($validModes as $mode) {
            $result = $this->presenter->setCurrentContext($mode);
            $this->assertInstanceOf(CustomerOrgPresenter::class, $result);
            $this->assertSame($mode, $this->presenter->getCurrentContext());
        }
    }

    public function testLoadCustomerViewThrowsWithoutDebtorNo(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('DebtorNo required');

        $this->presenter->loadCustomerView();
    }

    public function testLoadCustomerViewLoadsData(): void
    {
        $this->presenter->setCurrentDebtorNo(123);

        $view = new CustomerOrgView();
        $view->setDebtorNo(123);

        $this->mockService->method('loadCustomerOrgData')
            ->willReturn($view);

        $result = $this->presenter->loadCustomerView();

        $this->assertInstanceOf(CustomerOrgView::class, $result);
        $this->assertSame(123, $result->getDebtorNo());
    }

    public function testGetCardCountReturnsZeroForEmpty(): void
    {
        $this->presenter->setCurrentDebtorNo(123);

        $view = new CustomerOrgView();
        $view->setDebtorNo(123);
        $view->setCards([]);

        $this->mockService->method('loadCustomerOrgData')
            ->willReturn($view);

        $count = $this->presenter->getCardCount();

        $this->assertSame(0, $count);
    }

    public function testHasProjectContextReturnsFalseWithoutDebtor(): void
    {
        $result = $this->presenter->hasProjectContext();

        $this->assertFalse($result);
    }

    public function testHasProjectContextDelegatesToService(): void
    {
        $this->presenter->setCurrentDebtorNo(123);

        $this->mockService->method('hasProjectContext')
            ->willReturn(true);

        $result = $this->presenter->hasProjectContext();

        $this->assertTrue($result);
    }

    public function testGetViewArray(): void
    {
        $this->presenter->setCurrentDebtorNo(123);

        $view = new CustomerOrgView();
        $view->setDebtorNo(123);
        $view->setSalesmanId(10);
        $view->setWarrantyRepId(20);
        $view->setCards([]);

        $this->mockService->method('loadCustomerOrgData')
            ->willReturn($view);

        $array = $this->presenter->getViewArray();

        $this->assertArrayHasKey('debtorNo', $array);
        $this->assertArrayHasKey('salesmanId', $array);
        $this->assertArrayHasKey('warrantyRepId', $array);
        $this->assertArrayHasKey('cardCount', $array);
        $this->assertArrayHasKey('cards', $array);
    }
}