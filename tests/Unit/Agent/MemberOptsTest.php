<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\MemberOpts;
use DCarbone\PHPConsulAPI\Agent\MembersOpts;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MemberOptsTest extends TestCase
{
    public function testDeprecatedAliasExtendsMembersOpts(): void
    {
        $m = new MemberOpts(WAN: true, Segment: 'seg', Filter: 'f');
        self::assertInstanceOf(MembersOpts::class, $m);
        self::assertTrue($m->isWAN());
        self::assertSame('seg', $m->getSegment());
        self::assertSame('f', $m->getFilter());
    }
}
