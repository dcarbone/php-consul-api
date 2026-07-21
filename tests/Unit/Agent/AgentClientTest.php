<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentAuthorize;
use DCarbone\PHPConsulAPI\Agent\AgentAuthorizeParams;
use DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration;
use DCarbone\PHPConsulAPI\Agent\AgentCheckUpdate;
use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Agent\ForceLeaveOpts;
use DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\PHPLib\MapResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @internal
 */
final class AgentClientTest extends TestCase
{
    public static function aclTokenMethodProvider(): array
    {
        return [
            'legacy default' => ['UpdateACLToken', 'acl_token'],
            'legacy agent' => ['UpdateACLAgentToken', 'acl_agent_token'],
            'legacy master' => ['UpdateACLAgentMasterToken', 'acl_agent_master_token'],
            'legacy replication' => ['UpdateACLReplicationToken', 'acl_replication_token'],
            'default' => ['UpdateDefaultACLToken', 'default'],
            'agent' => ['UpdateAgentACLToken', 'agent'],
            'agent recovery' => ['UpdateAgentRecoveryACLToken', 'agent_recovery'],
            'agent master' => ['UpdateAgentMasterACLToken', 'agent_master'],
            'replication' => ['UpdateReplicationACLToken', 'replication'],
            'config file registration' => ['UpdateConfigFileRegistrationToken', 'config_file_service_registration'],
            'dns' => ['UpdateDNSToken', 'dns'],
        ];
    }

