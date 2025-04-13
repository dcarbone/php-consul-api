<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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
        self::FIELD_FROM_WILDCARD => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
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

    public CompoundServiceName $Gateway;
    public CompoundServiceName $Service;
    public string $GatewayKind;
    public int $Port;
    public string $Protocol;
    public array $Hosts;
    public string $CAFile;
    public string $CertFile;
    public string $KeyFile;
    public string $SNI;
    public string $FromWildCard;

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

    public function getGateway(): CompoundServiceName
    {
        return $this->Gateway;
    }

    public function setGateway(CompoundServiceName $Gateway): self
    {
        $this->Gateway = $Gateway;
        return $this;
    }

    public function getService(): CompoundServiceName
    {
        return $this->Service;
    }

    public function setService(CompoundServiceName $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getGatewayKind(): string
    {
        return $this->GatewayKind;
    }

    public function setGatewayKind(string $GatewayKind): self
    {
        $this->GatewayKind = $GatewayKind;
        return $this;
    }

    public function getPort(): int
    {
        return $this->Port;
    }

    public function setPort(int $Port): self
    {
        $this->Port = $Port;
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->Protocol;
    }

    public function setProtocol(string $Protocol): self
    {
        $this->Protocol = $Protocol;
        return $this;
    }

    public function getHosts(): array
    {
        return $this->Hosts;
    }

    public function setHosts(array $Hosts): self
    {
        $this->Hosts = $Hosts;
        return $this;
    }

    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    public function setCAFile(string $CAFile): self
    {
        $this->CAFile = $CAFile;
        return $this;
    }

    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    public function setCertFile(string $CertFile): self
    {
        $this->CertFile = $CertFile;
        return $this;
    }

    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    public function setKeyFile(string $KeyFile): self
    {
        $this->KeyFile = $KeyFile;
        return $this;
    }

    public function getSNI(): string
    {
        return $this->SNI;
    }

    public function setSNI(string $SNI): self
    {
        $this->SNI = $SNI;
        return $this;
    }

    public function getFromWildCard(): string
    {
        return $this->FromWildCard;
    }

    public function setFromWildCard(string $FromWildCard): self
    {
        $this->FromWildCard = $FromWildCard;
        return $this;
    }
}
