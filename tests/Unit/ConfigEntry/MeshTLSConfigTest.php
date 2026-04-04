<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshTLSConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\MeshDirectionalTLSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class MeshTLSConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MeshTLSConfig();
        self::assertNull($m->getIncoming());
        self::assertNull($m->getOutgoing());
    }

    public function testConstructorWithParams(): void
    {
        $incoming = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_2');
        $outgoing = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_3');
        $m = new MeshTLSConfig(Incoming: $incoming, Outgoing: $outgoing);
        self::assertSame($incoming, $m->getIncoming());
        self::assertSame($outgoing, $m->getOutgoing());
    }

    public function testFluentSetters(): void
    {
        $incoming = new MeshDirectionalTLSConfig(TLSMinVersion: 'TLSv1_2');
        $m = new MeshTLSConfig();
        $result = $m->setIncoming($incoming);
        self::assertSame($m, $result);
        self::assertSame($incoming, $m->getIncoming());
    }

}
