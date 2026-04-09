<?php

namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacenterMap;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CoordinateDatacenterMapTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new CoordinateDatacenterMap();
        self::assertSame('', $m->getDatacenter());
        self::assertSame('', $m->Datacenter);
        self::assertSame('', $m->getAreaID());
        self::assertSame('', $m->AreaID);
        self::assertSame([], $m->getCoordinates());
        self::assertSame([], $m->Coordinates);
    }

    public function testConstructorWithValues(): void
    {
        $entry = new CoordinateEntry(Node: 'n1', Coord: new Coordinate(Vec: [1.0]));
        $m = new CoordinateDatacenterMap(Datacenter: 'dc1', AreaID: 'area-1', Coordinates: [$entry]);
        self::assertSame('dc1', $m->getDatacenter());
        self::assertSame('dc1', $m->Datacenter);
        self::assertSame('area-1', $m->getAreaID());
        self::assertSame('area-1', $m->AreaID);
        self::assertCount(1, $m->getCoordinates());
        self::assertCount(1, $m->Coordinates);
        self::assertSame('n1', $m->Coordinates[0]->Node);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $m = new CoordinateDatacenterMap();

        $m->setDatacenter('dc2');
        self::assertSame('dc2', $m->getDatacenter());
        self::assertSame('dc2', $m->Datacenter);

        $m->setAreaID('a2');
        self::assertSame('a2', $m->getAreaID());
        self::assertSame('a2', $m->AreaID);

        $entry = new CoordinateEntry(Node: 'n');
        $m->setCoordinates($entry);
        self::assertCount(1, $m->getCoordinates());
        self::assertCount(1, $m->Coordinates);
        self::assertSame('n', $m->Coordinates[0]->Node);
    }

    public function testFluentSetters(): void
    {
        $m = new CoordinateDatacenterMap();
        $result = $m
            ->setDatacenter('dc2')
            ->setAreaID('a2')
            ->setCoordinates(new CoordinateEntry(Node: 'n'));
        self::assertSame($m, $result);
        self::assertSame('dc2', $m->Datacenter);
        self::assertSame('a2', $m->AreaID);
        self::assertCount(1, $m->Coordinates);
    }

    public function testJsonSerialize(): void
    {
        $m = new CoordinateDatacenterMap(Datacenter: 'dc1', AreaID: 'area');
        $out = $m->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('dc1', $out->Datacenter);
        self::assertSame('area', $out->AreaID);
    }

    public function testJsonUnserialize(): void
    {
        $coordObj = new \stdClass();
        $coordObj->Vec = [0.5];
        $coordObj->Error = 0.0;
        $coordObj->Adjustment = 0.0;
        $coordObj->Height = 0.0;

        $entryObj = new \stdClass();
        $entryObj->Node = 'json-n';
        $entryObj->Segment = '';
        $entryObj->Coord = $coordObj;

        $decoded = new \stdClass();
        $decoded->Datacenter = 'dc-json';
        $decoded->AreaID = 'area-json';
        $decoded->Coordinates = [$entryObj];

        $m = CoordinateDatacenterMap::jsonUnserialize($decoded);
        self::assertSame('dc-json', $m->Datacenter);
        self::assertSame('area-json', $m->AreaID);
        self::assertCount(1, $m->Coordinates);
        self::assertInstanceOf(CoordinateEntry::class, $m->Coordinates[0]);
        self::assertSame('json-n', $m->Coordinates[0]->Node);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new CoordinateDatacenterMap(Datacenter: 'dc1', AreaID: 'a1');
        $restored = CoordinateDatacenterMap::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->Datacenter, $restored->Datacenter);
        self::assertSame($original->AreaID, $restored->AreaID);
    }
}

