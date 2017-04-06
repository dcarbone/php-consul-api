<?php namespace DCarbone\PHPConsulAPI\PreparedQuery;

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
 * Class QueryTemplate
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class QueryTemplate extends AbstractModel
{
    /** @var string */
    public $Type = '';
    /** @var string */
    public $Regexp = '';

    /**
     * @return string
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate
     */
    public function setType($Type)
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegexp()
    {
        return $this->Regexp;
    }

    /**
     * @param string $Regexp
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate
     */
    public function setRegexp($Regexp)
    {
        $this->Regexp = $Regexp;
        return $this;
    }
}