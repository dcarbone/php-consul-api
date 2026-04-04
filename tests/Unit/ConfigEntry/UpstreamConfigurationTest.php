<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfiguration;
use DCarbone\PHPConsulAPI\ConfigEntry\UpstreamConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class UpstreamConfigurationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $uc = new UpstreamConfiguration();
        self::assertSame([], $uc->getOverrides());
        self::assertNull($uc->getDefaults());
    }

    public function testConstructorWithParams(): void
    {
        $defaults = new UpstreamConfig(Protocol: 'http');
        $override = new UpstreamConfig(Name: 'db', Protocol: 'tcp');
        $uc = new UpstreamConfiguration(
            Overrides: [$override],
            Defaults: $defaults,
        );
        self::assertCount(1, $uc->getOverrides());
        self::assertSame($defaults, $uc->getDefaults());
    }

    public function testFluentSetters(): void
    {
        $defaults = new UpstreamConfig(Protocol: 'grpc');
        $uc = new UpstreamConfiguration();
        $result = $uc->setDefaults($defaults);
        self::assertSame($uc, $result);
        self::assertSame($defaults, $uc->getDefaults());
    }

}
