<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceRegisterOptsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $o = new ServiceRegisterOpts();
        self::assertFalse($o->isReplaceExistingChecks());
        self::assertFalse($o->ReplaceExistingChecks);
        self::assertSame('', $o->getToken());
        self::assertSame('', $o->Token);
    }

    public function testConstructorWithParams(): void
    {
        $o = new ServiceRegisterOpts(ReplaceExistingChecks: true, Token: 'tok');
        self::assertTrue($o->isReplaceExistingChecks());
        self::assertTrue($o->ReplaceExistingChecks);
        self::assertSame('tok', $o->getToken());
        self::assertSame('tok', $o->Token);
    }

    public function testFluentSetters(): void
    {
        $o = new ServiceRegisterOpts();
        $result = $o->setReplaceExistingChecks(true)->setToken('t');
        self::assertSame($o, $result);
        self::assertTrue($o->isReplaceExistingChecks());
        self::assertSame('t', $o->getToken());
    }

    public function testJsonSerialize(): void
    {
        $o = new ServiceRegisterOpts(ReplaceExistingChecks: true, Token: 'tok');
        $out = $o->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertTrue($out->ReplaceExistingChecks);
        self::assertSame('tok', $out->Token);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ReplaceExistingChecks = false;
        $d->Token = 't2';
        $o = ServiceRegisterOpts::jsonUnserialize($d);
        self::assertInstanceOf(ServiceRegisterOpts::class, $o);
        self::assertFalse($o->isReplaceExistingChecks());
        self::assertSame('t2', $o->getToken());
    }
}

