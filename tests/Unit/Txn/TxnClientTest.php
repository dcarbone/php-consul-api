<?php

namespace DCarbone\PHPConsulAPITests\Unit\Txn;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Txn\KVOp;
use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnClient;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class TxnClientTest extends TestCase
{
    /** @var array<int, array<string,mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): TxnClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new TxnClient(new Config(HttpClient: $httpClient));
    }

    public function testTxnSuccessResponse(): void
    {
        $payload = [
            'Results' => [[
                'KV' => [
                    'Key' => 'test/key',
                    'CreateIndex' => 1,
                    'ModifyIndex' => 1,
                    'LockIndex' => 0,
                    'Flags' => 0,
                    'Value' => base64_encode('value'),
                    'Session' => '',
                ],
            ]],
            'Errors' => [],
        ];
        $client = $this->mockClient([new Response(200, [], json_encode($payload, JSON_THROW_ON_ERROR))]);

        $resp = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVGet, Key: 'test/key')));

        self::assertNull($resp->Err);
        self::assertTrue($resp->OK);
        self::assertNotNull($resp->TxnResponse);
        self::assertSame('/v1/txn', $this->history[0]['request']->getUri()->getPath());
    }

    public function testTxnConflictSetsOKFalse(): void
    {
        $payload = [
            'Results' => [],
            'Errors' => [['OpIndex' => 0, 'What' => 'conflict']],
        ];
        $client = $this->mockClient([new Response(409, [], json_encode($payload, JSON_THROW_ON_ERROR))]);

        $resp = $client->Txn(null, new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/key', Value: 'x')));

        self::assertNull($resp->Err);
        self::assertFalse($resp->OK);
        self::assertNotNull($resp->TxnResponse);
        self::assertCount(1, $resp->TxnResponse->Errors);
    }
}
