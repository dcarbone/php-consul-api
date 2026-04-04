<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\CompoundServiceName;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CompoundServiceNameTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new CompoundServiceName();
        self::assertSame('', $c->getName());
        self::assertSame('', $c->Name);
        self::assertSame('', $c->getNamespace());
        self::assertSame('', $c->Namespace);
        self::assertSame('', $c->getPartition());
        self::assertSame('', $c->Partition);
    }

    public function testConstructorWithParams(): void
    {
        $c = new CompoundServiceName(Name: 'web', Namespace: 'ns', Partition: 'pt');
        self::assertSame('web', $c->getName());
        self::assertSame('ns', $c->getNamespace());
        self::assertSame('pt', $c->getPartition());
    }

    public function testFluentSetters(): void
    {
        $c = new CompoundServiceName();
        $result = $c->setName('n')->setNamespace('ns')->setPartition('pt');
        self::assertSame($c, $result);
        self::assertSame('n', $c->getName());
        self::assertSame('n', $c->Name);
        self::assertSame('ns', $c->getNamespace());
        self::assertSame('pt', $c->getPartition());
    }

    public function testJsonSerializeOmitsEmptyOptionalFields(): void
    {
        $c = new CompoundServiceName(Name: 'web');
        $out = $c->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('web', $out->Name);
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeWithOptionalFields(): void
    {
        $c = new CompoundServiceName(Name: 'web', Namespace: 'ns', Partition: 'pt');
        $out = $c->jsonSerialize();
        self::assertSame('ns', $out->Namespace);
        self::assertSame('pt', $out->Partition);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'web';
        $d->Namespace = 'ns';
        $d->Partition = 'pt';
        $c = CompoundServiceName::jsonUnserialize($d);
        self::assertInstanceOf(CompoundServiceName::class, $c);
        self::assertSame('web', $c->getName());
        self::assertSame('ns', $c->getNamespace());
        self::assertSame('pt', $c->getPartition());
    }
}

