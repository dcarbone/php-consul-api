<?php

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
        self::assertSame('', $e->Node);
        self::assertSame('', $e->getSegment());
        self::assertSame('', $e->Segment);
        self::assertSame('', $e->getPartition());
        self::assertSame('', $e->Partition);
        self::assertNull($e->getCoord());
        self::assertNull($e->Coord);
    }

    public function testConstructorWithValues(): void
    {
        $coord = new Coordinate(Vec: [1.0, 2.0], Error: 0.1);
        $e = new CoordinateEntry(Node: 'node-1', Segment: 'seg', Partition: 'part', Coord: $coord);
        self::assertSame('node-1', $e->getNode());
        self::assertSame('node-1', $e->Node);
        self::assertSame('seg', $e->getSegment());
        self::assertSame('seg', $e->Segment);
        self::assertSame('part', $e->getPartition());
        self::assertSame('part', $e->Partition);
        self::assertNotNull($e->getCoord());
        self::assertNotNull($e->Coord);
        self::assertSame([1.0, 2.0], $e->Coord->Vec);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $e = new CoordinateEntry();

        $e->setNode('n');
        self::assertSame('n', $e->getNode());
        self::assertSame('n', $e->Node);

        $e->setSegment('s');
        self::assertSame('s', $e->getSegment());
        self::assertSame('s', $e->Segment);

        $e->setPartition('p');
        self::assertSame('p', $e->getPartition());
        self::assertSame('p', $e->Partition);

        $coord = new Coordinate(Vec: [0.5]);
        $e->setCoord($coord);
        self::assertSame($coord, $e->getCoord());
        self::assertSame($coord, $e->Coord);

        $e->setCoord(null);
        self::assertNull($e->getCoord());
        self::assertNull($e->Coord);
    }

    public function testFluentSetters(): void
    {
        $e = new CoordinateEntry();
        $coord = new Coordinate(Vec: [0.5]);
        $result = $e->setNode('n')->setSegment('s')->setPartition('p')->setCoord($coord);
        self::assertSame($e, $result);
        self::assertSame('n', $e->Node);
        self::assertSame('s', $e->Segment);
        self::assertSame('p', $e->Partition);
        self::assertNotNull($e->Coord);
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
        self::assertSame('json-node', $e->Node);
        self::assertSame('json-seg', $e->Segment);
        self::assertNotNull($e->Coord);
        self::assertSame([1.5, 2.5], $e->Coord->Vec);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new CoordinateEntry(Node: 'rt', Segment: 's', Coord: new Coordinate(Vec: [0.1, 0.2]));
        $json = json_encode($original);
        $decoded = json_decode($json, false);
        $restored = CoordinateEntry::jsonUnserialize($decoded);
        self::assertSame($original->Node, $restored->Node);
        self::assertSame($original->Segment, $restored->Segment);
        self::assertNotNull($restored->Coord);
        self::assertSame($original->Coord->Vec, $restored->Coord->Vec);
    }
}
