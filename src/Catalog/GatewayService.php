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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class GatewayService
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class GatewayService extends AbstractModel
{
    private const FIELD_GATEWAY = 'Gateway';
    private const FIELD_SERVICE = 'Service';

    /** @var \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName */
    public CompoundServiceName $Gateway;
    /** @var \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName */
    public CompoundServiceName $Service;
    /** @var string */
    public string $GatewayKind = '';
    /** @var int */
    public int $Port = 0;
    /** @var string */
    public string $Protocol = '';
    /** @var string[] */
    public array $Hosts = [];
    /** @var string */
    public string $CAFile = '';
    /** @var string */
    public string $CertFile = '';
    /** @var string */
    public string $KeyFile = '';
    /** @var string */
    public string $SNI = '';
    /** @var string */
    public string $FromWildCard = '';

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_GATEWAY => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => CompoundServiceName::class,
        ],
        self::FIELD_SERVICE => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => CompoundServiceName::class,
        ],
    ];

    /**
     * GatewayService constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (null === $this->Gateway) {
            $this->Gateway = new CompoundServiceName();
        }
        if (null === $this->Service) {
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