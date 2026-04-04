<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\LeastRequestConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class LeastRequestConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new LeastRequestConfig();
        self::assertSame(0, $c->getChoiceCount());
    }

    public function testConstructorWithParams(): void
    {
        $c = new LeastRequestConfig(ChoiceCount: 5);
        self::assertSame(5, $c->getChoiceCount());
    }

    public function testFluentSetters(): void
    {
        $c = new LeastRequestConfig();
        $result = $c->setChoiceCount(10);
        self::assertSame($c, $result);
        self::assertSame(10, $c->getChoiceCount());
    }

}
