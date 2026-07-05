<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentToken;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentTokenTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new AgentToken();
        self::assertSame('', $t->getToken());
        self::assertSame('', $t->Token);
    }

    public function testConstructorWithParams(): void
    {
        $t = new AgentToken(Token: 'tok-123');
        self::assertSame('tok-123', $t->getToken());
        self::assertSame('tok-123', $t->Token);
    }

    public function testFluentSetter(): void
    {
        $t = new AgentToken();
        $result = $t->setToken('abc');
        self::assertSame($t, $result);
        self::assertSame('abc', $t->getToken());
        self::assertSame('abc', $t->Token);
    }

    public function testJsonSerialize(): void
    {
        $t = new AgentToken(Token: 'tok');
        $out = $t->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('tok', $out->Token);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Token = 'tok2';
        $t = AgentToken::jsonUnserialize($d);
        self::assertInstanceOf(AgentToken::class, $t);
        self::assertSame('tok2', $t->getToken());
    }
}

