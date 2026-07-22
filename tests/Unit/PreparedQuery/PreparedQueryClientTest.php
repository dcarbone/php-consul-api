<?php

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class PreparedQueryClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): PreparedQueryClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new PreparedQueryClient(new Config(HttpClient: $httpClient));
    }

    public function testCreateParsesIDFromResponseObject(): void
    {
        $client = $this->mockClient([new Response(200, [], json_encode(['ID' => 'query-1'], JSON_THROW_ON_ERROR))]);

        $resp = $client->Create(new PreparedQueryDefinition(Name: 'web-query'));

        self::assertNull($resp->Err);
        self::assertSame('query-1', $resp->Value);
        self::assertSame('/v1/query', $this->history[0]['request']->getUri()->getPath());
    }

    public function testUpdateUsesQueryIDPath(): void
    {
        $client = $this->mockClient([new Response(200, [], '')]);

        $resp = $client->Update(new PreparedQueryDefinition(ID: 'query-abc', Name: 'q'));

        self::assertNull($resp->Err);
        self::assertSame('/v1/query/query-abc', $this->history[0]['request']->getUri()->getPath());
    }

    public function testUpdateRejectsEmptyQueryID(): void
    {
        $client = $this->mockClient([]);

        $resp = $client->Update(new PreparedQueryDefinition(ID: '', Name: 'q'));

        self::assertNotNull($resp->Err);
        self::assertStringContainsString('cannot be empty', $resp->Err->getMessage());
    }
}
