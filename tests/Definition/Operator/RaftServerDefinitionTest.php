<?php namespace DCarbone\PHPConsulAPITests\Definition\Operator;

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

use DCarbone\PHPConsulAPI\Operator\RaftServer;
use DCarbone\PHPConsulAPITests\Definition\AbstractDefinitionTestCases;

/**
 * Class RaftServerDefinitionTest
 *
 * @package DCarbone\PHPConsulAPITests\Definition\Operator
 */
class RaftServerDefinitionTest extends AbstractDefinitionTestCases {
    /** @var bool */
    protected $requiresSetters = true;

    /**
     * @return mixed
     */
    protected function getSubjectClassName() {
        return RaftServer::class;
    }
}