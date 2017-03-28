<?php namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class AgentSelfCoord
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelfCoord extends AbstractModel
{
    /** @var array */
    public $Vec = [];
    /** @var float */
    public $Error = 0.0;
    /** @var float */
    public $Adjustment = 0.0;
    /** @var float */
    public $Height = 0.0;

    /**
     * @return array
     */
    public function getVec()
    {
        return $this->Vec;
    }

    /**
     * @return float
     */
    public function getError()
    {
        return $this->Error;
    }

    /**
     * @return float
     */
    public function getAdjustment()
    {
        return $this->Adjustment;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->Height;
    }
}