<?php

namespace DCarbone\PHPConsulAPITests\Unit\Status;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Status\StatusClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class StatusClientTest extends TestCase
{
    /** @var array<int, array<string,mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): StatusClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new StatusClient(new Config(HttpClient: $httpClient));
    }

    public function testLeaderUsesExpectedEndpoint(): void
    {
        $client = $this->mockClient([new Response(200, [], json_encode('127.0.0.1:8300', JSON_THROW_ON_ERROR))]);

        $resp = $client->Leader(new QueryOptions(Pretty: true));

        self::assertNull($resp->Err);
        self::assertSame('127.0.0.1:8300', $resp->Value);
        self::assertSame('/v1/status/leader', $this->history[0]['request']->getUri()->getPath());
    }

    public function testPeersUsesExpectedEndpoint(): void
    {
        $client = $this->mockClient([new Response(200, [], json_encode(['127.0.0.1:8300'], JSON_THROW_ON_ERROR))]);

        $resp = $client->Peers();

        self::assertNull($resp->Err);
        self::assertSame(['127.0.0.1:8300'], $resp->Value);
        self::assertSame('/v1/status/peers', $this->history[0]['request']->getUri()->getPath());
    }
}
