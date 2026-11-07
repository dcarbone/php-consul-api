<?php

namespace DCarbone\PHPConsulAPITests\Unit\KV;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairResponse;
use DCarbone\PHPConsulAPI\KV\KVPairsResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Txn\KVOp;
use DCarbone\PHPConsulAPI\Txn\KVTxnAPIResponse;
use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class KVClientTest extends TestCase
{
    /**
     * @param array<string, array<int, string>> $headers
     */
    private function mockClient(int $statusCode, string $body, mixed &$history, array $headers = []): KVClient
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response($statusCode, $headers, $body)]));
        $stack->push(Middleware::history($history));
        $httpClient = new Client(['handler' => $stack]);
        return new KVClient(new Config(HttpClient: $httpClient));
    }

    public function testGetSimpleKey(): void
    {
        $kvPair = [
            'Key' => 'test/key',
            'CreateIndex' => 100,
            'ModifyIndex' => 101,
            'LockIndex' => 0,
            'Flags' => 42,
            'Value' => base64_encode('test value'),
            'Session' => '',
        ];
        $history = [];
        $client = $this->mockClient(200, json_encode([$kvPair], JSON_THROW_ON_ERROR), $history);

        $response = $client->Get('test/key');

        self::assertInstanceOf(KVPairResponse::class, $response);
        self::assertCount(1, $history);
    }

    public function testGetNonExistentReturnsNull(): void
    {
        $history = [];
        $client = $this->mockClient(404, '', $history);

        $response = $client->Get('test/nonexistent');

        self::assertInstanceOf(KVPairResponse::class, $response);
        self::assertNull($response->getValue());
    }

    public function testListKeys(): void
    {
        $kvPairs = [
            [
                'Key' => 'test/key1',
                'CreateIndex' => 100,
                'ModifyIndex' => 101,
                'LockIndex' => 0,
                'Flags' => 0,
                'Value' => base64_encode('value1'),
                'Session' => '',
            ],
        ];
        $history = [];
        $client = $this->mockClient(200, json_encode($kvPairs, JSON_THROW_ON_ERROR), $history);

        $response = $client->List('test/');

        self::assertInstanceOf(KVPairsResponse::class, $response);
        self::assertCount(1, $history);
    }

    public function testKeysSimple(): void
    {
        $keys = ['test/key1', 'test/key2', 'test/subfolder/'];
        $history = [];
        $client = $this->mockClient(200, json_encode($keys, JSON_THROW_ON_ERROR), $history);

        $response = $client->Keys('test/');

        self::assertInstanceOf(ValuedQueryStringsResponse::class, $response);
        self::assertCount(1, $history);
    }

    public function testKeysWithSeparator(): void
    {
        $keys = ['test/folder/'];
        $history = [];
        $client = $this->mockClient(200, json_encode($keys, JSON_THROW_ON_ERROR), $history);

        $response = $client->Keys('test/', '/');

        self::assertInstanceOf(ValuedQueryStringsResponse::class, $response);
        self::assertCount(1, $history);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayHasKey('keys', $query);
        self::assertSame('/', $query['separator']);
    }

    public function testKeysSecondArgumentQueryOptionsForBC(): void
    {
        $keys = ['test/key1'];
        $history = [];
        $client = $this->mockClient(200, json_encode($keys, JSON_THROW_ON_ERROR), $history);

        $response = $client->Keys('test/', new QueryOptions(Pretty: true));

        self::assertInstanceOf(ValuedQueryStringsResponse::class, $response);
        self::assertCount(1, $history);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayHasKey('keys', $query);
        self::assertArrayNotHasKey('separator', $query);
        self::assertArrayHasKey('pretty', $query);
    }

    public function testKeysSecondArgumentNullForBC(): void
    {
        $keys = ['test/key1'];
        $history = [];
        $client = $this->mockClient(200, json_encode($keys, JSON_THROW_ON_ERROR), $history);

        $response = $client->Keys('test/', null);

        self::assertInstanceOf(ValuedQueryStringsResponse::class, $response);
        self::assertCount(1, $history);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayHasKey('keys', $query);
        self::assertArrayNotHasKey('separator', $query);
    }

    public function testPutSimple(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Put(new KVPair(Key: 'test/key', Value: 'test value'));

        self::assertInstanceOf(WriteResponse::class, $response);
        self::assertCount(1, $history);
    }

    public function testPutCapturesKVWarnings(): void
    {
        $history = [];
        $client = $this->mockClient(
            200,
            'true',
            $history,
            [Consul::_headerConsulKVWarning => ['warning-1', 'warning-2']],
        );

        $response = $client->Put(new KVPair(Key: 'test/key', Value: 'test value'));

        self::assertSame(['warning-1', 'warning-2'], $response->getWriteMeta()?->getWarnings());
    }

    public function testPutWithFlags(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Put(new KVPair(Key: 'test/key', Flags: 42, Value: 'test value'));

        self::assertInstanceOf(WriteResponse::class, $response);
    }

    public function testPutRejectsLeadingSlash(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid key. Key must not begin with a '/': /test/key");

        $client->Put(new KVPair(Key: '/test/key', Value: 'test value'));
    }

    public function testCasSuccessful(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->CAS(new KVPair(Key: 'test/key', ModifyIndex: 101, Value: 'new value'));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testCAsFailed(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'false', $history);

        $response = $client->CAS(new KVPair(Key: 'test/key', ModifyIndex: 99, Value: 'new value'));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertFalse($response->getValue());
    }

    public function testDeleteSimple(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Delete('test/key');

        self::assertInstanceOf(WriteResponse::class, $response);
    }

    public function testDeleteCASSuccessful(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->DeleteCAS(new KVPair(Key: 'test/key', ModifyIndex: 101));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testDeleteCASFailed(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'false', $history);

        $response = $client->DeleteCAS(new KVPair(Key: 'test/key', ModifyIndex: 99));

        self::assertFalse($response->getValue());
    }

    public function testDeleteTree(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->DeleteTree('test/');

        self::assertInstanceOf(WriteResponse::class, $response);
    }

    public function testAcquireLock(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Acquire(new KVPair(Key: 'test/lock', Value: 'locked', Session: 'session-id'));

        self::assertInstanceOf(WriteResponse::class, $response);
    }

    public function testAcquireBoolLock(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->AcquireBool(new KVPair(Key: 'test/lock', Value: 'locked', Session: 'session-id'));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testReleaseLock(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Release(new KVPair(Key: 'test/lock', Value: 'released', Session: 'session-id'));

        self::assertInstanceOf(WriteResponse::class, $response);
    }

    public function testReleaseBoolLock(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->ReleaseBool(new KVPair(Key: 'test/lock', Value: 'released', Session: 'session-id'));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testGetTrimsLeadingSlash(): void
    {
        $kvPair = [
            'Key' => 'test/key',
            'CreateIndex' => 100,
            'ModifyIndex' => 101,
            'LockIndex' => 0,
            'Flags' => 42,
            'Value' => base64_encode('test value'),
            'Session' => '',
        ];
        $history = [];
        $client = $this->mockClient(200, json_encode([$kvPair], JSON_THROW_ON_ERROR), $history);

        $response = $client->Get('/test/key');

        self::assertInstanceOf(KVPairResponse::class, $response);
        self::assertSame('/v1/kv/test/key', $history[0]['request']->getUri()->getPath());
    }

    public function testTxnGetOperation(): void
    {
        $kvPair = [
            'Key' => 'test/key',
            'CreateIndex' => 100,
            'ModifyIndex' => 101,
            'LockIndex' => 0,
            'Flags' => 0,
            'Value' => base64_encode('test'),
            'Session' => '',
        ];
        $txnResponse = [
            'Results' => [$kvPair],
            'Errors' => [],
        ];
        $history = [];
        $client = $this->mockClient(200, json_encode($txnResponse, JSON_THROW_ON_ERROR), $history);

        $response = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVGet, Key: 'test/key')));

        self::assertInstanceOf(KVTxnAPIResponse::class, $response);
        self::assertTrue($response->OK);
    }

    public function testTxnSetOperation(): void
    {
        $kvPair = [
            'Key' => 'test/key',
            'CreateIndex' => 100,
            'ModifyIndex' => 101,
            'LockIndex' => 0,
            'Flags' => 0,
            'Value' => base64_encode('test'),
            'Session' => '',
        ];
        $txnResponse = [
            'Results' => [$kvPair],
            'Errors' => [],
        ];
        $history = [];
        $client = $this->mockClient(200, json_encode($txnResponse, JSON_THROW_ON_ERROR), $history);

        $response = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/key', Value: 'test')));

        self::assertInstanceOf(KVTxnAPIResponse::class, $response);
        self::assertTrue($response->OK);
    }

    public function testTxnConflictSetsOKFalse(): void
    {
        $txnResponse = [
            'Results' => [],
            'Errors' => [['OpIndex' => 0, 'What' => 'conflict']],
        ];
        $history = [];
        $client = $this->mockClient(409, json_encode($txnResponse, JSON_THROW_ON_ERROR), $history);

        $response = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/key', Value: 'test')));

        self::assertInstanceOf(KVTxnAPIResponse::class, $response);
        self::assertFalse($response->OK);
        self::assertNotNull($response->KVTxnResponse);
        self::assertCount(1, $response->KVTxnResponse->Errors);
    }

    public function testGetDecodesUint64Values(): void
    {
        $body = '[{"Key":"test/key","CreateIndex":9223372036854775808,"ModifyIndex":18446744073709551614,'
            . '"LockIndex":7,"Flags":18446744073709551615,"Value":"","Session":""}]';
        $history = [];
        $client = $this->mockClient(200, $body, $history, ['X-Consul-Index' => ['18446744073709551615']]);

        $response = $client->Get('test/key');

        self::assertNull($response->Err);
        $pair = $response->getValue();
        self::assertNotNull($pair);
        self::assertSame('9223372036854775808', $pair->CreateIndex->toString());
        self::assertSame('18446744073709551614', $pair->ModifyIndex->toString());
        self::assertSame('7', $pair->LockIndex->toString());
        self::assertSame('18446744073709551615', $pair->Flags->toString());
        self::assertNotNull($response->QueryMeta);
        self::assertSame('18446744073709551615', $response->QueryMeta->LastIndex->toString());
    }

    public function testInvalidIndexHeaderLeavesLastIndexZero(): void
    {
        $history = [];
        $client = $this->mockClient(404, '', $history, ['X-Consul-Index' => ['not-a-number']]);

        $response = $client->Get('test/key');

        self::assertNotNull($response->QueryMeta);
        self::assertTrue($response->QueryMeta->LastIndex->isZero());
    }

    public function testPutAndCASEncodeUint64QueryParams(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $client->CAS(new KVPair(Key: 'test/key', ModifyIndex: '9223372036854775808', Flags: BigInteger::of('18446744073709551615'), Value: 'v'));

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('18446744073709551615', $query['flags']);
        self::assertSame('9223372036854775808', $query['cas']);
    }

    public function testPutOmitsZeroFlags(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $client->Put(new KVPair(Key: 'test/key', Value: 'v'));

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayNotHasKey('flags', $query);
    }

    public function testWaitIndexEncodedAsUint64(): void
    {
        $history = [];
        $client = $this->mockClient(404, '', $history);

        $client->Get('test/key', new QueryOptions(WaitIndex: '18446744073709551615'));

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('18446744073709551615', $query['index']);
    }

    public function testTxnEncodesUint64Body(): void
    {
        $history = [];
        $client = $this->mockClient(200, '{"Results":[],"Errors":[]}', $history);

        $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVCAS, Key: 'test/key', Value: 'v', Flags: '18446744073709551615', Index: '9223372036854775808')));

        $request = $history[0]['request'];
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $body = (string)$request->getBody();
        self::assertStringContainsString('"Flags":18446744073709551615', $body);
        self::assertStringContainsString('"Index":9223372036854775808', $body);
    }

    public function testGetInvalidValueReturnsErr(): void
    {
        $client = $this->mockClient(200, '[{"Key":"foo","Value":"!!not-base64!!"}]', $history);

        $response = $client->Get('foo');

        self::assertNotNull($response->Err);
        self::assertStringContainsString('Could not base64 decode value', $response->Err->getMessage());
    }

    public function testListInvalidValueReturnsErr(): void
    {
        $client = $this->mockClient(200, '[{"Key":"foo","Value":"!!not-base64!!"}]', $history);

        $response = $client->List('foo');

        self::assertNotNull($response->Err);
        self::assertStringContainsString('Could not base64 decode value', $response->Err->getMessage());
    }

    public function testTxnInvalidNestedValueReturnsErr(): void
    {
        $body = json_encode(
            ['Results' => [['KV' => ['Key' => 'foo', 'Value' => '!!not-base64!!']]], 'Errors' => []],
            JSON_THROW_ON_ERROR
        );
        $client = $this->mockClient(200, $body, $history);

        $response = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVGet, Key: 'foo')));

        self::assertFalse($response->OK);
        self::assertNotNull($response->Err);
        self::assertStringContainsString('Could not base64 decode value', $response->Err->getMessage());
        self::assertNull($response->KVTxnResponse);
    }
}
