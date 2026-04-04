<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionMatchType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class IntentionMatchTypeTest extends TestCase
{

    public function testSourceCase(): void
    {
        $e = IntentionMatchType::Source;
        self::assertSame('source', $e->value);
    }

    public function testSourceFromString(): void
    {
        $e = IntentionMatchType::from('source');
        self::assertSame(IntentionMatchType::Source, $e);
    }

    public function testDestinationCase(): void
    {
        $e = IntentionMatchType::Destination;
        self::assertSame('destination', $e->value);
    }

    public function testDestinationFromString(): void
    {
        $e = IntentionMatchType::from('destination');
        self::assertSame(IntentionMatchType::Destination, $e);
    }

    public function testUNDEFINEDCase(): void
    {
        $e = IntentionMatchType::UNDEFINED;
        self::assertSame('', $e->value);
    }

    public function testUNDEFINEDFromString(): void
    {
        $e = IntentionMatchType::from('');
        self::assertSame(IntentionMatchType::UNDEFINED, $e);
    }
}
