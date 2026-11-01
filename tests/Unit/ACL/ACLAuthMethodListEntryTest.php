<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use Brick\Math\BigInteger;
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
        self::assertSame('0', (string)$e->getCreateIndex());
        self::assertSame('0', (string)$e->getModifyIndex());
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
        self::assertSame('5', (string)$e->getCreateIndex());
    }

    public function testUint64Fields(): void
    {
        $max = '18446744073709551615';
        $e = new ACLAuthMethodListEntry(CreateIndex: 1, ModifyIndex: '2');
        self::assertSame('1', (string)$e->CreateIndex);
        self::assertSame('2', (string)$e->getModifyIndex());
        $e = new ACLAuthMethodListEntry(CreateIndex: BigInteger::of($max), ModifyIndex: BigInteger::of(3));
        self::assertSame($max, (string)$e->getCreateIndex());
        self::assertSame('3', (string)$e->ModifyIndex);

        $e->setCreateIndex(4)->setModifyIndex('5');
        self::assertSame('4', (string)$e->getCreateIndex());
        self::assertSame('5', (string)$e->getModifyIndex());
        $e->setCreateIndex(BigInteger::of(6))->setModifyIndex($max);
        self::assertSame('6', (string)$e->CreateIndex);
        self::assertSame($max, (string)$e->ModifyIndex);

        $d = new \stdClass();
        $d->CreateIndex = $max;
        $d->ModifyIndex = 7;
        $e = ACLAuthMethodListEntry::jsonUnserialize($d);
        self::assertSame($max, (string)$e->getCreateIndex());
        self::assertSame('7', (string)$e->getModifyIndex());

        $this->expectException(\InvalidArgumentException::class);
        $e->setCreateIndex(-1);
    }
}

