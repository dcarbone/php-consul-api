<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentToken;
use DCarbone\PHPConsulAPI\Agent\AgentWeights;
use DCarbone\PHPConsulAPI\Agent\MemberOpts;
use DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SimpleAgentTypesTest extends TestCase
{
    // --- AgentToken ---

    public function testAgentTokenDefaults(): void
    {
        $t = new AgentToken();
        self::assertSame('', $t->getToken());
    }

    public function testAgentTokenWithParams(): void
    {
        $t = new AgentToken(Token: 'tok-123');
        self::assertSame('tok-123', $t->getToken());
    }

    public function testAgentTokenFluentSetter(): void
    {
        $t = new AgentToken();
        $result = $t->setToken('abc');
        self::assertSame($t, $result);
        self::assertSame('abc', $t->getToken());
    }

    public function testAgentTokenJsonRoundTrip(): void
    {
        $t = new AgentToken(Token: 'tok');
        $out = $t->jsonSerialize();
        self::assertSame('tok', $out->Token);
        $d = new \stdClass();
        $d->Token = 'tok2';
        $t2 = AgentToken::jsonUnserialize($d);
        self::assertSame('tok2', $t2->getToken());
    }

    // --- AgentWeights ---

    public function testAgentWeightsDefaults(): void
    {
        $w = new AgentWeights();
        self::assertSame(0, $w->getPassing());
        self::assertSame(0, $w->getWarning());
    }

    public function testAgentWeightsWithParams(): void
    {
        $w = new AgentWeights(Passing: 10, Warning: 1);
        self::assertSame(10, $w->getPassing());
        self::assertSame(1, $w->getWarning());
    }

    public function testAgentWeightsJsonRoundTrip(): void
    {
        $w = new AgentWeights(Passing: 5, Warning: 2);
        $out = $w->jsonSerialize();
        self::assertSame(5, $out->Passing);
        self::assertSame(2, $out->Warning);
        $d = new \stdClass();
        $d->Passing = 3;
        $d->Warning = 1;
        $w2 = AgentWeights::jsonUnserialize($d);
        self::assertSame(3, $w2->getPassing());
    }

    // --- MemberOpts ---

    public function testMemberOptsDefaults(): void
    {
        $m = new MemberOpts();
        self::assertFalse($m->isWAN());
        self::assertSame('', $m->getSegment());
        self::assertSame('', $m->getFilter());
    }

    public function testMemberOptsWithParams(): void
    {
        $m = new MemberOpts(WAN: true, Segment: 'seg1', Filter: 'Status == "alive"');
        self::assertTrue($m->isWAN());
        self::assertSame('seg1', $m->getSegment());
    }

    public function testMemberOptsFluentSetters(): void
    {
        $m = new MemberOpts();
        $result = $m->setWAN(true)->setSegment('s')->setFilter('f');
        self::assertSame($m, $result);
        self::assertTrue($m->isWAN());
    }

    // --- ServiceRegisterOpts ---

    public function testServiceRegisterOptsDefaults(): void
    {
        $o = new ServiceRegisterOpts();
        self::assertFalse($o->isReplaceExistingChecks());
        self::assertSame('', $o->getToken());
    }

    public function testServiceRegisterOptsWithParams(): void
    {
        $o = new ServiceRegisterOpts(ReplaceExistingChecks: true, Token: 'tok');
        self::assertTrue($o->isReplaceExistingChecks());
        self::assertSame('tok', $o->getToken());
    }

    public function testServiceRegisterOptsFluentSetters(): void
    {
        $o = new ServiceRegisterOpts();
        $result = $o->setReplaceExistingChecks(true)->setToken('t');
        self::assertSame($o, $result);
        self::assertTrue($o->isReplaceExistingChecks());
    }

    public function testServiceRegisterOptsJsonRoundTrip(): void
    {
        $o = new ServiceRegisterOpts(ReplaceExistingChecks: true, Token: 'tok');
        $out = $o->jsonSerialize();
        self::assertTrue($out->ReplaceExistingChecks);
        self::assertSame('tok', $out->Token);
        $d = new \stdClass();
        $d->ReplaceExistingChecks = false;
        $d->Token = 't2';
        $o2 = ServiceRegisterOpts::jsonUnserialize($d);
        self::assertFalse($o2->isReplaceExistingChecks());
    }
}

