<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntriesQueryResponse;
use DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntryClient;
use DCarbone\PHPConsulAPI\ConfigEntry\ConfigEntryQueryResponse;
use DCarbone\PHPConsulAPI\ConfigEntry\GenericConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceConfigEntry;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ConfigEntryClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param array<Response> $responses
     */
    private function mockClient(array $responses): ConfigEntryClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new ConfigEntryClient(new Config(HttpClient: $httpClient));
    }

    public function testGetReturnsTypedConfigEntry(): void
    {
        $client = $this->mockClient(
            [
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'Kind' => 'service-defaults',
                            'Name' => 'web',
                            'Protocol' => 'http',
                        ],
                        JSON_THROW_ON_ERROR
                    )
                ),
            ]
        );

        $response = $client->Get('service-defaults', 'web');

        self::assertInstanceOf(ConfigEntryQueryResponse::class, $response);
        self::assertNull($response->Err);
        self::assertInstanceOf(ServiceConfigEntry::class, $response->Entry);
        self::assertSame('/v1/config/service-defaults/web', $this->history[0]['request']->getUri()->getPath());
    }

    public function testListDecodesUnknownKindAsGeneric(): void
    {
        $client = $this->mockClient(
            [
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            [
                                'Kind' => 'service-defaults',
                                'Name' => 'api',
                            ],
                            [
                                'Kind' => 'http-route',
                                'Name' => 'route-1',
                                'Parents' => [],
                            ],
                        ],
                        JSON_THROW_ON_ERROR
                    )
                ),
            ]
        );

        $response = $client->List('service-defaults', new QueryOptions(Pretty: true));

        self::assertInstanceOf(ConfigEntriesQueryResponse::class, $response);
        self::assertNull($response->Err);
        self::assertCount(2, $response->Entries);
        self::assertInstanceOf(ServiceConfigEntry::class, $response->Entries[0]);
        self::assertInstanceOf(GenericConfigEntry::class, $response->Entries[1]);
        self::assertSame('/v1/config/service-defaults', $this->history[0]['request']->getUri()->getPath());
        self::assertStringContainsString('pretty', $this->history[0]['request']->getUri()->getQuery());
    }

    public function testSetAndCASCallExpectedEndpoint(): void
    {
        $client = $this->mockClient(
            [
                new Response(200, [], 'true'),
                new Response(200, [], 'false'),
            ]
        );

        $entry = new ServiceConfigEntry(Kind: 'service-defaults', Name: 'web');

        $setResponse = $client->Set($entry);
        $casResponse = $client->CAS($entry, 42);

        self::assertInstanceOf(ValuedWriteBoolResponse::class, $setResponse);
        self::assertTrue($setResponse->Value);
        self::assertInstanceOf(ValuedWriteBoolResponse::class, $casResponse);
        self::assertFalse($casResponse->Value);
        self::assertSame('/v1/config', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('/v1/config', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('cas=42', $this->history[1]['request']->getUri()->getQuery());
    }

    public function testDeleteAndDeleteCASCallExpectedEndpoint(): void
    {
        $client = $this->mockClient(
            [
                new Response(200, [], 'true'),
                new Response(200, [], 'false'),
            ]
        );

        $deleteResponse = $client->Delete('service-defaults', 'web');
        $deleteCASResponse = $client->DeleteCAS('service-defaults', 'web', 99);

        self::assertInstanceOf(WriteResponse::class, $deleteResponse);
        self::assertInstanceOf(ValuedWriteBoolResponse::class, $deleteCASResponse);
        self::assertFalse($deleteCASResponse->Value);
        self::assertSame('/v1/config/service-defaults/web', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('/v1/config/service-defaults/web', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('cas=99', $this->history[1]['request']->getUri()->getQuery());
    }

    public function testGetAndListValidateRequiredInputs(): void
    {
        $client = $this->mockClient([]);

        $getResponse = $client->Get('', '');
        $listResponse = $client->List('');
        $deleteResponse = $client->Delete('', '');
        $deleteCASResponse = $client->DeleteCAS('', '', 1);

        self::assertNotNull($getResponse->Err);
        self::assertStringContainsString('must not be empty', $getResponse->Err->getMessage());
        self::assertNotNull($listResponse->Err);
        self::assertStringContainsString('must not be empty', $listResponse->Err->getMessage());
        self::assertNotNull($deleteResponse->Err);
        self::assertStringContainsString('must not be empty', $deleteResponse->Err->getMessage());
        self::assertNotNull($deleteCASResponse->Err);
        self::assertStringContainsString('must not be empty', $deleteCASResponse->Err->getMessage());
    }
}
