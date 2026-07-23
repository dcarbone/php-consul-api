<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLAuthMethodQueryResponse;
use DCarbone\PHPConsulAPI\ACL\ACLBindingRulesQueryResponse;
use DCarbone\PHPConsulAPI\ACL\ACLClient;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ACLClientTest extends TestCase
{
    private function mockClient(int $statusCode, string $body, array &$history): ACLClient
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response($statusCode, [], $body)]));
        $stack->push(Middleware::history($history));
        $httpClient = new Client(['handler' => $stack]);
        return new ACLClient(new Config(HttpClient: $httpClient));
    }

    public function testAuthMethodReadUsesKebabCasePath(): void
    {
        $history = [];
        $client = $this->mockClient(200, '{}', $history);

        $response = $client->AuthMethodRead('my-auth-method');

        self::assertInstanceOf(ACLAuthMethodQueryResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('/v1/acl/auth-method/my-auth-method', $history[0]['request']->getUri()->getPath());
    }

    public function testAuthMethodDeleteUsesKebabCasePath(): void
    {
        $history = [];
        $client = $this->mockClient(200, '', $history);

        $response = $client->AuthMethodDelete('my-auth-method');

        self::assertInstanceOf(WriteResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('DELETE', $history[0]['request']->getMethod());
        self::assertSame('/v1/acl/auth-method/my-auth-method', $history[0]['request']->getUri()->getPath());
    }

    public function testBindingRuleListSupportsMethodNameFilter(): void
    {
        $history = [];
        $client = $this->mockClient(200, '[]', $history);

        $response = $client->BindingRuleList('oidc-auth-method');

        self::assertInstanceOf(ACLBindingRulesQueryResponse::class, $response);
        self::assertCount(1, $history);
        self::assertSame('/v1/acl/binding-rules', $history[0]['request']->getUri()->getPath());

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('oidc-auth-method', $query['authmethod']);
    }

    public function testBindingRuleListSupportsLegacySecondArgumentOptions(): void
    {
        $history = [];
        $client = $this->mockClient(200, '[]', $history);

        $response = $client->BindingRuleList(new QueryOptions(Pretty: true));

        self::assertInstanceOf(ACLBindingRulesQueryResponse::class, $response);
        self::assertCount(1, $history);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayNotHasKey('authmethod', $query);
        self::assertArrayHasKey('pretty', $query);
    }

    public function testBindingRuleListSupportsLegacyNullFirstArgument(): void
    {
        $history = [];
        $client = $this->mockClient(200, '[]', $history);

        $response = $client->BindingRuleList(null);

        self::assertInstanceOf(ACLBindingRulesQueryResponse::class, $response);
        self::assertCount(1, $history);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertArrayNotHasKey('authmethod', $query);
    }

    public function testRulesTranslateReturnsDeprecatedErrorWithoutRequest(): void
    {
        $history = [];
        $client = $this->mockClient(200, '', $history);

        $response = $client->RulesTranslate('node_prefix "" { policy = "read" }');

        self::assertInstanceOf(ValuedWriteStringResponse::class, $response);
        self::assertNotNull($response->Err);
        self::assertStringContainsString('deprecated', (string)$response->Err);
        self::assertCount(0, $history);
    }

    public function testRulesTranslateTokenReturnsDeprecatedErrorWithoutRequest(): void
    {
        $history = [];
        $client = $this->mockClient(200, '', $history);

        $response = $client->RulesTranslateToken('legacy-token-id');

        self::assertInstanceOf(ValuedWriteStringResponse::class, $response);
        self::assertNotNull($response->Err);
        self::assertStringContainsString('deprecated', (string)$response->Err);
        self::assertCount(0, $history);
    }
}
