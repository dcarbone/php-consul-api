<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ConnectProxyConfig;
use DCarbone\PHPConsulAPI\Agent\Upstream;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConnectProxyConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new ConnectProxyConfig();
        self::assertSame('', $c->getProxyServiceID());
        self::assertSame('', $c->getTargetServiceID());
        self::assertSame('', $c->getTargetServiceName());
        self::assertSame('', $c->getContentHash());
        self::assertSame([], $c->getConfig());
        self::assertSame([], $c->getUpstreams());
    }

    public function testConstructorWithParams(): void
    {
        $u = new Upstream(DestinationName: 'db', LocalBindPort: 5432);
        $c = new ConnectProxyConfig(
            ProxyServiceID: 'proxy-1',
            TargetServiceID: 'web-1',
            TargetServiceName: 'web',
            ContentHash: 'hash',
            Config: ['protocol' => 'http'],
            Upstreams: [$u],
        );
        self::assertSame('proxy-1', $c->getProxyServiceID());
        self::assertSame('web', $c->getTargetServiceName());
        self::assertSame(['protocol' => 'http'], $c->getConfig());
        self::assertCount(1, $c->getUpstreams());
    }

    public function testFluentSetters(): void
    {
        $c = new ConnectProxyConfig();
        $result = $c->setProxyServiceID('p')->setTargetServiceID('t')
            ->setTargetServiceName('n')->setContentHash('h')
            ->setConfig(['k' => 'v'])->setUpstreams();
        self::assertSame($c, $result);
        self::assertSame('p', $c->getProxyServiceID());
    }
}

