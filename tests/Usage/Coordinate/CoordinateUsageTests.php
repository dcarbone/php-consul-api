<?php namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;
use function DCarbone\PHPConsulAPI\Coordinate\add;
use function DCarbone\PHPConsulAPI\Coordinate\diff;
use function DCarbone\PHPConsulAPI\Coordinate\magnitude;
use function DCarbone\PHPConsulAPI\Coordinate\unitVectorAt;

/**
 * These tests were largely pulled from https://github.com/hashicorp/serf/blob/master/coordinate/coordinate_test.go
 *
 * Class CoordinateUsageTests
 * @package DCarbone\PHPConsulAPITests\Usage\Coordinate
 */
class CoordinateUsageTests extends AbstractUsageTests {
    const ZeroThreshold = 1.0e-6;

    public function testAdd() {
        $vec1 = [1.0, -3.0, 3.0];
        $vec2 = [-4.0, 5.0, 6.0];
        $this->verifyEqualVectors(add($vec1, $vec2), [-3.0, 2.0, 9.0]);
    }

    public function testDiff() {
        $vec1 = [1.0, -3.0, 3.0];
        $vec2 = [-4.0, 5.0, 6.0];
        $this->verifyEqualVectors(diff($vec1, $vec2), [5.0, -8.0, -3.0]);
    }

    public function testMagnitude() {
        $zero = [0.0, 0.0, 0.0];
        $this->verifyEqualFloats(magnitude($zero), 0.0);

        $vec = [1.0, -2.0, 3.0];
        $this->verifyEqualFloats(magnitude($vec), 3.7416573867739413);
    }

    public function testUnitVectorAt() {
        $vec1 = [1.0, 2.0, 3.0];
        $vec2 = [0.5, 0.6, 0.7];

        list($u, $mag) = unitVectorAt($vec1, $vec2);
        $this->verifyEqualVectors($u, [0.18257418583505536, 0.511207720338155, 0.8398412548412546]);
        $this->verifyEqualFloats(magnitude($u), 1.0);
        $this->verifyEqualFloats($mag, magnitude(diff($vec1, $vec2)));

        list($u, $mag) = unitVectorAt($vec1, $vec1);
        $this->verifyEqualFloats(magnitude($u), 1.0);
        $this->verifyEqualFloats($mag, 0.0);
    }

    public function testCanConstructCoordinateWithDefaultConfig() {
        $config = CoordinateConfig::default();
        $coord = new Coordinate($config);

        $this->assertInternalType('array', $coord->Vec);
        $this->assertCount($config->Dimensionality, $coord->Vec);
        $this->assertContainsOnly('float', $coord->Vec);
        $this->assertEquals($config->VivaldiErrorMax, $coord->Error);
        $this->assertEquals($config->HeightMin, $coord->Height);
        $this->assertEquals(0.0, $coord->Adjustment);
    }

    public function testCanConstructCoordinateWithArrayOfValues() {
        $coord = new Coordinate([
            'Vec' => [0.1, 0.2],
        ]);
        $this->assertInstanceOf(Coordinate::class, $coord);
        $this->assertInternalType('array', $coord->Vec);
        $this->assertCount(2, $coord->Vec);
        $this->assertContainsOnly('float', $coord->Vec);
        $this->assertEquals($coord->Vec[0], 0.1);
        $this->assertEquals($coord->Vec[1], 0.2);
    }

