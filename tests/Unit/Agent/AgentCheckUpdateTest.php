<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentCheckUpdate;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentCheckUpdateTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $u = new AgentCheckUpdate();
        self::assertSame('', $u->getStatus());
        self::assertSame('', $u->getOutput());
    }

    public function testConstructorWithParams(): void
    {
        $u = new AgentCheckUpdate(Status: 'passing', Output: 'ok');
        self::assertSame('passing', $u->getStatus());
        self::assertSame('ok', $u->getOutput());
    }

    public function testFluentSetters(): void
    {
        $u = new AgentCheckUpdate();
        $result = $u->setStatus('critical')->setOutput('err');
        self::assertSame($u, $result);
        self::assertSame('critical', $u->getStatus());
        self::assertSame('err', $u->getOutput());
    }

    public function testJsonSerialize(): void
    {
        $u = new AgentCheckUpdate(Status: 's', Output: 'o');
        $out = $u->jsonSerialize();
        self::assertSame('s', $out->Status);
        self::assertSame('o', $out->Output);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Status = 'passing';
        $d->Output = 'ok';
        $u = AgentCheckUpdate::jsonUnserialize($d);
        self::assertSame('passing', $u->getStatus());
        self::assertSame('ok', $u->getOutput());
    }

    public function testToString(): void
    {
        $u = new AgentCheckUpdate(Status: 'passing', Output: 'ok');
        self::assertSame('passing: ok', (string)$u);
    }
}

