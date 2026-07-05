<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\KVTxnAPIResponse;
use DCarbone\PHPConsulAPI\Txn\KVTxnResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVTxnAPIResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $resp = new KVTxnAPIResponse();
        self::assertFalse($resp->isOK());
        self::assertFalse($resp->OK);
        self::assertNull($resp->getKVTxnResponse());
        self::assertNull($resp->KVTxnResponse);
        self::assertNull($resp->Err);
    }

    public function testSetOK(): void
    {
        $resp = new KVTxnAPIResponse();
        $result = $resp->setOK(true);
        self::assertSame($resp, $result);
        self::assertTrue($resp->isOK());
        self::assertTrue($resp->OK);
    }

    public function testSetKVTxnResponse(): void
    {
        $resp = new KVTxnAPIResponse();
        $txnResp = new KVTxnResponse();
        $result = $resp->setKVTxnResponse($txnResp);
        self::assertSame($resp, $result);
        self::assertSame($txnResp, $resp->getKVTxnResponse());
        self::assertSame($txnResp, $resp->KVTxnResponse);
    }

    public function testSetKVTxnResponseToNull(): void
    {
        $resp = new KVTxnAPIResponse();
        $resp->setKVTxnResponse(new KVTxnResponse());
        $resp->setKVTxnResponse(null);
        self::assertNull($resp->getKVTxnResponse());
    }

    public function testFluentSetters(): void
    {
        $resp = new KVTxnAPIResponse();
        $txnResp = new KVTxnResponse();
        $result = $resp
            ->setOK(true)
            ->setKVTxnResponse($txnResp);
        self::assertSame($resp, $result);
        self::assertTrue($resp->isOK());
        self::assertSame($txnResp, $resp->getKVTxnResponse());
    }
}

