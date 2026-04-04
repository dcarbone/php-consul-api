<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QueryDatacenterOptionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $opts = new QueryDatacenterOptions();
        self::assertSame(0, $opts->getNearestN());
        self::assertSame([], $opts->getDatacenters());
    }

    public function testConstructorWithValues(): void
    {
        $opts = new QueryDatacenterOptions(
            NearestN: 3,
            Datacenters: ['dc1', 'dc2', 'dc3'],
        );
        self::assertSame(3, $opts->getNearestN());
        self::assertSame(['dc1', 'dc2', 'dc3'], $opts->getDatacenters());
    }

    public function testFluentSetters(): void
    {
        $opts = new QueryDatacenterOptions();
        $result = $opts
            ->setNearestN(5)
            ->setDatacenters('us-east', 'us-west');

        self::assertSame($opts, $result);
        self::assertSame(5, $opts->getNearestN());
        self::assertSame(['us-east', 'us-west'], $opts->getDatacenters());
    }

    public function testVariadicSetDatacentersReplacesExisting(): void
    {
        $opts = new QueryDatacenterOptions(Datacenters: ['old-dc']);
        $opts->setDatacenters('new-dc-1', 'new-dc-2');

        self::assertSame(['new-dc-1', 'new-dc-2'], $opts->getDatacenters());
    }

    public function testVariadicSetDatacentersWithNoArgsClearsArray(): void
    {
        $opts = new QueryDatacenterOptions(Datacenters: ['dc1']);
        $opts->setDatacenters();

        self::assertSame([], $opts->getDatacenters());
    }

    public function testAddDatacenter(): void
    {
        $opts = new QueryDatacenterOptions(Datacenters: ['dc1']);
        $result = $opts->addDatacenter('dc2');

        self::assertSame($opts, $result);
        self::assertSame(['dc1', 'dc2'], $opts->getDatacenters());
    }

    public function testJsonSerialize(): void
    {
        $opts = new QueryDatacenterOptions(
            NearestN: 2,
            Datacenters: ['dc-a', 'dc-b'],
        );

        $out = $opts->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(2, $out->NearestN);
        self::assertSame(['dc-a', 'dc-b'], $out->Datacenters);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->NearestN = 4;
        $decoded->Datacenters = ['x', 'y'];

        $opts = QueryDatacenterOptions::jsonUnserialize($decoded);

        self::assertSame(4, $opts->getNearestN());
        self::assertSame(['x', 'y'], $opts->getDatacenters());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new QueryDatacenterOptions(
            NearestN: 3,
            Datacenters: ['dc1', 'dc2'],
        );

        $restored = QueryDatacenterOptions::jsonUnserialize($original->jsonSerialize());

        self::assertSame($original->getNearestN(), $restored->getNearestN());
        self::assertSame($original->getDatacenters(), $restored->getDatacenters());
    }
}

