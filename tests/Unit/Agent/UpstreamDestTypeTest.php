<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\UpstreamDestType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UpstreamDestTypeTest extends TestCase
{
    public function testCaseValues(): void
    {
        self::assertSame('service', UpstreamDestType::Service->value);
        self::assertSame('prepared_query', UpstreamDestType::PreparedQuery->value);
        self::assertSame('', UpstreamDestType::UNDEFINED->value);
    }

    public function testFromString(): void
    {
        self::assertSame(UpstreamDestType::Service, UpstreamDestType::from('service'));
        self::assertSame(UpstreamDestType::PreparedQuery, UpstreamDestType::from('prepared_query'));
        self::assertSame(UpstreamDestType::UNDEFINED, UpstreamDestType::from(''));
    }

    public function testTryFromValid(): void
    {
        self::assertSame(UpstreamDestType::Service, UpstreamDestType::tryFrom('service'));
    }

    public function testTryFromInvalid(): void
    {
        self::assertNull(UpstreamDestType::tryFrom('not-valid'));
    }

    public function testCaseCount(): void
    {
        self::assertCount(3, UpstreamDestType::cases());
    }
}

