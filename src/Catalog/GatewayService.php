<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class GatewayService
 */
class GatewayService extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_GATEWAY  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => CompoundServiceName::class,
        ],
        self::FIELD_SERVICE  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => CompoundServiceName::class,
        ],
        self::FIELD_PORT     => Transcoding::OMITEMPTY_INTEGER_FIELD,
        self::FIELD_PROTOCOL => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_HOSTS    => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::STRING,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
        self::FIELD_CA_FILE      => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_CERT_FILE    => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_KEY_FILE     => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SNI          => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_FROM_WILDCARD=> Transcoding::OMITEMPTY_BOOLEAN_FIELD,
    ];

    private const FIELD_GATEWAY       = 'Gateway';
    private const FIELD_SERVICE       = 'Service';
    private const FIELD_PORT          = 'Port';
    private const FIELD_PROTOCOL      = 'Protocol';
    private const FIELD_HOSTS         = 'Hosts';
    private const FIELD_CA_FILE       = 'CAFile';
    private const FIELD_CERT_FILE     = 'CertFile';
    private const FIELD_KEY_FILE      = 'KeyFile';
    private const FIELD_SNI           = 'SNI';
    private const FIELD_FROM_WILDCARD = 'FromWildcard';

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

    /**
     * GatewayService constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Gateway)) {
            $this->Gateway = new CompoundServiceName();
        }
        if (!isset($this->Service)) {
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
     * @param \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName $Gateway
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setGateway(CompoundServiceName $Gateway): self
    {
        $this->Gateway = $Gateway;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName
     */
    public function getService(): CompoundServiceName
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CompoundServiceName $Service
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setService(CompoundServiceName $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return string
     */
    public function getGatewayKind(): string
    {
        return $this->GatewayKind;
    }

    /**
     * @param string $GatewayKind
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setGatewayKind(string $GatewayKind): self
    {
        $this->GatewayKind = $GatewayKind;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $Port
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    /**
     * @param string $Protocol
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getHosts(): array
    {
        return $this->Hosts;
    }

    /**
     * @param string[] $Hosts
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setHosts(array $Hosts): self
    {
        $this->Hosts = $Hosts;
        return $this;
    }

    /**
     * @return string
     */
    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    /**
     * @param string $CAFile
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setCAFile(string $CAFile): self
    {
        $this->CAFile = $CAFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    /**
     * @param string $CertFile
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setCertFile(string $CertFile): self
    {
        $this->CertFile = $CertFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    /**
     * @param string $KeyFile
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setKeyFile(string $KeyFile): self
    {
        $this->KeyFile = $KeyFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getSNI(): string
    {
        return $this->SNI;
    }

    /**
     * @param string $SNI
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setSNI(string $SNI): self
    {
        $this->SNI = $SNI;
        return $this;
    }

    /**
     * @return string
     */
    public function getFromWildCard(): string
    {
        return $this->FromWildCard;
    }

    /**
     * @param string $FromWildCard
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayService
     */
    public function setFromWildCard(string $FromWildCard): self
    {
        $this->FromWildCard = $FromWildCard;
        return $this;
    }
}
