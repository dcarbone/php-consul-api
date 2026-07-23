<?php

namespace DCarbone\PHPConsulAPITests\Unit\Peering;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Peering\PeeringClient;
use DCarbone\PHPConsulAPI\Peering\PeeringEstablishRequest;
use DCarbone\PHPConsulAPI\Peering\PeeringGenerateTokenRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class PeeringClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): PeeringClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new PeeringClient(new Config(HttpClient: $httpClient));
    }

    public function testReadRejectsEmptyName(): void
    {
        $client = $this->mockClient([]);

        $resp = $client->Read('');

        self::assertNotNull($resp->Err);
        self::assertStringContainsString('cannot be empty', $resp->Err->getMessage());
    }

    public function testReadReturnsNullOnNotFound(): void
    {
        $client = $this->mockClient([new Response(404, [], '')]);

        $resp = $client->Read('peer-a');

        self::assertNull($resp->Err);
        self::assertNull($resp->Peering);
        self::assertSame('/v1/peering/peer-a', $this->history[0]['request']->getUri()->getPath());
    }

    public function testGenerateTokenParsesToken(): void
    {
        $client = $this->mockClient([new Response(200, [], json_encode(['PeeringToken' => 'tok-1'], JSON_THROW_ON_ERROR))]);

        $resp = $client->GenerateToken(new PeeringGenerateTokenRequest(PeerName: 'peer-a'));

        self::assertNull($resp->Err);
        self::assertSame('tok-1', $resp->Value);
        self::assertSame('/v1/peering/token', $this->history[0]['request']->getUri()->getPath());
    }

    public function testEstablishUsesExpectedEndpoint(): void
    {
        $client = $this->mockClient([new Response(200, [], '')]);

        $resp = $client->Establish(new PeeringEstablishRequest(PeerName: 'peer-a', PeeringToken: 'tok'));

        self::assertNull($resp->Err);
        self::assertSame('/v1/peering/establish', $this->history[0]['request']->getUri()->getPath());
    }

    public function testDeleteUsesExpectedEndpoint(): void
    {
        $client = $this->mockClient([new Response(200, [], '')]);

        $resp = $client->Delete('peer-a');

        self::assertNull($resp->Err);
        self::assertSame('/v1/peering/peer-a', $this->history[0]['request']->getUri()->getPath());
    }

    public function testListDecodesCollection(): void
    {
        $client = $this->mockClient([
            new Response(
                200,
                [],
                json_encode([['ID' => 'peer-id-1', 'Name' => 'peer-a']], JSON_THROW_ON_ERROR)
            ),
        ]);

        $resp = $client->List();

        self::assertNull($resp->Err);
        self::assertCount(1, $resp->Peerings);
        self::assertSame('peer-id-1', $resp->Peerings[0]->ID);
        self::assertSame('/v1/peerings', $this->history[0]['request']->getUri()->getPath());
    }
}
