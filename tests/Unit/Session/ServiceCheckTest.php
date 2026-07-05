<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Session;

use DCarbone\PHPConsulAPI\Session\ServiceCheck;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceCheckTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $sc = new ServiceCheck();
        self::assertSame('', $sc->getID());
        self::assertSame('', $sc->getNamespace());
    }

    public function testConstructorWithValues(): void
    {
        $sc = new ServiceCheck(ID: 'check-1', Namespace: 'ns-1');
        self::assertSame('check-1', $sc->getID());
        self::assertSame('ns-1', $sc->getNamespace());
    }

    public function testFluentSetters(): void
    {
        $sc = new ServiceCheck();
        $result = $sc->setID('id')->setNamespace('ns');
        self::assertSame($sc, $result);
        self::assertSame('id', $sc->getID());
        self::assertSame('ns', $sc->getNamespace());
    }

    public function testJsonSerialize(): void
    {
        $sc = new ServiceCheck(ID: 'sc-1', Namespace: 'default');
        $out = $sc->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('sc-1', $out->ID);
        self::assertSame('default', $out->Namespace);
    }

    public function testJsonSerializeOmitsEmptyNamespace(): void
    {
        $sc = new ServiceCheck(ID: 'sc-1');
        $out = $sc->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'sc-from-json';
        $decoded->Namespace = 'my-ns';
        $sc = ServiceCheck::jsonUnserialize($decoded);
        self::assertSame('sc-from-json', $sc->getID());
        self::assertSame('my-ns', $sc->getNamespace());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new ServiceCheck(ID: 'rt', Namespace: 'ns');
        $restored = ServiceCheck::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getNamespace(), $restored->getNamespace());
    }
}

