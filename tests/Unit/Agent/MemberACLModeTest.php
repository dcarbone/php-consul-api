<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\MemberACLMode;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MemberACLModeTest extends TestCase
{
    public function testCaseValues(): void
    {
        self::assertSame('0', MemberACLMode::Disabled->value);
        self::assertSame('1', MemberACLMode::Enabled->value);
        self::assertSame('2', MemberACLMode::Legacy->value);
        self::assertSame('3', MemberACLMode::Unknown->value);
    }

    public function testFromString(): void
    {
        self::assertSame(MemberACLMode::Disabled, MemberACLMode::from('0'));
        self::assertSame(MemberACLMode::Enabled, MemberACLMode::from('1'));
        self::assertSame(MemberACLMode::Legacy, MemberACLMode::from('2'));
        self::assertSame(MemberACLMode::Unknown, MemberACLMode::from('3'));
    }

    public function testTryFromValid(): void
    {
        self::assertSame(MemberACLMode::Enabled, MemberACLMode::tryFrom('1'));
    }

    public function testTryFromInvalid(): void
    {
        self::assertNull(MemberACLMode::tryFrom('99'));
    }

    public function testCaseCount(): void
    {
        self::assertCount(4, MemberACLMode::cases());
    }
}

