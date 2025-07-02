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
use DCarbone\PHPConsulAPI\Agent\ServiceKind;

class GatewayService extends AbstractModel
{
    public CompoundServiceName $Gateway;
    public CompoundServiceName $Service;
    public ServiceKind $GatewayKind;
    public int $Port;
    public string $Protocol;
    /** @var array<string> */
    public array $Hosts;
    public string $CAFile;
    public string $CertFile;
    public string $KeyFile;
    public string $SNI;
    public string $FromWildCard;

    public function __construct(
        null|array $data = [], // Deprecated, will be removed.
        null|CompoundServiceName $Gateway = null,
        null|CompoundServiceName $Service = null,
        string|ServiceKind $GatewayKind = '',
        int $Port = 0,
        string $Protocol = '',
        array $Hosts = [],
        string $CAFile = '',
        string $CertFile = '',
        string $KeyFile = '',
        string $SNI = '',
        string $FromWildCard = ''
    )
    {
        $this->Gateway = $Gateway ?? new CompoundServiceName();
        $this->Service = $Service ?? new CompoundServiceName();
        $this->GatewayKind = $GatewayKind instanceof ServiceKind ? $GatewayKind : ServiceKind::from($GatewayKind);
        $this->Port = $Port;
        $this->Protocol = $Protocol;
        $this->setHosts(...$Hosts);
        $this->CAFile = $CAFile;
        $this->CertFile = $CertFile;
        $this->KeyFile = $KeyFile;
        $this->SNI = $SNI;
        $this->FromWildCard = $FromWildCard;
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

    public function getGatewayKind(): ServiceKind
    {
        return $this->GatewayKind;
    }

    public function setGatewayKind(ServiceKind $GatewayKind): self
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

    /**
     * @return array<string>
     */
    public function getHosts(): array
    {
        return $this->Hosts;
    }

    public function setHosts(string ...$Hosts): self
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Gateway' === $k) {
                $n->Gateway = CompoundServiceName::jsonUnserialize($v);
            } else if ('Service' === $k) {
                $n->Service = CompoundServiceName::jsonUnserialize($v);
            } else if ('GatewayKind' === $k) {
                $n->GatewayKind = ServiceKind::from($v);
            } else if ('Hosts' === $k) {
                $n->setHosts(...$v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }


    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Gateway = $this->Gateway;
        $out->Service = $this->Service;
        $out->GatewayKind = $this->GatewayKind->value;
        if (0 !== $this->Port) {
            $out->Port = $this->Port;
        }
        if ('' !== $this->Protocol) {
            $out->Protocol = $this->Protocol;
        }
        if ([] !== $this->Hosts) {
            $out->Hosts = $this->Hosts;
        }
        if ('' !== $this->CAFile) {
            $out->CAFile = $this->CAFile;
        }
        if ('' !== $this->CertFile) {
            $out->CertFile = $this->CertFile;
        }
        if ('' !== $this->KeyFile) {
            $out->KeyFile = $this->KeyFile;
        }
        if ('' !== $this->SNI) {
            $out->SNI = $this->SNI;
        }
        if ('' !== $this->FromWildCard) {
            $out->FromWildCard = $this->FromWildCard;
        }
        return $out;
    }
}
