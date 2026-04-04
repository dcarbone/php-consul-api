<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IntentionHTTPHeaderPermissionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $h = new IntentionHTTPHeaderPermission();
        self::assertSame('', $h->getName());
        self::assertFalse($h->isPresent());
        self::assertSame('', $h->getExact());
        self::assertSame('', $h->getPrefix());
        self::assertSame('', $h->getSuffix());
        self::assertSame('', $h->getRegex());
        self::assertFalse($h->isInvert());
    }

    public function testConstructorWithParams(): void
    {
        $h = new IntentionHTTPHeaderPermission(
            Name: 'x-service',
            Present: true,
            Exact: 'web',
            Prefix: 'pre',
            Suffix: 'suf',
            Regex: '.*',
            Invert: true,
        );
        self::assertSame('x-service', $h->getName());
        self::assertTrue($h->isPresent());
        self::assertSame('web', $h->getExact());
        self::assertSame('pre', $h->getPrefix());
        self::assertSame('suf', $h->getSuffix());
        self::assertSame('.*', $h->getRegex());
        self::assertTrue($h->isInvert());
    }

    public function testFluentSetters(): void
    {
        $h = new IntentionHTTPHeaderPermission();
        $result = $h->setName('x-test')
            ->setPresent(true)
            ->setExact('val')
            ->setPrefix('p')
            ->setSuffix('s')
            ->setRegex('r')
            ->setInvert(true);
        self::assertSame($h, $result);
        self::assertSame('x-test', $h->getName());
        self::assertTrue($h->isPresent());
        self::assertSame('val', $h->getExact());
    }

}
