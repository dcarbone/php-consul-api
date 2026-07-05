<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionSourceType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class IntentionSourceTypeTest extends TestCase
{

    public function testConsulCase(): void
    {
        $e = IntentionSourceType::Consul;
        self::assertSame('consul', $e->value);
    }

    public function testConsulFromString(): void
    {
        $e = IntentionSourceType::from('consul');
        self::assertSame(IntentionSourceType::Consul, $e);
    }

    public function testUNDEFINEDCase(): void
    {
        $e = IntentionSourceType::UNDEFINED;
        self::assertSame('', $e->value);
    }

    public function testUNDEFINEDFromString(): void
    {
        $e = IntentionSourceType::from('');
        self::assertSame(IntentionSourceType::UNDEFINED, $e);
    }
}
