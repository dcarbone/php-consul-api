<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ConnectCALeafResponse;
use DCarbone\PHPConsulAPI\Agent\LeafCert;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConnectCALeafResponseTest extends TestCase
{
    public function testDefaultsAndUnmarshal(): void
    {
        $r = new ConnectCALeafResponse();
        self::assertNull($r->getValue());

        $obj = new \stdClass();
        $obj->SerialNumber = '123';
        $obj->Service = 'web';
        $obj->ServiceURI = 'spiffe://web';
        $obj->CreateIndex = 1;
        $obj->ModifyIndex = 2;
        $r->unmarshalValue($obj);

        self::assertInstanceOf(LeafCert::class, $r->LeafCert);
        self::assertSame('123', $r->LeafCert->SerialNumber);
    }
}