    public static function queryVariantProvider(): array
    {
        return [
            'checks with filter' => [
                'Checks',
                ['Status == "passing"', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'GET',
                '/v1/agent/checks',
                ['dc' => 'dc1', 'filter' => 'Status == "passing"', 'pretty' => ''],
                json_encode([
                    'check-1' => [
                        'Node' => 'node-1',
                        'CheckID' => 'check-1',
                        'Name' => 'check',
                        'Status' => 'passing',
                        'Notes' => '',
                        'Output' => '',
                        'ServiceID' => '',
                        'ServiceName' => '',
                        'Type' => '',
                        'Definition' => new \stdClass(),
                    ],
                ], JSON_THROW_ON_ERROR),
                null,
                [],
            ],
            'services with filter' => [
                'Services',
                ['Service == "web"', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'GET',
                '/v1/agent/services',
                ['dc' => 'dc1', 'filter' => 'Service == "web"', 'pretty' => ''],
                json_encode([
                    'web-1' => [
                        'ID' => 'web-1',
                        'Service' => 'web',
                        'Tags' => [],
                        'Meta' => new \stdClass(),
                        'Port' => 8080,
                        'Address' => '127.0.0.1',
                        'Weights' => ['Passing' => 1, 'Warning' => 1],
                    ],
                ], JSON_THROW_ON_ERROR),
                null,
                [],
            ],
            'health by id' => [
                'AgentHealthServiceByID',
                ['svc-1', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'GET',
                '/v1/agent/health/service/id/svc-1',
                ['dc' => 'dc1', 'format' => 'json', 'pretty' => ''],
                json_encode([
                    'AggregatedStatus' => 'passing',
                    'Service' => [
                        'ID' => 'svc-1',
                        'Service' => 'web',
                        'Tags' => [],
                        'Meta' => new \stdClass(),
                        'Port' => 8080,
                        'Address' => '127.0.0.1',
                        'Weights' => ['Passing' => 1, 'Warning' => 1],
                    ],
                    'Checks' => [],
                ], JSON_THROW_ON_ERROR),
                null,
                [],
            ],
            'health by name' => [
                'AgentHealthServiceByName',
                ['web', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'GET',
                '/v1/agent/health/service/name/web',
                ['dc' => 'dc1', 'format' => 'json', 'pretty' => ''],
                json_encode([
                    [
                        'AggregatedStatus' => 'passing',
                        'Service' => [
                            'ID' => 'svc-1',
                            'Service' => 'web',
                            'Tags' => [],
                            'Meta' => new \stdClass(),
                            'Port' => 8080,
                            'Address' => '127.0.0.1',
                            'Weights' => ['Passing' => 1, 'Warning' => 1],
                        ],
                        'Checks' => [],
                    ],
                ], JSON_THROW_ON_ERROR),
                null,
                [],
            ],
            'service deregister' => [
                'ServiceDeregister',
                ['svc-1', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/service/deregister/svc-1',
                ['dc' => 'dc1', 'pretty' => ''],
                '',
                null,
                [],
            ],
            'update ttl' => [
                'UpdateTTL',
                ['check-1', 'ok', 'pass', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/check/update/check-1',
                ['dc' => 'dc1', 'pretty' => ''],
                '',
                AgentCheckUpdate::class,
                ['Status' => 'passing', 'Output' => 'ok'],
            ],
            'check register' => [
                'CheckRegister',
                [new AgentCheckRegistration(ID: 'check-1', Name: 'check-1', TTL: '30s'), new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/check/register',
                ['dc' => 'dc1', 'pretty' => ''],
                '',
                AgentCheckRegistration::class,
                ['ID' => 'check-1', 'Name' => 'check-1', 'TTL' => '30s'],
            ],
            'check deregister' => [
                'CheckDeregister',
                ['check-1', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/check/deregister/check-1',
                ['dc' => 'dc1', 'pretty' => ''],
                '',
                null,
                [],
            ],
            'enable service maintenance' => [
                'EnableServiceMaintenance',
                ['svc-1', 'maintenance', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/service/maintenance/svc-1',
                ['dc' => 'dc1', 'enable' => 'true', 'pretty' => '', 'reason' => 'maintenance'],
                '',
                null,
                [],
            ],
            'disable service maintenance' => [
                'DisableServiceMaintenance',
                ['svc-1', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/service/maintenance/svc-1',
                ['dc' => 'dc1', 'enable' => 'false', 'pretty' => ''],
                '',
                null,
                [],
            ],
            'enable node maintenance' => [
                'EnableNodeMaintenance',
                ['maintenance', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/maintenance',
                ['dc' => 'dc1', 'enable' => 'true', 'pretty' => '', 'reason' => 'maintenance'],
                '',
                null,
                [],
            ],
            'disable node maintenance' => [
                'DisableNodeMaintenance',
                [new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/maintenance',
                ['dc' => 'dc1', 'enable' => 'false', 'pretty' => ''],
                '',
                null,
                [],
            ],
            'force leave with opts' => [
                'ForceLeave',
                ['node-1', new ForceLeaveOpts(Prune: true, WAN: true), new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true)],
                'PUT',
                '/v1/agent/force-leave/node-1',
                ['dc' => 'dc1', 'pretty' => '', 'prune' => '1', 'wan' => '1'],
                '',
                null,
                [],
            ],
        ];
    }

    #[DataProvider('aclTokenMethodProvider')]
    public function testACLTokenMethodsConstructExpectedRequests(string $method, string $target): void
    {
        $history = [];
        $client = $this->newClient([new Response(200, [], '')], $history);

        $err = $client->{$method}('secret-token', new WriteOptions(Datacenter: 'dc1', Token: 'write-token'));

        self::assertNull($err);
        [$request, , $params] = $this->requestData($history);
        self::assertSame('PUT', $request->getMethod());
        self::assertSame('/v1/agent/token/' . $target, $request->getUri()->getPath());
        self::assertSame(['dc' => 'dc1'], $params);
        self::assertSame('write-token', $request->getHeaderLine('X-Consul-Token'));
        $body = json_decode((string)$request->getBody());
        self::assertInstanceOf(\stdClass::class, $body);
        self::assertSame('secret-token', $body->Token);
    }

    public function testConnectAuthorizeConstructsRequestAndUnmarshalsResponse(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(200, [], json_encode(['Authorized' => true, 'Reason' => 'ok'], JSON_THROW_ON_ERROR)),
        ], $history);

        $response = $client->ConnectAuthorize(new AgentAuthorizeParams(Target: 'web', ClientCertURI: 'spiffe://web', ClientCertSerial: '1234'));

        self::assertNull($response->Err);
        self::assertInstanceOf(AgentAuthorize::class, $response->Authorize);
        self::assertTrue($response->Authorize->Authorized);
        self::assertSame('ok', $response->Authorize->Reason);
        [$request] = $this->requestData($history);
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v1/agent/connect/authorize', $request->getUri()->getPath());
        $body = json_decode((string)$request->getBody());
        self::assertInstanceOf(\stdClass::class, $body);
        self::assertSame('web', $body->Target);
        self::assertSame('spiffe://web', $body->ClientCertURI);
    }

    public function testConnectCARootsConstructsRequestAndUnmarshalsResponse(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(
                200,
                ['X-Consul-Index' => '7'],
                json_encode([
                    'ActiveRootID' => 'root-1',
                    'TrustDomain' => 'consul',
                    'Roots' => [[
                        'ID' => 'root-1',
                        'Name' => 'root',
                        'RootCert' => 'pem',
                        'Active' => true,
                        'DaysRemaining' => 365,
                        'CreateIndex' => 1,
                        'ModifyIndex' => 2,
                    ]],
                ], JSON_THROW_ON_ERROR),
            ),
        ], $history);

        $response = $client->ConnectCARoots(new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true));

        self::assertNull($response->Err);
        self::assertNotNull($response->QueryMeta);
        self::assertSame(7, $response->QueryMeta->LastIndex);
        self::assertSame('root-1', $response->CARoots->ActiveRootID);
        self::assertCount(1, $response->CARoots->Roots);
        self::assertSame('root-1', $response->CARoots->Roots[0]->ID);
        [$request, , $params] = $this->requestData($history);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/agent/connect/ca/roots', $request->getUri()->getPath());
        self::assertSame(['dc' => 'dc1', 'pretty' => ''], $params);
        self::assertSame('query-token', $request->getHeaderLine('X-Consul-Token'));
    }

    public function testConnectCALeafConstructsRequestAndUnmarshalsResponse(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(
                200,
                ['X-Consul-Index' => '8'],
                json_encode([
                    'SerialNumber' => '1234',
                    'CertPEM' => 'cert',
                    'PrivateKeyPEM' => 'key',
                    'Service' => 'web',
                    'ServiceURI' => 'spiffe://web',
                    'CreateIndex' => 1,
                    'ModifyIndex' => 2,
                ], JSON_THROW_ON_ERROR),
            ),
        ], $history);

        $response = $client->ConnectCALeaf('svc-1', new QueryOptions(Datacenter: 'dc1', Token: 'query-token', Pretty: true));

        self::assertNull($response->Err);
        self::assertNotNull($response->QueryMeta);
        self::assertSame(8, $response->QueryMeta->LastIndex);
        self::assertSame('1234', $response->LeafCert->SerialNumber);
        self::assertSame('web', $response->LeafCert->Service);
        [$request, , $params] = $this->requestData($history);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/agent/connect/ca/leaf/svc-1', $request->getUri()->getPath());
        self::assertSame(['dc' => 'dc1', 'pretty' => ''], $params);
        self::assertSame('query-token', $request->getHeaderLine('X-Consul-Token'));
    }

    public function testVersionConstructsRequestAndUnmarshalsResponse(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(200, [], json_encode(['Version' => '1.20.0', 'Revision' => 'abc'], JSON_THROW_ON_ERROR)),
        ], $history);

        $response = $client->Version();

        self::assertInstanceOf(MapResponse::class, $response);
        self::assertNull($response->Err);
        self::assertSame('1.20.0', $response->Map['Version']);
        [$request] = $this->requestData($history);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/agent/version', $request->getUri()->getPath());
    }

    #[DataProvider('queryVariantProvider')]
    public function testQueryOptionVariantsConstructExpectedRequests(
        string $method,
        array $args,
        string $expectedMethod,
        string $expectedPath,
        array $expectedParams,
        string $responseBody,
        null|string $expectedJsonClass,
        array $expectedJsonFields,
    ): void {
        $history = [];
        $client = $this->newClient([new Response(200, [], $responseBody)], $history);

        $client->{$method}(...$args);
        [$request, , $params] = $this->requestData($history);
        self::assertSame($expectedMethod, $request->getMethod());
        self::assertSame($expectedPath, $request->getUri()->getPath());
        self::assertSame($expectedParams, $params);
        self::assertSame('query-token', $request->getHeaderLine('X-Consul-Token'));
        if (null !== $expectedJsonClass) {
            $body = json_decode((string)$request->getBody());
            self::assertInstanceOf(\stdClass::class, $body);
            foreach ($expectedJsonFields as $field => $expectedValue) {
                self::assertSame($expectedValue, $body->{$field});
            }
        } else {
            self::assertSame('', (string)$request->getBody());
        }
    }

    public function testBaseMethodsWithoutQueryOptionsWorkCorrectly(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
        ], $history);

        $client->Checks();
        self::assertSame('/v1/agent/checks', $history[0]['request']->getUri()->getPath());
        self::assertSame('GET', $history[0]['request']->getMethod());

        $client->Services();
        self::assertSame('/v1/agent/services', $history[1]['request']->getUri()->getPath());
        self::assertSame('GET', $history[1]['request']->getMethod());

        $err = $client->ServiceDeregister('svc-1');
        self::assertNull($err);
        self::assertSame('/v1/agent/service/deregister/svc-1', $history[2]['request']->getUri()->getPath());
        self::assertSame('PUT', $history[2]['request']->getMethod());

        $err = $client->CheckDeregister('check-1');
        self::assertNull($err);
        self::assertSame('/v1/agent/check/deregister/check-1', $history[3]['request']->getUri()->getPath());
        self::assertSame('PUT', $history[3]['request']->getMethod());

        $err = $client->ForceLeave('node-1');
        self::assertNull($err);
        self::assertSame('/v1/agent/force-leave/node-1', $history[4]['request']->getUri()->getPath());
        self::assertSame('PUT', $history[4]['request']->getMethod());

        $err = $client->ForceLeavePrune('node-1');
        self::assertNull($err);
        self::assertSame('/v1/agent/force-leave/node-1', $history[5]['request']->getUri()->getPath());
        parse_str($history[5]['request']->getUri()->getQuery(), $params);
        self::assertArrayHasKey('prune', $params);
        self::assertSame('1', $params['prune']);
    }