    /**
     * @depends testCanConstructCoordinateWithDefaultConfig
     */
    public function testIsValidStates() {
        $coord = new Coordinate(CoordinateConfig::default());

        $this->assertTrue($coord->isValid());

        foreach ($coord->Vec as &$field) {
            $field = NAN;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());

            $field = INF;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());
        }

        foreach ([&$coord->Error, &$coord->Adjustment, &$coord->Height] as &$field) {
            $field = NAN;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());

            $field = INF;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());
        }
    }

    public function testIsCompatibleWith() {
        $conf = CoordinateConfig::default();

        $conf->Dimensionality = 3;
        $coord1 = new Coordinate($conf);
        $coord2 = new Coordinate($conf);

        $conf->Dimensionality = 2;
        $alien = new Coordinate($conf);

        $this->assertTrue($coord1->isCompatibleWith($coord2), 'coord1 should be compatible with coord2');
        $this->assertFalse($coord1->isCompatibleWith($alien), 'coord1 should NOT be compatible with alien');
        $this->assertFalse($coord2->isCompatibleWith($alien), 'coord2 should NOT be compatible with alien');
    }

    /**
     * @expectedException \DCarbone\PHPConsulAPI\Coordinate\DimensionalityConflictException
     */
    public function testApplyForce() {
        $config = CoordinateConfig::default();
        $config->Dimensionality = 3;
        $config->HeightMin = 0;

        $origin = new Coordinate($config);

        $above = new Coordinate($config);
        $above->Vec = [0.0, 0.0, 2.9];
        $c = $origin->applyForce($config, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);

        $right = new Coordinate($config);
        $right->Vec = [3.4, 0.0, -5.3];
        $c = $c->applyForce($config, 2.0, $right);
        $this->verifyEqualVectors($c->Vec, [-2.0, 0.0, -5.3]);

        $c = $origin->applyForce($config, 1.0, $origin);
        $this->verifyEqualFloats($origin->distanceTo($c)->Seconds(), 1.0);

        $config->HeightMin = 10.0e-6;
        $origin = new Coordinate($config);
        $c = $origin->applyForce($config, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);
        $this->verifyEqualFloats($c->Height, $config->HeightMin + 5.3 * $config->HeightMin / 2.9);

        $c = $origin->applyForce($config, -5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, 5.3]);
        $this->verifyEqualFloats($c->Height, $config->HeightMin);

        $bad = clone $c;
        $bad->Vec = array_fill(0, count($c->Vec) + 1, 0.0);
        $c->applyForce($config, 1.0, $bad);
    }

    /**
     * @expectedException \DCarbone\PHPConsulAPI\Coordinate\DimensionalityConflictException
     */
    public function testDistanceTo() {
        $config = CoordinateConfig::default();
        $config->Dimensionality = 3;
        $config->HeightMin = 0;

        $c1 = new Coordinate($config);
        $c2 = new Coordinate($config);
        $c1->Vec = [-0.5, 1.3, 2.4];
        $c2->Vec = [1.2, -2.3, 3.4];

        $this->verifyEqualFloats($c1->distanceTo($c1)->Seconds(), 0.0);
        $this->verifyEqualFloats($c1->distanceTo($c2)->Seconds(), $c2->distanceTo($c1)->Seconds());
        $this->verifyEqualFloats($c1->distanceTo($c2)->Seconds(), 4.104875150354758);

        $c1->Adjustment = -1.0e6;

        $this->verifyEqualFloats($c1->distanceTo($c2)->Seconds(), 4.104875150354758);

        $c1->Adjustment = 0.1;
        $c2->Adjustment = 0.2;
        $this->verifyEqualFloats($c1->distanceTo($c2)->Seconds(), 4.104875150354758 + 0.3);

        $c1->Height = 0.7;
        $c2->Height = 0.1;
        $this->verifyEqualFloats($c1->distanceTo($c2)->Seconds(), 4.104875150354758 + 0.3 + 0.8);

        $bad = clone $c1;
        $bad->Vec = array_fill(0, count($c1->Vec) + 1, 0.0);
        $c1->distanceTo($bad);
    }

    /**
     * @param float $f1
     * @param float $f2
     */
    protected function verifyEqualFloats(float $f1, float $f2): void {
        $this->assertLessThanOrEqual(self::ZeroThreshold,
            abs($f1 - $f2),
            sprintf('equal assertion fail, %.6f != %.6f', $f1, $f2));
    }

    /**
     * @param array $vec1
     * @param array $vec2
     */
    protected function verifyEqualVectors(array $vec1, array $vec2): void {
        $this->assertSameSize($vec1, $vec2);
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