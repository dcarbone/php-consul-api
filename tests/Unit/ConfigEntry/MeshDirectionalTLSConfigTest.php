<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshDirectionalTLSConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class MeshDirectionalTLSConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MeshDirectionalTLSConfig();
        self::assertSame('', $m->getTLSMinVersion());
        self::assertSame('', $m->getTLSMaxVersion());
        self::assertSame([], $m->getCipherSuites());
    }

    public function testConstructorWithParams(): void
    {
        $m = new MeshDirectionalTLSConfig(
            TLSMinVersion: 'TLSv1_2',
            TLSMaxVersion: 'TLSv1_3',
            CipherSuites: ['suite1'],
        );
        self::assertSame('TLSv1_2', $m->getTLSMinVersion());
        self::assertSame('TLSv1_3', $m->getTLSMaxVersion());
        self::assertSame(['suite1'], $m->getCipherSuites());
    }

    public function testFluentSetters(): void
    {
        $m = new MeshDirectionalTLSConfig();
        $result = $m->setTLSMinVersion('TLSv1_2')
            ->setTLSMaxVersion('TLSv1_3')
            ->setCipherSuites('s1', 's2');
        self::assertSame($m, $result);
        self::assertSame('TLSv1_2', $m->getTLSMinVersion());
        self::assertSame('TLSv1_3', $m->getTLSMaxVersion());
        self::assertSame(['s1', 's2'], $m->getCipherSuites());
    }

}
