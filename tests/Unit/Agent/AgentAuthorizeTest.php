<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentAuthorize;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentAuthorizeTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $a = new AgentAuthorize();
        self::assertFalse($a->isAuthorized());
        self::assertSame('', $a->getReason());
    }

    public function testConstructorWithParams(): void
    {
        $a = new AgentAuthorize(Authorized: true, Reason: 'ACL allowed');
        self::assertTrue($a->isAuthorized());
        self::assertTrue($a->Authorized);
        self::assertSame('ACL allowed', $a->getReason());
    }

    public function testFluentSetters(): void
    {
        $a = new AgentAuthorize();
        $result = $a->setAuthorized(true)->setReason('test');
        self::assertSame($a, $result);
        self::assertTrue($a->isAuthorized());
        self::assertSame('test', $a->getReason());
    }

    public function testJsonSerialize(): void
    {
        $a = new AgentAuthorize(Authorized: true, Reason: 'ok');
        $out = $a->jsonSerialize();
        self::assertTrue($out->Authorized);
        self::assertSame('ok', $out->Reason);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Authorized = false;
        $d->Reason = 'denied';
        $a = AgentAuthorize::jsonUnserialize($d);
        self::assertFalse($a->isAuthorized());
        self::assertSame('denied', $a->getReason());
    }
}

