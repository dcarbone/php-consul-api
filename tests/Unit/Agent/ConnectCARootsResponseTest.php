<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\CARootList;
use DCarbone\PHPConsulAPI\Agent\ConnectCARootsResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConnectCARootsResponseTest extends TestCase
{
    public function testDefaultsAndUnmarshal(): void
    {
        $r = new ConnectCARootsResponse();
        self::assertNull($r->getValue());

        $obj = new \stdClass();
        $obj->ActiveRootID = 'root-1';
        $obj->TrustDomain = 'consul';
        $obj->Roots = [];
        $r->unmarshalValue($obj);

        self::assertInstanceOf(CARootList::class, $r->CARoots);
        self::assertSame('root-1', $r->CARoots->ActiveRootID);
    }
}
