<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplitterConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceSplitterConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ServiceSplitterConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame([], $e->getSplits());
    }

    public function testConstructorWithParams(): void
    {
        $split = new ServiceSplit(Weight: 100.0, Service: 'web');
        $e = new ServiceSplitterConfigEntry(
            Kind: 'service-splitter',
            Name: 'web',
            Partition: 'pt',
            Splits: [$split],
        );
        self::assertSame('service-splitter', $e->getKind());
        self::assertSame('web', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertCount(1, $e->getSplits());
    }

    public function testFluentSetters(): void
    {
        $split = new ServiceSplit(Weight: 100.0, Service: 'api');
        $e = new ServiceSplitterConfigEntry();
        $result = $e->setKind('service-splitter')
            ->setName('api')
            ->setPartition('pt')
            ->setSplits($split);
        self::assertSame($e, $result);
        self::assertSame('service-splitter', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertCount(1, $e->getSplits());
    }

}
