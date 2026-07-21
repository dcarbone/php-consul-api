<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Health\HealthChecksResponse;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class HealthClientTest extends TestCase
{
    private function mockClient(int $statusCode, string $body, array &$history): HealthClient
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response($statusCode, [], $body)]));
        $stack->push(Middleware::history($history));
        $httpClient = new Client(['handler' => $stack]);
        return new HealthClient(new Config(HttpClient: $httpClient));
    }

    public function testNodeSimple(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->Node('node-1');

        self::assertInstanceOf(HealthChecksResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('/v1/health/node/node-1', $history[0]['request']->getUri()->getPath());
    }

    public function testChecksSimple(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->Checks('web');

        self::assertInstanceOf(HealthChecksResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('/v1/health/checks/web', $history[0]['request']->getUri()->getPath());
    }

    public function testServiceMultipleTagsUsesRepeatedTagAndPassing(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->ServiceMultipleTags('web', ['blue', 'v1'], true);

        self::assertInstanceOf(ServiceEntriesResponse::class, $response);
        self::assertSame('/v1/health/service/web', $history[0]['request']->getUri()->getPath());
        self::assertSame('tag=blue&tag=v1&passing=1', $history[0]['request']->getUri()->getQuery());
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('1', $query['passing']);
    }

    public function testConnectMultipleTagsUsesConnectPath(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->ConnectMultipleTags('web', ['blue', 'v1'], true);

        self::assertInstanceOf(ServiceEntriesResponse::class, $response);
        self::assertSame('/v1/health/connect/web', $history[0]['request']->getUri()->getPath());
        self::assertSame('tag=blue&tag=v1&passing=1', $history[0]['request']->getUri()->getQuery());
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('1', $query['passing']);
    }

    public function testIngressSupportsGoStyleSignature(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->Ingress('web', true);

        self::assertInstanceOf(ServiceEntriesResponse::class, $response);
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('/v1/health/ingress/web', $history[0]['request']->getUri()->getPath());
        self::assertArrayNotHasKey('tag', $query);
        self::assertSame('1', $query['passing']);
    }

    public function testIngressSupportsGoStyleSignatureWithOptions(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->Ingress('web', true, new QueryOptions(Pretty: true));

        self::assertInstanceOf(ServiceEntriesResponse::class, $response);
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('/v1/health/ingress/web', $history[0]['request']->getUri()->getPath());
        self::assertArrayNotHasKey('tag', $query);
        self::assertSame('1', $query['passing']);
        self::assertArrayHasKey('pretty', $query);
    }

    public function testIngressRetainsLegacyTagSupport(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->Ingress('web', 'blue', true);

        self::assertInstanceOf(ServiceEntriesResponse::class, $response);
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('/v1/health/ingress/web', $history[0]['request']->getUri()->getPath());
        self::assertSame('blue', $query['tag']);
        self::assertSame('1', $query['passing']);
    }

    public function testStateRejectsUnknown(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->State('unknown');

        self::assertInstanceOf(HealthChecksResponse::class, $response);
        self::assertNotNull($response->Err);
        self::assertCount(0, $history);
    }

    public function testStatePassingUsesExpectedPath(): void
    {
        $history = [];
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR), $history);

        $response = $client->State('passing');

        self::assertInstanceOf(HealthChecksResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('/v1/health/state/passing', $history[0]['request']->getUri()->getPath());
    }
}
