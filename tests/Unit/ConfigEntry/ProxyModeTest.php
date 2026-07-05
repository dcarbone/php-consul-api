<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ProxyMode;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ProxyModeTest extends TestCase
{

    public function testDefaultCase(): void
    {
        $e = ProxyMode::Default;
        self::assertSame('', $e->value);
    }

    public function testDefaultFromString(): void
    {
        $e = ProxyMode::from('');
        self::assertSame(ProxyMode::Default, $e);
    }

    public function testTransparentCase(): void
    {
        $e = ProxyMode::Transparent;
        self::assertSame('transparent', $e->value);
    }

    public function testTransparentFromString(): void
    {
        $e = ProxyMode::from('transparent');
        self::assertSame(ProxyMode::Transparent, $e);
    }

    public function testDirectCase(): void
    {
        $e = ProxyMode::Direct;
        self::assertSame('direct', $e->value);
    }

    public function testDirectFromString(): void
    {
        $e = ProxyMode::from('direct');
        self::assertSame(ProxyMode::Direct, $e);
    }
}
