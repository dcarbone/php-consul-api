<?php namespace DCarbone\PHPConsulAPITests\Definition\Coordinate;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Coordinate\CoordinateConfig;
use DCarbone\PHPConsulAPITests\Definition\AbstractDefinitionTestCases;

/**
 * Class CoordinateConfigDefinitionTest
 * @package DCarbone\PHPConsulAPITests\Definition\Coordinate
 */
class CoordinateConfigDefinitionTest extends AbstractDefinitionTestCases {
    /** @var bool */
    protected $requiresSetters = true;

    /**
     * @return string
     */
    protected function getSubjectClassName() {
        return CoordinateConfig::class;
    }

    public function testCanGetDefaultConfig() {
        $config = CoordinateConfig::default();

        $this->assertInstanceOf(CoordinateConfig::class, $config);

        $this->assertEquals(CoordinateConfig::DefaultDimensionality, $config->Dimensionality, 'Dimensionality default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultVivaldiErrorMax, $config->VivaldiErrorMax, 'VivaldiErrorMax default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultVivaldiCE, $config->VivaldiCE, 'VivaldiCE default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultVivaldiCC, $config->VivaldiCC, 'VivaldiCC default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultAdjustmentWindowSize, $config->AdjustmentWindowSize, 'AdjustmentWindowSize default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultHeightMin, $config->HeightMin, 'HeightMin default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultLatencyFilterSize, $config->LatencyFilterSize, 'LatencyFilterSize default is incorrect');
        $this->assertEquals(CoordinateConfig::DefaultGravityRho, $config->GravityRho, 'GravityRho default is incorrect');
    }
}