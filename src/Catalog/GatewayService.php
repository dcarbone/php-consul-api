<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class GatewayService
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class GatewayService extends AbstractModel
{
    /** @var \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName */
    public $Gateway;
    /** @var \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName */
    public $Service;
    /** @var string */
    public $GatewayKind = '';
    /** @var int */
    public $Port = 0;
    /** @var string */
    public $Protocol = '';
    /** @var string[]|null */
    public $Hosts = null;
    /** @var string */
    public $CAFile = '';
    /** @var string */
    public $CertFile = '';
    /** @var string */
    public $KeyFile = '';
    /** @var string */
    public $SNI = '';
    /** @var string */
    public $FromWildCard = '';

    /**
     * GatewayService constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        if (null === $data) {
            $this->Gateway = new CompoundServiceName();
            $this->Service = new CompoundServiceName();
            return;
        }
        parent::__construct($data);
        if (is_array($this->Gateway)) {
            $this->Gateway = new CompoundServiceName($this->Gateway);
        } elseif (!($this->Gateway instanceof CompoundServiceName)) {
            $this->Gateway = new CompoundServiceName();
        }
        if (is_array($this->Service)) {
            $this->Service = new CompoundServiceName($this->Service);
        } elseif (!($this->Gateway instanceof CompoundServiceName)) {
            $this->Service = new CompoundServiceName();
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName
     */
    public function getGateway(): CompoundServiceName
    {
        return $this->Gateway;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName
     */
    public function getService(): CompoundServiceName
    {
        return $this->Service;
    }

    /**
     * @return string
     */
    public function getGatewayKind(): string
    {
        return $this->GatewayKind;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    /**
     * @return string[]|null
     */
    public function getHosts(): ?array
    {
        return $this->Hosts;
    }

    /**
     * @return string
     */
    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    /**
     * @return string
     */
    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    /**
     * @return string
     */
    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    /**
     * @return string
     */
    public function getSNI(): string
    {
        return $this->SNI;
    }

    /**
     * @return string
     */
    public function getFromWildCard(): string
    {
        return $this->FromWildCard;
    }
}