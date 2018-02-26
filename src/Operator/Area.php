<?php namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class Area
 * @package DCarbone\PHPConsulAPI\Operator
 */
class Area extends AbstractModel {
    /** @var string */
    public $ID = '';
    /** @var string */
    public $PeerDatacenter = '';
    /** @var string[] */
    public $RetryJoin = [];
    /** @var bool */
    public $UseTLS = false;

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return Area
     */
    public function setID(string $ID): Area {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getPeerDatacenter(): string {
        return $this->PeerDatacenter;
    }

    /**
     * @param string $PeerDatacenter
     * @return Area
     */
    public function setPeerDatacenter(string $PeerDatacenter): Area {
        $this->PeerDatacenter = $PeerDatacenter;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRetryJoin(): array {
        return $this->RetryJoin;
    }

    /**
     * @param string[] $RetryJoin
     * @return Area
     */
    public function setRetryJoin(array $RetryJoin): Area {
        $this->RetryJoin = $RetryJoin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseTLS(): bool {
        return $this->UseTLS;
    }

    /**
     * @param bool $UseTLS
     * @return Area
     */
    public function setUseTLS(bool $UseTLS): Area {
        $this->UseTLS = $UseTLS;
        return $this;
    }
}