<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionAction;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class IntentionActionTest extends TestCase
{

    public function testAllowCase(): void
    {
        $e = IntentionAction::Allow;
        self::assertSame('allow', $e->value);
    }

    public function testAllowFromString(): void
    {
        $e = IntentionAction::from('allow');
        self::assertSame(IntentionAction::Allow, $e);
    }

    public function testDenyCase(): void
    {
        $e = IntentionAction::Deny;
        self::assertSame('deny', $e->value);
    }

    public function testDenyFromString(): void
    {
        $e = IntentionAction::from('deny');
        self::assertSame(IntentionAction::Deny, $e);
    }

    public function testUNDEFINEDCase(): void
    {
        $e = IntentionAction::UNDEFINED;
        self::assertSame('', $e->value);
    }

    public function testUNDEFINEDFromString(): void
    {
        $e = IntentionAction::from('');
        self::assertSame(IntentionAction::UNDEFINED, $e);
    }
}
