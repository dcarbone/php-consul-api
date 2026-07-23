<?php

namespace DCarbone\PHPConsulAPITests\Integration\Coordinate;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPI\Coordinate\DimensionalityConflictException;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Depends;

final class CoordinateClientTest extends AbstractIntegrationTestCase
{
    public const ZeroThreshold = 1.0e-6;

    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructClient(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());
        self::assertInstanceOf(CoordinateClient::class, $client);
    }

    #[Depends('testCanConstructClient')]
    public function testDatacenters(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());

        [$dcs, $err] = $client->Datacenters();
        self::assertNull($err, sprintf('CoordinateClient::datacenters() - %s', $err));
        self::assertIsArray($dcs);
        self::assertGreaterThan(0, count($dcs), 'Expected at least 1 datacenter');
    }

    #[Depends('testCanConstructClient')]
    public function testNodes(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());

        [$nodes, $qm, $err] = $client->Nodes();
        self::assertNull($err, sprintf('CoordinateClient::nodes() - %s', $err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($nodes);
    }

    public function testAdd(): void
    {
        $vec1 = [1.0, -3.0, 3.0];
        $vec2 = [-4.0, 5.0, 6.0];
        $this->verifyEqualVectors(Coordinate::_add($vec1, $vec2), [-3.0, 2.0, 9.0]);
    }

    public function testDiff(): void
    {
        $vec1 = [1.0, -3.0, 3.0];
        $vec2 = [-4.0, 5.0, 6.0];
        $this->verifyEqualVectors(Coordinate::_diff($vec1, $vec2), [5.0, -8.0, -3.0]);
    }

    public function testMagnitude(): void
    {
        $zero = [0.0, 0.0, 0.0];
        $this->verifyEqualFloats(Coordinate::_magnitude($zero), 0.0);

        $vec = [1.0, -2.0, 3.0];
        $this->verifyEqualFloats(Coordinate::_magnitude($vec), 3.7416573867739413);
    }

    public function testUnitVectorAt(): void
    {
        $vec1 = [1.0, 2.0, 3.0];
        $vec2 = [0.5, 0.6, 0.7];

        [$u, $mag] = Coordinate::_unitVectorAt($vec1, $vec2);
        $this->verifyEqualVectors($u, [0.18257418583505536, 0.511207720338155, 0.8398412548412546]);
        $this->verifyEqualFloats(Coordinate::_magnitude($u), 1.0);
        $this->verifyEqualFloats($mag, Coordinate::_magnitude(Coordinate::_diff($vec1, $vec2)));

        [$u, $mag] = Coordinate::_unitVectorAt($vec1, $vec1);
        $this->verifyEqualFloats(Coordinate::_magnitude($u), 1.0);
        $this->verifyEqualFloats($mag, 0.0);
    }

    public function testCanConstructCoordinateWithDefaultConfig(): void
    {
        $config = CoordinateConfig::Default();
        $coord  = new Coordinate(config: $config);

        self::assertIsArray($coord->Vec);
        self::assertCount($config->Dimensionality, $coord->Vec);
        self::assertContainsOnly('float', $coord->Vec);
        self::assertSame($config->VivaldiErrorMax, $coord->Error);
        self::assertSame($config->HeightMin, $coord->Height);
        self::assertSame(0.0, $coord->Adjustment);
    }

    public function testCanConstructCoordinateWithArrayOfValues(): void
    {
        $coord = new Coordinate(
            Vec: [0.1, 0.2],
        );
        self::assertInstanceOf(Coordinate::class, $coord);
        self::assertIsArray($coord->Vec);
        self::assertCount(2, $coord->Vec);
        self::assertContainsOnly('float', $coord->Vec);
        self::assertSame($coord->Vec[0], 0.1);
        self::assertSame($coord->Vec[1], 0.2);
    }

    #[Depends('testCanConstructCoordinateWithDefaultConfig')]
    public function testIsValidStates(): void
    {
        $coord = new Coordinate(config: CoordinateConfig::Default());

        self::assertTrue($coord->IsValid());

        foreach ($coord->Vec as &$field) {
            $field = \NAN;
            self::assertFalse($coord->IsValid());

            $field = 0.0;
            self::assertTrue($coord->IsValid());

            $field = \INF;
            self::assertFalse($coord->IsValid());

            $field = 0.0;
            self::assertTrue($coord->IsValid());
        }

        foreach ([&$coord->Error, &$coord->Adjustment, &$coord->Height] as &$field) {
            $field = \NAN;
            self::assertFalse($coord->IsValid());

            $field = 0.0;
            self::assertTrue($coord->IsValid());

            $field = \INF;
            self::assertFalse($coord->IsValid());

            $field = 0.0;
            self::assertTrue($coord->IsValid());
        }
    }

    public function testIsCompatibleWith(): void
    {
        $conf = CoordinateConfig::Default();

        $conf->Dimensionality = 3;
        $coord1               = new Coordinate(config: $conf);
        $coord2               = new Coordinate(config: $conf);

        $conf->Dimensionality = 2;
        $alien                = new Coordinate(config: $conf);

        self::assertTrue($coord1->IsCompatibleWith($coord2), 'coord1 should be compatible with coord2');
        self::assertFalse($coord1->IsCompatibleWith($alien), 'coord1 should NOT be compatible with alien');
        self::assertFalse($coord2->IsCompatibleWith($alien), 'coord2 should NOT be compatible with alien');
    }

    public function testApplyForce(): void
    {
        $this->expectException(DimensionalityConflictException::class);

        $config                 = CoordinateConfig::Default();
        $config->Dimensionality = 3;
        $config->HeightMin      = 0;

        $origin = new Coordinate(config: $config);

        $above      = new Coordinate(config: $config);
        $above->Vec = [0.0, 0.0, 2.9];
        $c          = $origin->ApplyForce($config, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);

        $right      = new Coordinate(config: $config);
        $right->Vec = [3.4, 0.0, -5.3];
        $c          = $c->ApplyForce($config, 2.0, $right);
        $this->verifyEqualVectors($c->Vec, [-2.0, 0.0, -5.3]);

        $c = $origin->ApplyForce($config, 1.0, $origin);
        $this->verifyEqualFloats($origin->DistanceTo($c)->Seconds(), 1.0);

        $config->HeightMin = 10.0e-6;
        $origin            = new Coordinate(config: $config);
        $c                 = $origin->ApplyForce($config, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);
        $this->verifyEqualFloats($c->Height, $config->HeightMin + 5.3 * $config->HeightMin / 2.9);

        $c = $origin->ApplyForce($config, -5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, 5.3]);
        $this->verifyEqualFloats($c->Height, $config->HeightMin);

        $bad      = clone $c;
        $bad->Vec = array_fill(0, count($c->Vec) + 1, 0.0);
        $c->ApplyForce($config, 1.0, $bad);
    }

    public function testDistanceTo(): void
    {
        $this->expectException(DimensionalityConflictException::class);

        $config                 = CoordinateConfig::Default();
        $config->Dimensionality = 3;
        $config->HeightMin      = 0;

        $c1      = new Coordinate(config: $config);
        $c2      = new Coordinate(config: $config);
        $c1->Vec = [-0.5, 1.3, 2.4];
        $c2->Vec = [1.2, -2.3, 3.4];

        $this->verifyEqualFloats($c1->DistanceTo($c1)->Seconds(), 0.0);
        $this->verifyEqualFloats($c1->DistanceTo($c2)->Seconds(), $c2->DistanceTo($c1)->Seconds());
        $this->verifyEqualFloats($c1->DistanceTo($c2)->Seconds(), 4.104875150354758);

        $c1->Adjustment = -1.0e6;

        $this->verifyEqualFloats($c1->DistanceTo($c2)->Seconds(), 4.104875150354758);

        $c1->Adjustment = 0.1;
        $c2->Adjustment = 0.2;
        $this->verifyEqualFloats($c1->DistanceTo($c2)->Seconds(), 4.104875150354758 + 0.3);

        $c1->Height = 0.7;
        $c2->Height = 0.1;
        $this->verifyEqualFloats($c1->DistanceTo($c2)->Seconds(), 4.104875150354758 + 0.3 + 0.8);

        $bad      = clone $c1;
        $bad->Vec = array_fill(0, count($c1->Vec) + 1, 0.0);
        $c1->DistanceTo($bad);
    }

    protected function verifyEqualFloats(float $f1, float $f2): void
    {
        self::assertLessThanOrEqual(
            self::ZeroThreshold,
            abs($f1 - $f2),
            sprintf('equal assertion fail, %.6f != %.6f', $f1, $f2)
        );
    }

    protected function verifyEqualVectors(array $vec1, array $vec2): void
    {
        self::assertSameSize($vec1, $vec2);
        try {
            foreach ($vec1 as $k => $v) {
                $this->verifyEqualFloats($v, $vec2[$k]);
            }
        } catch (AssertionFailedError $e) {
            var_dump($vec1, $vec2);
            throw $e;
        }
    }
}