    public function testMembersWanConstructsExpectedRequest(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(200, [], json_encode([], JSON_THROW_ON_ERROR)),
        ], $history);

        $client->Members(true);

        [$request, , $params] = $this->requestData($history);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/v1/agent/members', $request->getUri()->getPath());
        self::assertSame(['wan' => '1'], $params);
    }

    public function testServiceRegisterOptsUsesRegisterTokenHeader(): void
    {
        $history = [];
        $client = $this->newClient([
            new Response(200, [], ''),
        ], $history);

        $err = $client->ServiceRegisterOpts(
            new AgentServiceRegistration(Name: 'web', Address: '127.0.0.1', Port: 8080),
            new ServiceRegisterOpts(ReplaceExistingChecks: true, Token: 'register-token'),
        );

        self::assertNull($err);
        [$request, , $params] = $this->requestData($history);
        self::assertSame('PUT', $request->getMethod());
        self::assertSame('/v1/agent/service/register', $request->getUri()->getPath());
        self::assertSame(['replace-existing-checks' => 'true'], $params);
        self::assertSame('register-token', $request->getHeaderLine('X-Consul-Token'));
    }

    private function newClient(array $responses, array &$history): AgentClient
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));

        return new AgentClient(new Config(HttpClient: new Client(['handler' => $stack])));
    }

    /**
     * @return array{0: RequestInterface, 1: array<string,mixed>, 2: array<string,string>}
     */
    private function requestData(array $history): array
    {
        self::assertCount(1, $history);
        $request = $history[0]['request'];
        $options = $history[0]['options'];
        parse_str($request->getUri()->getQuery(), $params);
        ksort($params);
        return [$request, $options, $params];
    }
}
