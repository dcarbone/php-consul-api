<?php namespace DCarbone\SimpleConsulPHP\Agent;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\SimpleConsulPHP\AbstractResponseModel;

/**
 * Class AgentSelfCoord
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentSelfCoord extends AbstractResponseModel
{
    protected static $default = array(
        'Vec' => array(),
        'Error' => null,
        'Adjustment' => null,
        'Height' => null
    );

    /**
     * @return array
     */
    public function getVec()
    {
        return $this['Vec'];
    }

    /**
     * @return float
     */
    public function getError()
    {
        return (float)$this['Error'];
    }

    /**
     * @return float
     */
    public function getAdjustment()
    {
        return (float)$this['Adjustment'];
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return (float)$this['Height'];
    }
}