<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceSplitTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ServiceSplit();
        self::assertSame(0.0, $s->getWeight());
        self::assertSame('', $s->getService());
        self::assertSame('', $s->getServiceSubset());
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getPartition());
        self::assertNull($s->getRequestHeaders());
        self::assertNull($s->getResponseHeaders());
    }

    public function testConstructorWithParams(): void
    {
        $s = new ServiceSplit(
            Weight: 50.0,
            Service: 'web-v2',
            ServiceSubset: 'v2',
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame(50.0, $s->getWeight());
        self::assertSame('web-v2', $s->getService());
        self::assertSame('v2', $s->getServiceSubset());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('pt', $s->getPartition());
    }

    public function testFluentSetters(): void
    {
        $s = new ServiceSplit();
        $result = $s->setWeight(75.0)
            ->setService('api')
            ->setServiceSubset('v1')
            ->setNamespace('ns')
            ->setPartition('pt');
        self::assertSame($s, $result);
        self::assertSame(75.0, $s->getWeight());
        self::assertSame('api', $s->getService());
        self::assertSame('v1', $s->getServiceSubset());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('pt', $s->getPartition());
    }

}
