<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLToken;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTokenTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new ACLToken();
        self::assertSame(0, $t->getCreateIndex());
        self::assertSame(0, $t->getModifyIndex());
        self::assertSame('', $t->getAccessorID());
        self::assertSame('', $t->getSecretID());
        self::assertSame('', $t->getDescription());
        self::assertSame([], $t->getPolicies());
        self::assertSame([], $t->getRoles());
        self::assertSame([], $t->getServiceIdentities());
        self::assertSame([], $t->getNodeIdentities());
        self::assertSame([], $t->getTemplatePolicies());
        self::assertFalse($t->isLocal());
        self::assertSame('', $t->getAuthMethod());
        self::assertSame('', $t->getHash());
        self::assertSame('', $t->getNamespace());
        self::assertSame('', $t->getRules());
        self::assertSame('', $t->getPartition());
        self::assertSame('', $t->getAuthMethodNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $pol = new ACLTokenPolicyLink(ID: 'p1', Name: 'pol');
        $role = new ACLTokenRoleLink(ID: 'r1', Name: 'role');
        $t = new ACLToken(
            CreateIndex: 1,
            ModifyIndex: 2,
            AccessorID: 'accessor-1',
            SecretID: 'secret-1',
            Description: 'desc',
            Policies: [$pol],
            Roles: [$role],
            Local: true,
            AuthMethod: 'auth',
            Hash: 'h',
            Namespace: 'ns',
            Rules: 'r',
            Partition: 'pt',
            AuthMethodNamespace: 'amns',
        );
        self::assertSame(1, $t->getCreateIndex());
        self::assertSame('accessor-1', $t->getAccessorID());
        self::assertSame('secret-1', $t->getSecretID());
        self::assertCount(1, $t->getPolicies());
        self::assertCount(1, $t->getRoles());
        self::assertTrue($t->isLocal());
    }

    public function testJsonSerialize(): void
    {
        $t = new ACLToken(AccessorID: 'a', SecretID: 's', Description: 'd');
        $out = $t->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('a', $out->AccessorID);
        self::assertSame('s', $out->SecretID);
        self::assertSame('d', $out->Description);
    }

    public function testJsonSerializeOmitsEmptyCollections(): void
    {
        $t = new ACLToken(AccessorID: 'a');
        $out = $t->jsonSerialize();
        self::assertObjectNotHasProperty('Policies', $out);
        self::assertObjectNotHasProperty('Roles', $out);
        self::assertObjectNotHasProperty('ServiceIdentities', $out);
        self::assertObjectNotHasProperty('NodeIdentities', $out);
        self::assertObjectNotHasProperty('TemplatePolicies', $out);
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
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
        $d->TemplatePolicies = [];
        $t = ACLToken::jsonUnserialize($d);
        self::assertSame('acc-1', $t->getAccessorID());
        self::assertSame('sec-1', $t->getSecretID());
        self::assertTrue($t->isLocal());
        self::assertCount(1, $t->getPolicies());
        self::assertInstanceOf(ACLTokenPolicyLink::class, $t->getPolicies()[0]);
    }

    public function testFluentSetters(): void
    {
        $t = new ACLToken();
        $pol = new ACLTokenPolicyLink(ID: 'p1', Name: 'pol');
        $role = new ACLTokenRoleLink(ID: 'r1', Name: 'role');
        $si = new ACLServiceIdentity(ServiceName: 'web');
        $ni = new ACLNodeIdentity(NodeName: 'node');
        $tp = new ACLTemplatedPolicy(TemplateName: 'tmpl');

        $result = $t->setCreateIndex(10)
            ->setModifyIndex(20)
            ->setAccessorID('acc-set')
            ->setSecretID('sec-set')
            ->setDescription('desc-set')
            ->setPolicies($pol)
            ->setRoles($role)
            ->setServiceIdentities($si)
            ->setNodeIdentities($ni)
            ->setTemplatePolicies($tp)
            ->setLocal(true)
            ->setAuthMethod('auth-set')
            ->setExpirationTTL('5m')
            ->setHash('hash-set')
            ->setNamespace('ns-set')
            ->setRules('rules-set')
            ->setPartition('pt-set')
            ->setAuthMethodNamespace('amns-set');

        self::assertSame($t, $result);
        self::assertSame(10, $t->getCreateIndex());
        self::assertSame(10, $t->CreateIndex);
        self::assertSame(20, $t->getModifyIndex());
        self::assertSame(20, $t->ModifyIndex);
        self::assertSame('acc-set', $t->getAccessorID());
        self::assertSame('acc-set', $t->AccessorID);
        self::assertSame('sec-set', $t->getSecretID());
        self::assertSame('sec-set', $t->SecretID);
        self::assertSame('desc-set', $t->getDescription());
        self::assertSame('desc-set', $t->Description);
        self::assertCount(1, $t->getPolicies());
        self::assertSame($pol, $t->getPolicies()[0]);
        self::assertCount(1, $t->getRoles());
        self::assertSame($role, $t->getRoles()[0]);
        self::assertCount(1, $t->getServiceIdentities());
        self::assertSame($si, $t->getServiceIdentities()[0]);
        self::assertCount(1, $t->getNodeIdentities());
        self::assertSame($ni, $t->getNodeIdentities()[0]);
        self::assertCount(1, $t->getTemplatePolicies());
        self::assertSame($tp, $t->getTemplatePolicies()[0]);
        self::assertTrue($t->isLocal());
        self::assertTrue($t->Local);
        self::assertSame('auth-set', $t->getAuthMethod());
        self::assertSame('auth-set', $t->AuthMethod);
        self::assertSame('hash-set', $t->getHash());
        self::assertSame('hash-set', $t->Hash);
        self::assertSame('ns-set', $t->getNamespace());
        self::assertSame('ns-set', $t->Namespace);
        self::assertSame('rules-set', $t->getRules());
        self::assertSame('rules-set', $t->Rules);
        self::assertSame('pt-set', $t->getPartition());
        self::assertSame('pt-set', $t->Partition);
        self::assertSame('amns-set', $t->getAuthMethodNamespace());
        self::assertSame('amns-set', $t->AuthMethodNamespace);
    }
}

