<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLTokenListEntry;
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
        self::assertSame('sec', $e->getSecretID());
        self::assertTrue($e->isLocal());
    }

    public function testJsonSerialize(): void
    {
        $e = new ACLTokenListEntry(AccessorID: 'a', SecretID: 's');
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('a', $out->AccessorID);
    }
}

