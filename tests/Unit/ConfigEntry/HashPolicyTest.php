<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy;
use DCarbone\PHPConsulAPI\ConfigEntry\CookieConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class HashPolicyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $h = new HashPolicy();
        self::assertSame('', $h->getField());
        self::assertSame('', $h->getFieldValue());
        self::assertNull($h->getCookieConfig());
        self::assertFalse($h->isSourceIP());
        self::assertFalse($h->isTerminal());
    }

    public function testConstructorWithParams(): void
    {
        $cookie = new CookieConfig(Session: true, Path: '/');
        $h = new HashPolicy(
            Field: 'header',
            FieldValue: 'x-user',
            CookieConfig: $cookie,
            SourceIP: true,
            Terminal: true,
        );
        self::assertSame('header', $h->getField());
        self::assertSame('x-user', $h->getFieldValue());
        self::assertSame($cookie, $h->getCookieConfig());
        self::assertTrue($h->isSourceIP());
        self::assertTrue($h->isTerminal());
    }

    public function testFluentSetters(): void
    {
        $cookie = new CookieConfig();
        $h = new HashPolicy();
        $result = $h->setField('cookie')
            ->setFieldValue('session')
            ->setCookieConfig($cookie)
            ->setSourceIP(true)
            ->setTerminal(true);
        self::assertSame($h, $result);
        self::assertSame('cookie', $h->getField());
        self::assertSame('session', $h->getFieldValue());
        self::assertSame($cookie, $h->getCookieConfig());
        self::assertTrue($h->isSourceIP());
        self::assertTrue($h->isTerminal());
    }

}
