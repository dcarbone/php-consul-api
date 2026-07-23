<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration;
use DCarbone\PHPConsulAPI\Operator\OperatorClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OperatorClientParityTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    private array $history = [];

    /**
     * @param Response[] $responses
     */
    private function mockClient(array $responses): OperatorClient
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));
        $httpClient = new Client(['handler' => $stack]);
        return new OperatorClient(new Config(HttpClient: $httpClient));
    }

    public function testRaftLeaderTransferAndRemovePeerByID(): void
    {
        $client = $this->mockClient([
            new Response(200, [], json_encode(['Success' => true], JSON_THROW_ON_ERROR)),
            new Response(200, [], ''),
        ]);

        $transfer = $client->RaftLeaderTransfer('server-1');
        $removeErr = $client->RaftRemovePeerByID('server-2');

        self::assertNull($transfer->Err);
        self::assertTrue($transfer->Value);
        self::assertNull($removeErr);
        self::assertSame('/v1/operator/raft/transfer-leader', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('id=server-1', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('/v1/operator/raft/peer', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('id=server-2', $this->history[1]['request']->getUri()->getQuery());
    }

    public function testKeyringMethodsAndList(): void
    {
        $client = $this->mockClient([
            new Response(200, [], ''),
            new Response(200, [], ''),
            new Response(200, [], ''),
            new Response(
                200,
                [],
                json_encode(
                    [
                        [
                            'WAN' => false,
                            'Datacenter' => 'dc1',
                            'Keys' => ['abc' => 1],
                            'PrimaryKeys' => ['abc' => 1],
                            'NumNodes' => 1,
                        ],
                    ],
                    JSON_THROW_ON_ERROR
                )
            ),
        ]);

        self::assertNull($client->KeyringInstall('abc'));
        self::assertNull($client->KeyringUse('abc'));
        self::assertNull($client->KeyringRemove('abc'));
        $list = $client->KeyringList();

        self::assertNull($list->Err);
        self::assertCount(1, $list->Keyrings);
        self::assertSame('/v1/operator/keyring', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('POST', $this->history[0]['request']->getMethod());
        self::assertSame('PUT', $this->history[1]['request']->getMethod());
        self::assertSame('DELETE', $this->history[2]['request']->getMethod());
        self::assertSame('GET', $this->history[3]['request']->getMethod());
    }

    public function testSegmentListAndUsage(): void
    {
        $client = $this->mockClient([
            new Response(200, [], json_encode(['seg-a', 'seg-b'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['Usage' => ['dc1' => ['Nodes' => 1]]], JSON_THROW_ON_ERROR)),
        ]);

        $segments = $client->SegmentList(new QueryOptions(Pretty: true));
        $usage = $client->Usage();

        self::assertNull($segments->Err);
        self::assertSame(['seg-a', 'seg-b'], $segments->Value);
        self::assertNull($usage->Err);
        self::assertIsArray($usage->Usage);
        self::assertArrayHasKey('Usage', $usage->Usage);
        self::assertSame('/v1/operator/segment', $this->history[0]['request']->getUri()->getPath());
        self::assertStringContainsString('pretty', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('/v1/operator/usage', $this->history[1]['request']->getUri()->getPath());
    }

    public function testAuditHashAndLicenseEndpoints(): void
    {
        $client = $this->mockClient([
            new Response(200, [], json_encode(['Hash' => 'hash-value'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['Valid' => true], JSON_THROW_ON_ERROR)),
            new Response(200, [], 'signed-license'),
            new Response(200, [], json_encode(['Valid' => true], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['Valid' => false], JSON_THROW_ON_ERROR)),
        ]);

        $hash = $client->AuditHash('payload');
        $license = $client->LicenseGet();
        $signed = $client->LicenseGetSigned();
        $put = $client->LicensePut('license-data');
        $reset = $client->LicenseReset();

        self::assertNull($hash->Err);
        self::assertSame('hash-value', $hash->Value);
        self::assertNull($license->Err);
        self::assertIsArray($license->Map);
        self::assertNull($signed->Err);
        self::assertSame('signed-license', $signed->Value);
        self::assertNull($put->Err);
        self::assertNull($reset->Err);
        self::assertSame('/v1/operator/audit-hash', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('/v1/operator/license', $this->history[1]['request']->getUri()->getPath());
        self::assertSame('/v1/operator/license', $this->history[2]['request']->getUri()->getPath());
        self::assertStringContainsString('signed=1', $this->history[2]['request']->getUri()->getQuery());
    }

    public function testAutopilotCASAddsModifyIndexAndHealthAccepts429(): void
    {
        $client = $this->mockClient([
            new Response(200, [], 'true'),
            new Response(429, [], json_encode(['Healthy' => false, 'FailureTolerance' => 0, 'Servers' => []], JSON_THROW_ON_ERROR)),
        ]);

        $ok = $client->AutopilotCASConfiguration(new AutopilotConfiguration(ModifyIndex: 123));
        $health = $client->AutopilotServerHealth();

        self::assertNull($ok->Err);
        self::assertTrue($ok->Value);
        self::assertNull($health->Err);
        self::assertSame('/v1/operator/autopilot/configuration', $this->history[0]['request']->getUri()->getPath());
        self::assertSame('cas=123', $this->history[0]['request']->getUri()->getQuery());
        self::assertSame('/v1/operator/autopilot/health', $this->history[1]['request']->getUri()->getPath());
    }
}
