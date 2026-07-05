<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ExposeConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\ExposePath;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ExposeConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new ExposeConfig();
        self::assertFalse($c->isChecks());
        self::assertSame([], $c->getPaths());
    }

    public function testConstructorWithParams(): void
    {
        $path = new ExposePath(ListenerPort: 21500, Path: '/health', Protocol: 'http');
        $c = new ExposeConfig(Checks: true, Paths: [$path]);
        self::assertTrue($c->isChecks());
        self::assertCount(1, $c->getPaths());
    }

    public function testFluentSetters(): void
    {
        $path = new ExposePath(ListenerPort: 21500);
        $c = new ExposeConfig();
        $result = $c->setChecks(true)->setPaths($path);
        self::assertSame($c, $result);
        self::assertTrue($c->isChecks());
        self::assertCount(1, $c->getPaths());
    }

}
