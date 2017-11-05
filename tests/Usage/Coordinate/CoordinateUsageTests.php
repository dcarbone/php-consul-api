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
        $coord = new Coordinate();
        $this->assertCount($config->Dimensionality, $coord->Vec);
        $this->assertContainsOnly('double', $coord->Vec);
        $this->assertEquals($config->VivaldiErrorMax, $coord->Error);
        $this->assertEquals($config->HeightMin, $coord->Height);
        $this->assertEquals(0.0, $coord->Adjustment);
    }
}