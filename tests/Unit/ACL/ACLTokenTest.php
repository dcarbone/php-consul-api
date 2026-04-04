<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLToken;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy;
use DCarbone\Go\Time;
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
}

