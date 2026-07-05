<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentAuthorize;
use DCarbone\PHPConsulAPI\Agent\AgentAuthorizeResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentAuthorizeResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new AgentAuthorizeResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->Authorize);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValue(): void
    {
        $obj = new \stdClass();
        $obj->Authorized = true;
        $obj->Reason = 'ok';

        $r = new AgentAuthorizeResponse();
        $r->unmarshalValue($obj);

        self::assertInstanceOf(AgentAuthorize::class, $r->getValue());
        self::assertTrue($r->Authorize->Authorized);
        self::assertSame('ok', $r->Authorize->Reason);
    }
}
