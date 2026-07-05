<?php
namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateEntriesResponse;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry;
use PHPUnit\Framework\TestCase;
/**
 * @internal
 */
final class CoordinateEntriesResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new CoordinateEntriesResponse();
        self::assertSame([], $r->Nodes);
        self::assertSame([], $r->getValue());
    }
    public function testUnmarshalValue(): void
    {
        $coordObj = new \stdClass();
        $coordObj->Vec = [2.0, 3.0];
        $coordObj->Error = 0.1;
        $coordObj->Adjustment = 0.0;
        $coordObj->Height = 0.0;
        $entryObj = new \stdClass();
        $entryObj->Node = 'node-1';
        $entryObj->Segment = 'seg';
        $entryObj->Coord = $coordObj;
        $r = new CoordinateEntriesResponse();
        $r->unmarshalValue([$entryObj]);
        self::assertCount(1, $r->Nodes);
        self::assertCount(1, $r->getValue());
        self::assertInstanceOf(CoordinateEntry::class, $r->Nodes[0]);
        self::assertSame('node-1', $r->Nodes[0]->Node);
        self::assertSame('seg', $r->Nodes[0]->Segment);
        self::assertSame([2.0, 3.0], $r->Nodes[0]->Coord->Vec);
    }
}
