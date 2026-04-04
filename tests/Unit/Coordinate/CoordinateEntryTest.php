<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CoordinateEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new CoordinateEntry();
        self::assertSame('', $e->getNode());
        self::assertSame('', $e->getSegment());
        self::assertSame('', $e->getPartition());
        self::assertNull($e->getCoord());
    }

    public function testConstructorWithValues(): void
    {
        $coord = new Coordinate(Vec: [1.0, 2.0], Error: 0.1);
        $e = new CoordinateEntry(Node: 'node-1', Segment: 'seg', Partition: 'part', Coord: $coord);
        self::assertSame('node-1', $e->getNode());
        self::assertSame('seg', $e->getSegment());
        self::assertSame('part', $e->getPartition());
        self::assertNotNull($e->getCoord());
        self::assertSame([1.0, 2.0], $e->getCoord()->getVec());
    }

    public function testFluentSetters(): void
    {
        $e = new CoordinateEntry();
        $coord = new Coordinate(Vec: [0.5]);
        $result = $e->setNode('n')->setSegment('s')->setPartition('p')->setCoord($coord);
        self::assertSame($e, $result);
        self::assertSame('n', $e->getNode());
        self::assertSame('s', $e->getSegment());
        self::assertSame('p', $e->getPartition());
        self::assertNotNull($e->getCoord());
    }

    public function testJsonSerialize(): void
    {
        $e = new CoordinateEntry(Node: 'n1', Segment: 's1', Coord: new Coordinate(Vec: [1.0]));
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('n1', $out->Node);
        self::assertSame('s1', $out->Segment);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeIncludesNonEmptyPartition(): void
    {
        $e = new CoordinateEntry(Node: 'n1', Partition: 'p1');
        $out = $e->jsonSerialize();
        self::assertSame('p1', $out->Partition);
    }

    public function testJsonUnserialize(): void
    {
        $coordObj = new \stdClass();
        $coordObj->Vec = [1.5, 2.5];
        $coordObj->Error = 0.3;
        $coordObj->Adjustment = 0.0;
        $coordObj->Height = 0.0;

        $decoded = new \stdClass();
        $decoded->Node = 'json-node';
        $decoded->Segment = 'json-seg';
        $decoded->Partition = '';
        $decoded->Coord = $coordObj;

        $e = CoordinateEntry::jsonUnserialize($decoded);
        self::assertSame('json-node', $e->getNode());
        self::assertSame('json-seg', $e->getSegment());
        self::assertNotNull($e->getCoord());
        self::assertSame([1.5, 2.5], $e->getCoord()->getVec());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new CoordinateEntry(Node: 'rt', Segment: 's', Coord: new Coordinate(Vec: [0.1, 0.2]));
        $json = json_encode($original);
        $decoded = json_decode($json, false);
        $restored = CoordinateEntry::jsonUnserialize($decoded);
        self::assertSame($original->getNode(), $restored->getNode());
        self::assertSame($original->getSegment(), $restored->getSegment());
        self::assertNotNull($restored->getCoord());
        self::assertSame($original->getCoord()->getVec(), $restored->getCoord()->getVec());
    }
}

