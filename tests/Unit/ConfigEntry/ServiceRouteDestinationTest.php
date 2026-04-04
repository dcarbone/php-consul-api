<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteDestination;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteDestinationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $d = new ServiceRouteDestination();
        self::assertSame('', $d->getService());
        self::assertSame('', $d->getServiceSubset());
        self::assertSame('', $d->getNamespace());
        self::assertSame('', $d->getPrefixRewrite());
        self::assertSame(0, $d->getNumRetries());
        self::assertFalse($d->isRetryOnConnectFailure());
        self::assertSame([], $d->getRetryOnStatusCodes());
        self::assertSame([], $d->getRetryOn());
    }

    public function testConstructorWithParams(): void
    {
        $d = new ServiceRouteDestination(
            Service: 'web-v2',
            ServiceSubset: 'v2',
            Namespace: 'ns',
            PrefixRewrite: '/v2',
            NumRetries: 3,
            RetryOnConnectFailure: true,
            RetryOnStatusCodes: [503, 504],
            RetryOn: ['5xx'],
        );
        self::assertSame('web-v2', $d->getService());
        self::assertSame('v2', $d->getServiceSubset());
        self::assertSame('ns', $d->getNamespace());
        self::assertSame('/v2', $d->getPrefixRewrite());
        self::assertSame(3, $d->getNumRetries());
        self::assertTrue($d->isRetryOnConnectFailure());
        self::assertSame([503, 504], $d->getRetryOnStatusCodes());
        self::assertSame(['5xx'], $d->getRetryOn());
    }

    public function testFluentSetters(): void
    {
        $d = new ServiceRouteDestination();
        $result = $d->setService('web')
            ->setServiceSubset('v1')
            ->setNamespace('ns')
            ->setPrefixRewrite('/v1')
            ->setNumRetries(2)
            ->setRetryOnConnectFailure(true)
            ->setRetryOnStatusCodes(502, 503)
            ->setRetryOn('5xx');
        self::assertSame($d, $result);
        self::assertSame('web', $d->getService());
        self::assertSame('v1', $d->getServiceSubset());
        self::assertSame('/v1', $d->getPrefixRewrite());
        self::assertSame(2, $d->getNumRetries());
        self::assertTrue($d->isRetryOnConnectFailure());
        self::assertSame([502, 503], $d->getRetryOnStatusCodes());
        self::assertSame(['5xx'], $d->getRetryOn());
    }

}
