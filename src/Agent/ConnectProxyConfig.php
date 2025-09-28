<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

class ConnectProxyConfig extends AbstractModel
{
    public string $ProxyServiceID;
    public string $TargetServiceID;
    public string $TargetServiceName;
    public string $ContentHash;
    /** @var array<string,mixed> */
    public array $Config;
    /** @var array<\DCarbone\PHPConsulAPI\Agent\Upstream> */
    public array $Upstreams;

    /**
     * @param array<string,mixed>|\stdClass|null $Config
     * @param array<\DCarbone\PHPConsulAPI\Agent\Upstream> $Upstreams
     */
    public function __construct(
        string $ProxyServiceID = '',
        string $TargetServiceID = '',
        string $TargetServiceName = '',
        string $ContentHash = '',
        null|\stdClass|array $Config = null,
        array $Upstreams = [],
    ) {
        $this->ProxyServiceID = $ProxyServiceID;
        $this->TargetServiceID = $TargetServiceID;
        $this->TargetServiceName = $TargetServiceName;
        $this->ContentHash = $ContentHash;
        $this->setConfig($Config);
        $this->setUpstreams(...$Upstreams);
}

    public function getProxyServiceID(): string
    {
        return $this->ProxyServiceID;
    }

    public function setProxyServiceID(string $ProxyServiceID): self
    {
        $this->ProxyServiceID = $ProxyServiceID;
        return $this;
    }

    public function getTargetServiceID(): string
    {
        return $this->TargetServiceID;
    }

    public function setTargetServiceID(string $TargetServiceID): self
    {
        $this->TargetServiceID = $TargetServiceID;
        return $this;
    }

    public function getTargetServiceName(): string
    {
        return $this->TargetServiceName;
    }

    public function setTargetServiceName(string $TargetServiceName): self
    {
        $this->TargetServiceName = $TargetServiceName;
        return $this;
    }

    public function getContentHash(): string
    {
        return $this->ContentHash;
    }

    public function setContentHash(string $ContentHash): self
    {
        $this->ContentHash = $ContentHash;
        return $this;
    }

    /**
     * @return null|array<string,mixed>
     */
    public function getConfig(): null|array
    {
        return $this->Config;
    }

    /**
     * @param \stdClass|array<string,mixed>|null $Config
     * @return $this
     */
    public function setConfig(null|\stdClass|array $Config): self
    {
        if (null === $Config) {
            unset($this->Config);
            return $this;
        }
        $this->Config = [];
        foreach ($Config as $k => $v) {
            $this->Config[$k] = $v;
        }
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\Upstream[]
     */
    public function getUpstreams(): array
    {
        return $this->Upstreams;
    }

    public function setUpstreams(Upstream ...$Upstreams): self
    {
        $this->Upstreams = $Upstreams;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Upstreams' === $k) {
                $n->Upstreams = [];
                foreach ($v as $vv) {
                    $n->Upstreams[] = Upstream::jsonUnserialize($vv);
                }
            } elseif ('Config' === $k) {
                $n->setConfig($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ProxyServiceID = $this->ProxyServiceID;
        $out->TargetServiceID = $this->TargetServiceID;
        $out->TargetServiceName = $this->TargetServiceName;
        $out->ContentHash = $this->ContentHash;
        $out->Config = $this->getConfig();
        $out->Upstreams = $this->Upstreams;
        return $out;
    }
}
