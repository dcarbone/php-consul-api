<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Metrics;

use DCarbone\PHPConsulAPI\Metrics\Label;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LabelTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new Label();
        self::assertSame('', $l->getName());
        self::assertSame('', $l->getValue());
    }

    public function testConstructorWithValues(): void
    {
        $l = new Label(Name: 'env', Value: 'prod');
        self::assertSame('env', $l->getName());
        self::assertSame('prod', $l->getValue());
    }

    public function testFluentSetters(): void
    {
        $l = new Label();
        $result = $l->setName('host')->setValue('node-1');
        self::assertSame($l, $result);
        self::assertSame('host', $l->getName());
        self::assertSame('node-1', $l->getValue());
    }

    public function testJsonSerialize(): void
    {
        $l = new Label(Name: 'dc', Value: 'dc1');
        $out = $l->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('dc', $out->Name);
        self::assertSame('dc1', $out->Value);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Name = 'region';
        $decoded->Value = 'us-east';
        $l = Label::jsonUnserialize($decoded);
        self::assertSame('region', $l->getName());
        self::assertSame('us-east', $l->getValue());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new Label(Name: 'service', Value: 'web');
        $restored = Label::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getName(), $restored->getName());
        self::assertSame($original->getValue(), $restored->getValue());
    }
}

