<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MutualTLSMode;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MutualTLSModeTest extends TestCase
{

    public function testDefaultCase(): void
    {
        $e = MutualTLSMode::Default;
        self::assertSame('', $e->value);
    }

    public function testDefaultFromString(): void
    {
        $e = MutualTLSMode::from('');
        self::assertSame(MutualTLSMode::Default, $e);
    }

    public function testStrictCase(): void
    {
        $e = MutualTLSMode::Strict;
        self::assertSame('strict', $e->value);
    }

    public function testStrictFromString(): void
    {
        $e = MutualTLSMode::from('strict');
        self::assertSame(MutualTLSMode::Strict, $e);
    }

    public function testPermissiveCase(): void
    {
        $e = MutualTLSMode::Permissive;
        self::assertSame('permissive', $e->value);
    }

    public function testPermissiveFromString(): void
    {
        $e = MutualTLSMode::from('permissive');
        self::assertSame(MutualTLSMode::Permissive, $e);
    }
}
