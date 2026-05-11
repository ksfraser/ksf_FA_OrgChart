<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\FA\OrgChart\Entity;

use Ksfraser\FA\OrgChart\Entity\TeamMemberCard;
use PHPUnit\Framework\TestCase;

class TeamMemberCardTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $card = new TeamMemberCard();

        $this->assertNull($card->getEmployeeId());
        $this->assertNull($card->getType());
        $this->assertNull($card->getRoleBadge());
        $this->assertSame('', $card->getName());
        $this->assertSame('', $card->getTitle());
        $this->assertSame('', $card->getEmail());
        $this->assertSame('', $card->getPhone());
    }

    public function testSetEmployeeId(): void
    {
        $card = new TeamMemberCard();
        $result = $card->setEmployeeId(123);

        $this->assertInstanceOf(TeamMemberCard::class, $result);
        $this->assertSame(123, $card->getEmployeeId());
    }

    public function testSetTypeSalesman(): void
    {
        $card = new TeamMemberCard();
        $result = $card->setType(TeamMemberCard::TYPE_SALESMAN);

        $this->assertInstanceOf(TeamMemberCard::class, $result);
        $this->assertTrue($card->isSalesman());
        $this->assertFalse($card->isWarrantyRep());
        $this->assertFalse($card->isProjectMember());
    }

    public function testSetTypeWarranty(): void
    {
        $card = new TeamMemberCard();
        $card->setType(TeamMemberCard::TYPE_WARRANTY);

        $this->assertTrue($card->isWarrantyRep());
        $this->assertFalse($card->isSalesman());
        $this->assertFalse($card->isProjectMember());
    }

    public function testSetTypeProject(): void
    {
        $card = new TeamMemberCard();
        $card->setType(TeamMemberCard::TYPE_PROJECT);

        $this->assertTrue($card->isProjectMember());
        $this->assertFalse($card->isSalesman());
        $this->assertFalse($card->isWarrantyRep());
    }

    public function testInvalidTypeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $card = new TeamMemberCard();
        $card->setType('invalid');
    }

    public function testSetNameAndTitle(): void
    {
        $card = new TeamMemberCard();
        $card->setName('John Doe')
             ->setTitle('Project Manager');

        $this->assertSame('John Doe', $card->getName());
        $this->assertSame('Project Manager', $card->getTitle());
    }

    public function testSetRoleBadge(): void
    {
        $card = new TeamMemberCard();
        $result = $card->setRoleBadge('Tech Lead');

        $this->assertInstanceOf(TeamMemberCard::class, $result);
        $this->assertSame('Tech Lead', $card->getRoleBadge());
    }

    public function testToArray(): void
    {
        $card = new TeamMemberCard();
        $card->setEmployeeId(1)
             ->setType(TeamMemberCard::TYPE_SALESMAN)
             ->setName('Jane Smith')
             ->setTitle('Sales Rep')
             ->setEmail('jane@example.com')
             ->setPhone('Ext 101');

        $array = $card->toArray();

        $this->assertSame(1, $array['employeeId']);
        $this->assertSame('salesman', $array['type']);
        $this->assertSame('Jane Smith', $array['name']);
        $this->assertSame('Sales Rep', $array['title']);
        $this->assertSame('jane@example.com', $array['email']);
        $this->assertSame('Ext 101', $array['phone']);
    }

    public function testFromArray(): void
    {
        $data = [
            'employeeId' => 10,
            'type' => 'project',
            'roleBadge' => 'Developer',
            'name' => 'Bob Wilson',
            'title' => 'Developer',
            'email' => 'bob@example.com',
            'phone' => 'Ext 202',
        ];

        $card = TeamMemberCard::fromArray($data);

        $this->assertSame(10, $card->getEmployeeId());
        $this->assertSame('project', $card->getType());
        $this->assertSame('Developer', $card->getRoleBadge());
        $this->assertSame('Bob Wilson', $card->getName());
        $this->assertSame('Developer', $card->getTitle());
        $this->assertSame('bob@example.com', $card->getEmail());
        $this->assertSame('Ext 202', $card->getPhone());
    }
}