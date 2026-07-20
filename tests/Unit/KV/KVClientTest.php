<?php

namespace DCarbone\PHPConsulAPITests\Unit\KV;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairResponse;
use DCarbone\PHPConsulAPI\KV\KVPairsResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
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
    private function mockClient(int $statusCode, string $body, array &$history, array $headers = []): KVClient
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

        $response = $client->Put(new KVPair(Key: 'test/key', Value: 'test value', Flags: 42));

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

        $response = $client->CAS(new KVPair(Key: 'test/key', Value: 'new value', ModifyIndex: 101));

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testCAsFailed(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'false', $history);

        $response = $client->CAS(new KVPair(Key: 'test/key', Value: 'new value', ModifyIndex: 99));

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

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $response);
        self::assertTrue($response->getValue());
    }

    public function testReleaseLock(): void
    {
        $history = [];
        $client = $this->mockClient(200, 'true', $history);

        $response = $client->Release(new KVPair(Key: 'test/lock', Value: 'released', Session: 'session-id'));

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
}
