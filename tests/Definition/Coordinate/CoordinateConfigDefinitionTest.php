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
 *
 * @internal
 */
final class CoordinateConfigDefinitionTest extends AbstractDefinitionTestCases
{
    /** @var bool */
    protected $requiresSetters = true;

    public function testCanGetDefaultConfig(): void
    {
        $config = CoordinateConfig::Default();

        static::assertInstanceOf(CoordinateConfig::class, $config);

        static::assertSame(CoordinateConfig::DefaultDimensionality, $config->Dimensionality, 'Dimensionality default is incorrect');
        static::assertSame(CoordinateConfig::DefaultVivaldiErrorMax, $config->VivaldiErrorMax, 'VivaldiErrorMax default is incorrect');
        static::assertSame(CoordinateConfig::DefaultVivaldiCE, $config->VivaldiCE, 'VivaldiCE default is incorrect');
        static::assertSame(CoordinateConfig::DefaultVivaldiCC, $config->VivaldiCC, 'VivaldiCC default is incorrect');
        static::assertSame(CoordinateConfig::DefaultAdjustmentWindowSize, $config->AdjustmentWindowSize, 'AdjustmentWindowSize default is incorrect');
        static::assertSame(CoordinateConfig::DefaultHeightMin, $config->HeightMin, 'HeightMin default is incorrect');
        static::assertSame(CoordinateConfig::DefaultLatencyFilterSize, $config->LatencyFilterSize, 'LatencyFilterSize default is incorrect');
        static::assertSame(CoordinateConfig::DefaultGravityRho, $config->GravityRho, 'GravityRho default is incorrect');
    }

    /**
     * @return string
     */
    protected function getSubjectClassName(): string
    {
        return CoordinateConfig::class;
    }
}
