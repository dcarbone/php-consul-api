<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Txn\TxnError;
use DCarbone\PHPConsulAPI\Txn\TxnResponse;
use DCarbone\PHPConsulAPI\Txn\TxnResult;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class TxnResponseTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $resp = new TxnResponse();
        self::assertSame([], $resp->getResults());
        self::assertSame([], $resp->getErrors());
    }

    public function testConstructorWithValues(): void
    {
        $r1 = new TxnResult();
        $e1 = new TxnError(OpIndex: 0, What: 'fail');

        $resp = new TxnResponse(Results: [$r1], Errors: [$e1]);

        self::assertCount(1, $resp->getResults());
        self::assertCount(1, $resp->getErrors());
        self::assertSame('fail', $resp->getErrors()[0]->getWhat());
    }

    public function testVariadicSetResults(): void
    {
        $resp = new TxnResponse();
        $r1 = new TxnResult();
        $r2 = new TxnResult();
        $result = $resp->setResults($r1, $r2);

        self::assertSame($resp, $result);
        self::assertCount(2, $resp->getResults());
    }

    public function testVariadicSetErrors(): void
    {
        $resp = new TxnResponse();
        $e1 = new TxnError(OpIndex: 0, What: 'a');
        $e2 = new TxnError(OpIndex: 1, What: 'b');
        $result = $resp->setErrors($e1, $e2);

        self::assertSame($resp, $result);
        self::assertCount(2, $resp->getErrors());
        self::assertSame('a', $resp->getErrors()[0]->getWhat());
        self::assertSame('b', $resp->getErrors()[1]->getWhat());
    }

    public function testVariadicSettersReplaceExisting(): void
    {
        $resp = new TxnResponse(
            Results: [new TxnResult()],
            Errors: [new TxnError(OpIndex: 0, What: 'old')],
        );

        $resp->setResults();
        $resp->setErrors();

        self::assertSame([], $resp->getResults());
        self::assertSame([], $resp->getErrors());
    }

    public function testJsonSerialize(): void
    {
        $resp = new TxnResponse(
            Errors: [new TxnError(OpIndex: 3, What: 'err')],
        );

        $out = $resp->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame([], $out->Results);
        self::assertIsArray($out->Errors);
        self::assertCount(1, $out->Errors);
    }

    public function testJsonUnserialize(): void
    {
        $errObj = new \stdClass();
        $errObj->OpIndex = 2;
        $errObj->What = 'something failed';

        $resultObj = new \stdClass();

        $decoded = new \stdClass();
        $decoded->Results = [$resultObj];
        $decoded->Errors = [$errObj];

        $resp = TxnResponse::jsonUnserialize($decoded);

        self::assertCount(1, $resp->getResults());
        self::assertInstanceOf(TxnResult::class, $resp->getResults()[0]);
        self::assertCount(1, $resp->getErrors());
        self::assertInstanceOf(TxnError::class, $resp->getErrors()[0]);
        self::assertSame(2, $resp->getErrors()[0]->getOpIndex());
        self::assertSame('something failed', $resp->getErrors()[0]->getWhat());
    }

    public function testJsonUnserializeHandlesNullArrays(): void
    {
        $decoded = new \stdClass();
        $decoded->Results = null;
        $decoded->Errors = null;

        $resp = TxnResponse::jsonUnserialize($decoded);

        self::assertSame([], $resp->getResults());
        self::assertSame([], $resp->getErrors());
    }
}

