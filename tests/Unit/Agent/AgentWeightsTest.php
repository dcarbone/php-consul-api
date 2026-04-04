<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentWeights;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentWeightsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $w = new AgentWeights();
        self::assertSame(0, $w->getPassing());
        self::assertSame(0, $w->Passing);
        self::assertSame(0, $w->getWarning());
        self::assertSame(0, $w->Warning);
    }

    public function testConstructorWithParams(): void
    {
        $w = new AgentWeights(Passing: 10, Warning: 1);
        self::assertSame(10, $w->getPassing());
        self::assertSame(10, $w->Passing);
        self::assertSame(1, $w->getWarning());
        self::assertSame(1, $w->Warning);
    }

    public function testFluentSetters(): void
    {
        $w = new AgentWeights();
        $result = $w->setPassing(5)->setWarning(2);
        self::assertSame($w, $result);
        self::assertSame(5, $w->getPassing());
        self::assertSame(5, $w->Passing);
        self::assertSame(2, $w->getWarning());
        self::assertSame(2, $w->Warning);
    }

    public function testJsonSerialize(): void
    {
        $w = new AgentWeights(Passing: 5, Warning: 2);
        $out = $w->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(5, $out->Passing);
        self::assertSame(2, $out->Warning);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Passing = 3;
        $d->Warning = 1;
        $w = AgentWeights::jsonUnserialize($d);
        self::assertInstanceOf(AgentWeights::class, $w);
        self::assertSame(3, $w->getPassing());
        self::assertSame(1, $w->getWarning());
    }
}

