<?php

namespace DCarbone\PHPConsulAPITests\Unit\Event;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Event\EventClient;
use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class EventClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param Response[] $responses
     */
    private function mockClient(array $responses): EventClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new EventClient(new Config(HttpClient: $httpClient));
    }

    public function testFireSetsFiltersAndContentType(): void
    {
        $client = $this->mockClient(
            [
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            'ID' => 'evt-1',
                            'Name' => 'deploy',
                            'Payload' => 'payload-data',
                            'NodeFilter' => 'node-*',
                            'ServiceFilter' => 'api',
                            'TagFilter' => 'v1',
                            'Version' => 1,
                            'LTime' => 42,
                        ],
                        JSON_THROW_ON_ERROR
                    )
                ),
            ]
        );

        $response = $client->Fire(
            new UserEvent(
                Name: 'deploy',
                Payload: 'payload-data',
                NodeFilter: 'node-*',
                ServiceFilter: 'api',
                TagFilter: 'v1',
            )
        );

        self::assertNull($response->Err);
        self::assertNotNull($response->UserEvent);
        self::assertSame('evt-1', $response->UserEvent->ID);
        self::assertSame('/v1/event/fire/deploy', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('node=node-%2A&service=api&tag=v1', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('application/octet-stream', $this->history[0]['request']->getHeaderLine('Content-Type'));
    }

    public function testListSetsNameFilterWhenProvided(): void
    {
        $client = $this->mockClient([new Response(200, [], json_encode([], JSON_THROW_ON_ERROR))]);

        $response = $client->List(name: 'deploy', opts: new QueryOptions(Pretty: true));

        self::assertNull($response->Err);
        self::assertSame('/v1/event/list', $this->history[0]['request']->getUri()->getPath());
        $query = $this->history[0]['request']->getUri()->getQuery();
        self::assertStringContainsString('name=deploy', $query);
        self::assertStringContainsString('pretty', $query);
    }

    public function testIDToIndexUsesHexDecoding(): void
    {
        $client = $this->mockClient([]);

        $index = $client->IDToIndex('12345678-9012-3456-7890-123456789012');

        self::assertSame(7684341960737989700, $index);
    }

    public function testIDToIndexParsesHexCharactersCorrectly(): void
    {
        $client = $this->mockClient([]);

        $index = $client->IDToIndex('abcdefab-cdef-abcd-efab-cdefabcdefab');

        self::assertSame(4928664519370228838, $index);
    }

    public function testIDToIndexRejectsInvalidUUID(): void
    {
        $client = $this->mockClient([]);
        $this->expectException(\InvalidArgumentException::class);
        $client->IDToIndex('not-a-uuid');
    }

    public function testIDToIndexHandlesSignBitDeterministically(): void
    {
        if (8 > PHP_INT_SIZE) {
            self::markTestSkipped('Requires 64-bit PHP integer support.');
        }

        $client = $this->mockClient([]);
        $index = $client->IDToIndex('80000000-0000-0000-0000-000000000000');
        self::assertSame(PHP_INT_MIN, $index);
    }
}
