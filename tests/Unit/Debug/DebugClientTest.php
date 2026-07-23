<?php

namespace DCarbone\PHPConsulAPITests\Unit\Debug;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Debug\DebugClient;
use DCarbone\PHPConsulAPI\PHPLib\ValuedStringResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class DebugClientTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param Response[] $responses
     */
    private function mockClient(array $responses): DebugClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new DebugClient(new Config(HttpClient: $httpClient));
    }

    public function testHeapReturnsRawProfileBody(): void
    {
        $client = $this->mockClient([new Response(200, [], 'heap-profile-data')]);

        $response = $client->Heap();

        self::assertInstanceOf(ValuedStringResponse::class, $response);
        self::assertNull($response->Err);
        self::assertSame('heap-profile-data', $response->Value);
        self::assertSame('/debug/pprof/heap', $this->history[0]['request']->getUri()->getPath());
    }

    public function testProfileTraceAndPProfSetSecondsParameter(): void
    {
        $client = $this->mockClient(
            [
                new Response(200, [], 'profile-data'),
                new Response(200, [], 'trace-data'),
                new Response(200, [], 'custom-data'),
            ]
        );

        $profile = $client->Profile(5);
        $trace = $client->Trace(6);
        $custom = $client->PProf('mutex', 7);

        self::assertSame('profile-data', $profile->Value);
        self::assertSame('trace-data', $trace->Value);
        self::assertSame('custom-data', $custom->Value);
        self::assertSame('/debug/pprof/profile', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('seconds=5', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('/debug/pprof/trace', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('seconds=6', $this->history[1]['request']->getUri()->getQuery());
        self::assertSame('/debug/pprof/mutex', $this->history[2]['request']->getUri()->getPath());
        self::assertSame('seconds=7', $this->history[2]['request']->getUri()->getQuery());
    }

    public function testGoroutineUsesExpectedEndpoint(): void
    {
        $client = $this->mockClient([new Response(200, [], 'goroutine-profile-data')]);

        $response = $client->Goroutine();

        self::assertNull($response->Err);
        self::assertSame('goroutine-profile-data', $response->Value);
        self::assertSame('/debug/pprof/goroutine', $this->history[0]['request']->getUri()->getPath());
    }
}
