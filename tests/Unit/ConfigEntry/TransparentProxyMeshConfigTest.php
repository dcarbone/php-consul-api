<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyMeshConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class TransparentProxyMeshConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new TransparentProxyMeshConfig();
        self::assertFalse($c->isMeshDestinationsOnly());
    }

    public function testConstructorWithParams(): void
    {
        $c = new TransparentProxyMeshConfig(MeshDestinationsOnly: true);
        self::assertTrue($c->isMeshDestinationsOnly());
    }

    public function testFluentSetters(): void
    {
        $c = new TransparentProxyMeshConfig();
        $result = $c->setMeshDestinationsOnly(true);
        self::assertSame($c, $result);
        self::assertTrue($c->isMeshDestinationsOnly());
    }

}
