<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\LoadBalancer;
use DCarbone\PHPConsulAPI\ConfigEntry\RingHashConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\LeastRequestConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class LoadBalancerTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new LoadBalancer();
        self::assertSame('', $l->getPolicy());
        self::assertNull($l->getRingHashConfig());
        self::assertNull($l->getLeastRequestConfig());
    }

    public function testConstructorWithParams(): void
    {
        $rh = new RingHashConfig(MinimumRingSize: 1024);
        $lr = new LeastRequestConfig(ChoiceCount: 5);
        $l = new LoadBalancer(
            Policy: 'ring_hash',
            RingHashConfig: $rh,
            LeastRequestConfig: $lr,
        );
        self::assertSame('ring_hash', $l->getPolicy());
        self::assertSame($rh, $l->getRingHashConfig());
        self::assertSame($lr, $l->getLeastRequestConfig());
    }

    public function testFluentSetters(): void
    {
        $rh = new RingHashConfig(MinimumRingSize: 512);
        $l = new LoadBalancer();
        $result = $l->setPolicy('ring_hash')
            ->setRingHashConfig($rh);
        self::assertSame($l, $result);
        self::assertSame('ring_hash', $l->getPolicy());
        self::assertSame($rh, $l->getRingHashConfig());
    }

}
