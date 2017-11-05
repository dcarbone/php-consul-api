<?php namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

ini_set('precision', 17);

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;

/**
 * These tests were largely pulled from https://github.com/hashicorp/serf/blob/master/coordinate/coordinate_test.go
 *
 * Class CoordinateUsageTests
 * @package DCarbone\PHPConsulAPITests\Usage\Coordinate
 */
class CoordinateUsageTests extends AbstractUsageTests {
    const ZeroThreshold = 1.0e-6;

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
     * TODO: Have somebody who isn't terrible at math look at this...
     */
    public function testApplyForce() {
        $conf = CoordinateConfig::default();
        $conf->Dimensionality = 3;
        $conf->HeightMin = 0;

        $origin = new Coordinate($conf);

        $above = new Coordinate($conf);
        $above->Vec = [0.0, 0.0, 2.9];
        $c = $origin->applyForce($conf, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);

        $right = new Coordinate($conf);
        $right->Vec = [3.4, 0.0, -5.3];
        $c = $c->applyForce($conf, 2.0, $right);
        $this->verifyEqualVectors($c->Vec, [-2.0, 0.0, -5.3]);

        $c = $origin->applyForce($conf, 1.0, $origin);
        $this->verifyEqualFloats($origin->distanceTo($c) / Coordinate::SecondsToNanoseconds, 1.0);

        $conf->HeightMin = 10.0e-6;
        $origin = new Coordinate($conf);
        $c = $origin->applyForce($conf, 5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, -5.3]);
        $this->verifyEqualFloats($c->Height, $conf->HeightMin + 5.3 * $conf->HeightMin / 2.9);

        $c = $origin->applyForce($conf, -5.3, $above);
        $this->verifyEqualVectors($c->Vec, [0.0, 0.0, 5.3]);
        $this->verifyEqualFloats($c->Height, $conf->HeightMin);


    }

    /**
     * TODO: Is this a valid test...?
     *
     * @param float $f1
     * @param float $f2
     */
    protected function verifyEqualFloats(float $f1, float $f2): void {
        if (0.0 === $f1) {
            $this->assertTrue(0.0 === $f2, 'Failed to assert (0.0 === '.$f2.')');
        } else {
            $this->assertGreaterThan(self::ZeroThreshold, abs($f1 - $f2));
        }
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