<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\KVTxnResponse;
use DCarbone\PHPConsulAPI\Txn\TxnError;
use DCarbone\PHPConsulAPI\Txn\TxnResult;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KVTxnResponseTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new KVTxnResponse();
        self::assertSame([], $r->getResults());
        self::assertSame([], $r->getErrors());
    }

    public function testConstructorWithValues(): void
    {
        $result = new TxnResult();
        $error = new TxnError(OpIndex: 0, What: 'err');
        $r = new KVTxnResponse(Results: [$result], Errors: [$error]);
        self::assertCount(1, $r->getResults());
        self::assertCount(1, $r->getErrors());
        self::assertSame('err', $r->getErrors()[0]->getWhat());
    }

    public function testFluentSetters(): void
    {
        $r = new KVTxnResponse();
        $result = $r
            ->setResults(new TxnResult(), new TxnResult())
            ->setErrors(new TxnError(OpIndex: 1, What: 'x'));
        self::assertSame($r, $result);
        self::assertCount(2, $r->getResults());
        self::assertCount(1, $r->getErrors());
    }

    public function testJsonSerialize(): void
    {
        $r = new KVTxnResponse(Results: [new TxnResult()], Errors: [new TxnError(OpIndex: 0, What: 'e')]);
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertIsArray($out->Results);
        self::assertIsArray($out->Errors);
    }

    public function testJsonUnserialize(): void
    {
        $resultObj = new \stdClass();
        $errorObj = new \stdClass();
        $errorObj->OpIndex = 2;
        $errorObj->What = 'failed';

        $decoded = new \stdClass();
        $decoded->Results = [$resultObj];
        $decoded->Errors = [$errorObj];

        $r = KVTxnResponse::jsonUnserialize($decoded);
        self::assertCount(1, $r->getResults());
        self::assertInstanceOf(TxnResult::class, $r->getResults()[0]);
        self::assertCount(1, $r->getErrors());
        self::assertSame('failed', $r->getErrors()[0]->getWhat());
    }
}

