<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogNodeServicesListResponse;
use DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class CatalogClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    private function mockClient(int $statusCode, string $body): CatalogClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler([new Response($statusCode, [], $body)]));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new CatalogClient(new Config(HttpClient: $httpClient));
    }

    public function testNodeServiceListAliasUsesSameEndpointAsNodeServicesList(): void
    {
        $payload = json_encode(['Node' => ['Node' => 'node-1'], 'Services' => []], JSON_THROW_ON_ERROR);
        $client = $this->mockClient(200, $payload);

        $response = $client->NodeServiceList('node-1');

        self::assertInstanceOf(CatalogNodeServicesListResponse::class, $response);
        self::assertCount(1, $this->history);
        self::assertSame('/v1/catalog/node-services/node-1', $this->history[0]['request']->getUri()->getPath());
    }

    public function testConnectUsesConnectEndpointAndSingleTag(): void
    {
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR));

        $response = $client->Connect('web', 'blue');

        self::assertInstanceOf(CatalogServicesResponse::class, $response);
        self::assertCount(1, $this->history);
        self::assertSame('/v1/catalog/connect/web', $this->history[0]['request']->getUri()->getPath());
        parse_str($this->history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('blue', $query['tag']);
    }

    public function testConnectMultipleTagsUsesRepeatedTagAndOptions(): void
    {
        $client = $this->mockClient(200, json_encode([], JSON_THROW_ON_ERROR));

        $response = $client->ConnectMultipleTags('web', ['blue', 'v1'], new QueryOptions(Pretty: true));

        self::assertInstanceOf(CatalogServicesResponse::class, $response);
        self::assertCount(1, $this->history);
        self::assertSame('/v1/catalog/connect/web', $this->history[0]['request']->getUri()->getPath());
        $query = $this->history[0]['request']->getUri()->getQuery();
        self::assertStringContainsString('tag=blue', $query);
        self::assertStringContainsString('tag=v1', $query);
        self::assertStringContainsString('pretty', $query);
    }
}
