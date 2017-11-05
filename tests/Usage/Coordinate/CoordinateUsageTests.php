<?php namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * These tests were largely pulled from https://github.com/hashicorp/serf/blob/master/coordinate/coordinate_test.go
 *
 * Class CoordinateUsageTests
 * @package DCarbone\PHPConsulAPITests\Usage\Coordinate
 */
class CoordinateUsageTests extends AbstractUsageTests {
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

        foreach($coord->Vec as &$field) {
            $field = NAN;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());

            $field = INF;
            $this->assertFalse($coord->isValid());

            $field = 0.0;
            $this->assertTrue($coord->isValid());
        }

        foreach([&$coord->Error, &$coord->Adjustment, &$coord->Height] as &$field) {
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


}