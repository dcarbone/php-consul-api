<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentChecksResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentChecksResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new AgentChecksResponse();
        self::assertSame([], $r->getValue());
        self::assertSame([], $r->Checks);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $obj = new \stdClass();
        $obj->Node = 'n1';
        $obj->CheckID = 'chk1';
        $obj->Name = 'test';
        $obj->Status = 'passing';
        $obj->Notes = '';
        $obj->Output = '';
        $obj->ServiceID = '';
        $obj->ServiceName = '';
        $obj->Type = '';
        $obj->Definition = new \stdClass();

        $r = new AgentChecksResponse();
        $r->unmarshalValue(['chk1' => $obj]);

        $checks = $r->getValue();
        self::assertCount(1, $checks);
        self::assertArrayHasKey('chk1', $checks);
        self::assertInstanceOf(AgentCheck::class, $checks['chk1']);
        self::assertSame('n1', $checks['chk1']->getNode());
    }

    public function testOffsetAccess(): void
    {
        $r = new AgentChecksResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertSame([], $r[0]);
        self::assertNull($r[1]);
    }

    public function testUnmarshalValueScalarKeepsDefaultArray(): void
    {
        $r = new AgentChecksResponse();
        $r->unmarshalValue('nope');

        self::assertSame([], $r->Checks);
        self::assertSame([], $r->getValue());
    }
}
