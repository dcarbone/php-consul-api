<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLTokenListEntry;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTokenListEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ACLTokenListEntry();
        self::assertSame('', $e->getAccessorID());
        self::assertSame('', $e->getSecretID());
        self::assertSame('', $e->getDescription());
        self::assertSame([], $e->getPolicies());
        self::assertSame([], $e->getRoles());
        self::assertSame([], $e->getServiceIdentities());
        self::assertSame([], $e->getNodeIdentities());
        self::assertSame([], $e->getTemplatedPolicies());
        self::assertFalse($e->isLocal());
        self::assertSame(0, $e->getCreateIndex());
        self::assertSame(0, $e->getModifyIndex());
        self::assertFalse($e->isLegacy());
        self::assertSame('', $e->getNamespace());
        self::assertSame('', $e->getPartition());
        self::assertSame('', $e->getAuthMethodNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $e = new ACLTokenListEntry(
            AccessorID: 'acc',
            SecretID: 'sec',
            Description: 'desc',
            Local: true,
        );
        self::assertSame('acc', $e->getAccessorID());
        self::assertSame('acc', $e->AccessorID);
        self::assertSame('sec', $e->getSecretID());
        self::assertSame('sec', $e->SecretID);
        self::assertTrue($e->isLocal());
        self::assertTrue($e->Local);
    }

    public function testFluentSetters(): void
    {
        $e = new ACLTokenListEntry();
        $pol = new ACLTokenPolicyLink(ID: 'p1', Name: 'pol');
        $role = new ACLTokenRoleLink(ID: 'r1', Name: 'role');
        $si = new ACLServiceIdentity(ServiceName: 'web');
        $ni = new ACLNodeIdentity(NodeName: 'node');
        $tp = new ACLTemplatedPolicy(TemplateName: 'tmpl');

        $result = $e->setCreateIndex(10)
            ->setModifyIndex(20)
            ->setAccessorID('acc-set')
            ->setSecretID('sec-set')
            ->setDescription('desc-set')
            ->setPolicies($pol)
            ->setRoles($role)
            ->setServiceIdentities($si)
            ->setNodeIdentities($ni)
            ->setTemplatedPolicies($tp)
            ->setLocal(true)
            ->setAuthMethod('auth-set')
            ->setHash('hash-set')
            ->setLegacy(true)
            ->setNamespace('ns-set')
            ->setPartition('pt-set')
            ->setAuthMethodNamespace('amns-set');

        self::assertSame($e, $result);
        self::assertSame(10, $e->getCreateIndex());
        self::assertSame(10, $e->CreateIndex);
        self::assertSame(20, $e->getModifyIndex());
        self::assertSame('acc-set', $e->getAccessorID());
        self::assertSame('acc-set', $e->AccessorID);
        self::assertSame('sec-set', $e->getSecretID());
        self::assertSame('desc-set', $e->getDescription());
        self::assertCount(1, $e->getPolicies());
        self::assertCount(1, $e->getRoles());
        self::assertCount(1, $e->getServiceIdentities());
        self::assertCount(1, $e->getNodeIdentities());
        self::assertCount(1, $e->getTemplatedPolicies());
        self::assertTrue($e->isLocal());
        self::assertSame('auth-set', $e->getAuthMethod());
        self::assertSame('hash-set', $e->getHash());
        self::assertTrue($e->isLegacy());
        self::assertSame('ns-set', $e->getNamespace());
        self::assertSame('pt-set', $e->getPartition());
        self::assertSame('amns-set', $e->getAuthMethodNamespace());
    }

    public function testJsonSerialize(): void
    {
        $e = new ACLTokenListEntry(AccessorID: 'a', SecretID: 's');
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('a', $out->AccessorID);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $d->AccessorID = 'acc-1';
        $d->SecretID = 'sec-1';
        $d->Description = 'desc';
        $d->Local = true;
        $d->Hash = 'h';
        $polLink = new \stdClass();
        $polLink->ID = 'p1';
        $polLink->Name = 'policy';
        $d->Policies = [$polLink];
        $d->Roles = [];
        $d->ServiceIdentities = [];
        $d->NodeIdentities = [];
        $d->TemplatedPolicies = [];
        $e = ACLTokenListEntry::jsonUnserialize($d);
        self::assertSame('acc-1', $e->getAccessorID());
        self::assertSame('sec-1', $e->getSecretID());
        self::assertTrue($e->isLocal());
        self::assertCount(1, $e->getPolicies());
        self::assertInstanceOf(ACLTokenPolicyLink::class, $e->getPolicies()[0]);
    }
}

