<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodListEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLAuthMethodListEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ACLAuthMethodListEntry();
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getType());
        self::assertSame('', $e->getDisplayName());
        self::assertSame('', $e->getDescription());
        self::assertSame(0, $e->getMaxTokenTTL()->Nanoseconds());
        self::assertSame('', $e->getTokenLocality());
        self::assertSame(0, $e->getCreateIndex());
        self::assertSame(0, $e->getModifyIndex());
        self::assertSame('', $e->getNamespace());
    }

    public function testConstructorWithParams(): void
    {
        $e = new ACLAuthMethodListEntry(Name: 'auth', Type: 'jwt', DisplayName: 'JWT Auth');
        self::assertSame('auth', $e->getName());
        self::assertSame('jwt', $e->getType());
        self::assertSame('JWT Auth', $e->getDisplayName());
    }

    public function testJsonSerialize(): void
    {
        $e = new ACLAuthMethodListEntry(Name: 'auth', Type: 'jwt');
        $out = $e->jsonSerialize();
        self::assertSame('auth', $out->Name);
        self::assertSame('jwt', $out->Type);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'auth';
        $d->Type = 'kubernetes';
        $d->DisplayName = 'K8s';
        $d->Description = '';
        $d->MaxTokenTTL = '10m0s';
        $d->TokenLocality = 'local';
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $e = ACLAuthMethodListEntry::jsonUnserialize($d);
        self::assertSame('auth', $e->getName());
        self::assertSame('kubernetes', $e->getType());
        self::assertSame(5, $e->getCreateIndex());
    }
}

