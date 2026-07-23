<?php

namespace DCarbone\PHPConsulAPITests\Unit\Session;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SessionClientTest extends TestCase
{
    /** @var array<int, array<string,mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): SessionClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new SessionClient(new Config(HttpClient: $httpClient));
    }

    public function testCreateAllowsNullSessionEntry(): void
    {
        $client = $this->mockClient([
            new Response(200, [], json_encode(['ID' => 'sess-1'], JSON_THROW_ON_ERROR)),
        ]);

        $resp = $client->Create();

        self::assertNull($resp->Err);
        self::assertSame('sess-1', $resp->Value);
        self::assertSame('/v1/session/create', $this->history[0]['request']->getUri()->getPath());
    }

    public function testCreateNoChecksAlwaysSendsNodeChecksEmpty(): void
    {
        $client = $this->mockClient([
            new Response(200, [], json_encode(['ID' => 'sess-2'], JSON_THROW_ON_ERROR)),
        ]);

        $resp = $client->CreateNoChecks(new SessionEntry(Name: 'my-session', TTL: '10s', Checks: ['serfHealth']));

        self::assertNull($resp->Err);
        self::assertSame('sess-2', $resp->Value);

        $payload = json_decode((string)$this->history[0]['request']->getBody(), false, 512, JSON_THROW_ON_ERROR);
        self::assertSame([], $payload->NodeChecks);
        self::assertSame('my-session', $payload->Name);
        self::assertSame('10s', $payload->TTL);
        self::assertObjectNotHasProperty('Checks', $payload);
        self::assertObjectNotHasProperty('ServiceChecks', $payload);
    }
}
