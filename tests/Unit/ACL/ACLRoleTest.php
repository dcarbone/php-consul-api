<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLRole;
use DCarbone\PHPConsulAPI\ACL\ACLRolePolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLRoleTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new ACLRole();
        self::assertSame('', $r->getID());
        self::assertSame('', $r->getName());
        self::assertSame('', $r->getDescription());
        self::assertSame([], $r->getPolicies());
        self::assertSame([], $r->getServiceIdentities());
        self::assertSame([], $r->getNodeIdentities());
        self::assertSame([], $r->getTemplatedPolicies());
        self::assertSame('', $r->getHash());
        self::assertSame(0, $r->getCreateIndex());
        self::assertSame(0, $r->getModifyIndex());
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $pol = new ACLRolePolicyLink(ID: 'p1', Name: 'pol');
        $si = new ACLServiceIdentity(ServiceName: 'web');
        $ni = new ACLNodeIdentity(NodeName: 'node');
        $tp = new ACLTemplatedPolicy(TemplateName: 'tmpl');
        $r = new ACLRole(
            ID: 'role-1',
            Name: 'my-role',
            Description: 'desc',
            Policies: [$pol],
            ServiceIdentities: [$si],
            NodeIdentities: [$ni],
            TemplatedPolicies: [$tp],
            Hash: 'h',
            CreateIndex: 1,
            ModifyIndex: 2,
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame('role-1', $r->getID());
        self::assertCount(1, $r->getPolicies());
        self::assertCount(1, $r->getServiceIdentities());
        self::assertCount(1, $r->getNodeIdentities());
        self::assertCount(1, $r->getTemplatedPolicies());
    }

    public function testFluentSetters(): void
    {
        $r = new ACLRole();
        $pol = new ACLRolePolicyLink(ID: 'p1');
        $result = $r->setID('a')->setName('b')->setDescription('c')
            ->setPolicies($pol)->setServiceIdentities()
            ->setNodeIdentities()->setTemplatedPolicies()
            ->setHash('h')->setCreateIndex(1)->setModifyIndex(2)
            ->setNamespace('ns')->setPartition('pt');
        self::assertSame($r, $result);
        self::assertCount(1, $r->getPolicies());
    }

    public function testJsonSerialize(): void
    {
        $r = new ACLRole(ID: 'x', Name: 'y');
        $out = $r->jsonSerialize();
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
        self::assertObjectNotHasProperty('Policies', $out);
        self::assertObjectNotHasProperty('Namespace', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id-1';
        $d->Name = 'role';
        $d->Description = 'desc';
        $polLink = new \stdClass();
        $polLink->ID = 'p1';
        $polLink->Name = 'pol';
        $d->Policies = [$polLink];
        $siObj = new \stdClass();
        $siObj->ServiceName = 'web';
        $siObj->Datacenters = ['dc1'];
        $d->ServiceIdentities = [$siObj];
        $d->NodeIdentities = [];
        $d->TemplatedPolicies = [];
        $d->Hash = 'h';
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $r = ACLRole::jsonUnserialize($d);
        self::assertSame('id-1', $r->getID());
        self::assertCount(1, $r->getPolicies());
        self::assertInstanceOf(ACLRolePolicyLink::class, $r->getPolicies()[0]);
        self::assertCount(1, $r->getServiceIdentities());
        self::assertInstanceOf(ACLServiceIdentity::class, $r->getServiceIdentities()[0]);
    }
}

