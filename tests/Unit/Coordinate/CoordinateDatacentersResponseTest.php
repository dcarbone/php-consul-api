<?php
namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacenterMap;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacentersResponse;
use PHPUnit\Framework\TestCase;
/**
 * @internal
 */
final class CoordinateDatacentersResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new CoordinateDatacentersResponse();
        self::assertSame([], $r->DatacenterMap);
        self::assertSame([], $r->getValue());
    }
    public function testUnmarshalValue(): void
    {
        $coordObj = new \stdClass();
        $coordObj->Vec = [1.0];
        $coordObj->Error = 0.0;
        $coordObj->Adjustment = 0.0;
        $coordObj->Height = 0.0;
        $entryObj = new \stdClass();
        $entryObj->Node = 'n1';
        $entryObj->Segment = '';
        $entryObj->Coord = $coordObj;
        $mapObj = new \stdClass();
        $mapObj->Datacenter = 'dc1';
        $mapObj->AreaID = 'area';
        $mapObj->Coordinates = [$entryObj];
        $r = new CoordinateDatacentersResponse();
        $r->unmarshalValue([$mapObj]);
        self::assertCount(1, $r->DatacenterMap);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(CoordinateDatacenterMap::class, $r->DatacenterMap[0]);
        self::assertSame('dc1', $r->DatacenterMap[0]->Datacenter);
    }
}
