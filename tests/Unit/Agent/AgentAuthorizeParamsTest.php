<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentAuthorizeParams;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentAuthorizeParamsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new AgentAuthorizeParams();
        self::assertSame('', $p->getTarget());
        self::assertSame('', $p->getClientCertURI());
        self::assertSame('', $p->getClientCertSerial());
    }

    public function testConstructorWithParams(): void
    {
        $p = new AgentAuthorizeParams(
            Target: 'web',
            ClientCertURI: 'spiffe://dc1/ns/default/svc/web',
            ClientCertSerial: '1234',
        );
        self::assertSame('web', $p->getTarget());
        self::assertSame('web', $p->Target);
        self::assertSame('spiffe://dc1/ns/default/svc/web', $p->getClientCertURI());
        self::assertSame('1234', $p->getClientCertSerial());
    }

    public function testFluentSetters(): void
    {
        $p = new AgentAuthorizeParams();
        $result = $p->setTarget('t')->setClientCertURI('u')->setClientCertSerial('s');
        self::assertSame($p, $result);
        self::assertSame('t', $p->getTarget());
        self::assertSame('u', $p->getClientCertURI());
        self::assertSame('s', $p->getClientCertSerial());
    }

    public function testJsonSerialize(): void
    {
        $p = new AgentAuthorizeParams(Target: 'web', ClientCertURI: 'uri', ClientCertSerial: 'ser');
        $out = $p->jsonSerialize();
        self::assertSame('web', $out->Target);
        self::assertSame('uri', $out->ClientCertURI);
        self::assertSame('ser', $out->ClientCertSerial);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Target = 'api';
        $d->ClientCertURI = 'spiffe://test';
        $d->ClientCertSerial = '5678';
        $p = AgentAuthorizeParams::jsonUnserialize($d);
        self::assertSame('api', $p->getTarget());
        self::assertSame('spiffe://test', $p->getClientCertURI());
        self::assertSame('5678', $p->getClientCertSerial());
    }
}

