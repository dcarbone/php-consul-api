<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\KubernetesAuthMethodConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KubernetesAuthMethodConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new KubernetesAuthMethodConfig();
        self::assertSame('', $c->getHost());
        self::assertSame('', $c->getCACert());
        self::assertSame('', $c->getServiceAccountJWT());
    }

    public function testConstructorWithParams(): void
    {
        $c = new KubernetesAuthMethodConfig(Host: 'https://k8s', CACert: 'cert', ServiceAccountJWT: 'jwt');
        self::assertSame('https://k8s', $c->getHost());
        self::assertSame('cert', $c->getCACert());
        self::assertSame('jwt', $c->getServiceAccountJWT());
    }

    public function testFluentSetters(): void
    {
        $c = new KubernetesAuthMethodConfig();
        $result = $c->setHost('h')->setCACert('c')->setServiceAccountJWT('j');
        self::assertSame($c, $result);
    }

    public function testRenderToConfig(): void
    {
        $c = new KubernetesAuthMethodConfig(Host: 'h', CACert: 'c', ServiceAccountJWT: 'j');
        $config = $c->RenderToConfig();
        self::assertSame(['Host' => 'h', 'CACert' => 'c', 'ServiceAccountJWT' => 'j'], $config);
    }

    public function testJsonSerializeOmitsEmptyFields(): void
    {
        $c = new KubernetesAuthMethodConfig();
        $out = $c->jsonSerialize();
        self::assertObjectNotHasProperty('Host', $out);
        self::assertObjectNotHasProperty('CACert', $out);
        self::assertObjectNotHasProperty('ServiceAccountJWT', $out);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Host = 'https://k8s';
        $d->CACert = 'cert';
        $d->ServiceAccountJWT = 'jwt';
        $c = KubernetesAuthMethodConfig::jsonUnserialize($d);
        self::assertSame('https://k8s', $c->getHost());
    }
}

