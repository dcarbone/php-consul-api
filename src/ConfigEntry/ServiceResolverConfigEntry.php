<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ServiceResolverConfigEntry extends AbstractType implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public string $DefaultSubset;
    /** @var null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset> */
    public null|array $Subsets = null;
    public null|ServiceResolverRedirect $Redirect;
    /** @var null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover> */
    public null|array $Failover = null;
    public Time\Duration $ConnectTimeout;
    public Time\Duration $RequestTimeout;
    public null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality;
    public null|LoadBalancer $LoadBalancer;

    /**
     * @param null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset> $Subsets
     * @param null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover> $Failover
     * @param null|array<string,mixed> $Meta
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        string $DefaultSubnet = '',
        null|array $Subsets = null,
        null|ServiceResolverRedirect $Redirect = null,
        null|array $Failover = null,
        null|string|int|float|\DateInterval|Time\Duration $ConnectTimeout = null,
        null|string|int|float|\DateInterval|Time\Duration $RequestTimeout = null,
        null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality = null,
        null|LoadBalancer $LoadBalancer = null,
        null|array $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Kind = $Kind;
        $this->Name = $Name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->DefaultSubset = $DefaultSubnet;
        $this->setSubsets($Subsets);
        $this->Redirect = $Redirect;
        $this->setFailover($Failover);
        $this->ConnectTimeout = Time::Duration($ConnectTimeout);
        $this->RequestTimeout = Time::Duration($RequestTimeout);
        $this->PrioritizeByLocality = $PrioritizeByLocality;
        $this->LoadBalancer = $LoadBalancer;
        $this->setMeta($Meta);
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function setKind(string $Kind): self
    {
        $this->Kind = $Kind;
        return $this;
    }


    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getDefaultSubset(): string
    {
        return $this->DefaultSubset;
    }

    public function setDefaultSubset(string $DefaultSubset): self
    {
        $this->DefaultSubset = $DefaultSubset;
        return $this;
    }

    /**
     * @return null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset>
     */
    public function getSubsets(): null|array
    {
        return $this->Subsets;
    }

    public function setSubsetKey(string $key, ServiceResolverSubset $subset): self
    {
        if (null === $this->Subsets) {
            $this->Subsets = [];
        }
        $this->Subsets[$key] = $subset;
        return $this;
    }

    /**
     * @param null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset> $Subsets
     */
    public function setSubsets(null|array $Subsets): self
    {
        $this->Subsets = null;
        if (null === $Subsets) {
            return $this;
        }
        foreach ($Subsets as $k => $v) {
            $this->setSubsetKey($k, $v);
        }
        return $this;
    }

    public function getRedirect(): null|ServiceResolverRedirect
    {
        return $this->Redirect;
    }

    public function setRedirect(null|ServiceResolverRedirect $Redirect): self
    {
        $this->Redirect = $Redirect;
        return $this;
    }

    /**
     * @return null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover>
     */
    public function getFailover(): null|array
    {
        return $this->Failover;
    }

    public function setFailoverKey(string $key, ServiceResolverFailover $failover): self
    {
        if (null === $this->Failover) {
            $this->Failover = [];
        }
        $this->Failover[$key] = $failover;
        return $this;
    }

    /**
     * @param null|array<string,\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover> $Failover
     */
    public function setFailover(null|array $Failover): self
    {
        $this->Failover = null;
        if (null === $Failover) {
            return $this;
        }
        foreach ($Failover as $k => $v) {
            $this->setFailoverKey($k, $v);
        }
        return $this;
    }

    public function getConnectTimeout(): Time\Duration
    {
        return $this->ConnectTimeout;
    }

    public function setConnectTimeout(Time\Duration $ConnectTimeout): self
    {
        $this->ConnectTimeout = $ConnectTimeout;
        return $this;
    }

    public function getRequestTimeout(): Time\Duration
    {
        return $this->RequestTimeout;
    }

    public function setRequestTimeout(Time\Duration $RequestTimeout): self
    {
        $this->RequestTimeout = $RequestTimeout;
        return $this;
    }

    public function getPrioritizeByLocality(): null|ServiceResolverPrioritizeByLocality
    {
        return $this->PrioritizeByLocality;
    }

    public function setPrioritizeByLocality(null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality): self
    {
        $this->PrioritizeByLocality = $PrioritizeByLocality;
        return $this;
    }

    public function getLoadBalancer(): null|LoadBalancer
    {
        return $this->LoadBalancer;
    }

    public function setLoadBalancer(null|LoadBalancer $LoadBalancer): self
    {
        $this->LoadBalancer = $LoadBalancer;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('default_subset' === $k) {
                $n->DefaultSubset = $v;
            } elseif ('Subsets' === $k) {
                foreach ($v as $kk => $vv) {
                    $n->setSubsetKey($kk, ServiceResolverSubset::jsonUnserialize($vv));
                }
            } elseif ('Redirect' === $k) {
                $n->Redirect = ServiceResolverRedirect::jsonUnserialize($v);
            } elseif ('Failover' === $k) {
                foreach ($v as $kk => $vv) {
                    $n->setFailoverKey($kk, ServiceResolverFailover::jsonUnserialize($vv));
                }
            } elseif ('ConnectTimeout' === $k || 'connect_timeout' === $k) {
                $n->ConnectTimeout = Time::ParseDuration($v);
            } elseif ('RequestTimeout' === $k || 'request_timeout' === $k) {
                $n->RequestTimeout = Time::ParseDuration($v);
            } elseif ('PrioritizeByLocality' === $k || 'prioritize_by_locality' === $k) {
                $n->PrioritizeByLocality = ServiceResolverPrioritizeByLocality::jsonUnserialize($v);
            } elseif ('LoadBalancer' === $k || 'load_balancer' === $k) {
                $n->LoadBalancer = LoadBalancer::jsonUnserialize($v);
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->DefaultSubset) {
            $out->DefaultSubset = $this->DefaultSubset;
        }
        if (null !== $this->Subsets) {
            $out->Subsets = $this->Subsets;
        }
        if (null !== $this->Redirect) {
            $out->Redirect = $this->Redirect;
        }
        if (null !== $this->Failover) {
            $out->Failover = $this->Failover;
        }
        if (0 !== $this->ConnectTimeout->Nanoseconds()) {
            $out->ConnectTimeout = (string)$this->ConnectTimeout;
        }
        if (0 !== $this->RequestTimeout->Nanoseconds()) {
            $out->RequestTimeout = (string)$this->RequestTimeout;
        }
        if (null !== $this->PrioritizeByLocality) {
            $out->PrioritizeByLocality = $this->PrioritizeByLocality;
        }
        if (null !== $this->LoadBalancer) {
            $out->LoadBalancer = $this->LoadBalancer;
        }
        if (null !== $this->Meta) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
