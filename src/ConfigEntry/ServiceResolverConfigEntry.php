<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\Go\Time;
use stdClass;

use function DCarbone\PHPConsulAPI\_enc_obj_if_valued;

class ServiceResolverConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $Name;
    public string $Partition;
    public string $DefaultSubset;
    public null|\stdClass $Subsets;
    public null|ServiceResolverRedirect $Redirect;
    public null|\stdClass $Failover;
    public Time\Duration $ConnectTimeout;
    public Time\Duration $RequestTimeout;
    public null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality;
    public null|LoadBalancer $LoadBalancer;

    public function __construct(
        string $Kind = '',
        string $Name = '',
        string $Partition = '',
        string $Namespace = '',
        string $DefaultSubnet = '',
        null|\stdClass $Subsets = null,
        null|ServiceResolverRedirect $Redirect = null,
        null|\stdClass $Failover = null,
        null|string|int|float|\DateInterval|Time\Duration $ConnectTimeout = null,
        null|string|int|float|\DateInterval|Time\Duration $RequestTimeout = null,
        null|ServiceResolverPrioritizeByLocality $PrioritizeByLocality = null,
        null|LoadBalancer $LoadBalancer = null,
        null|\stdClass $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
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
        $this->Meta = $Meta;
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

    public function getSubsets(): null|\stdClass
    {
        return $this->Subsets;
    }

    public function setSubsetKey(string $key, ServiceResolverSubset $subset): self
    {
        if (!isset($this->Subsets)) {
            $this->Subsets = new \stdClass();
        }
        $this->Subsets->{$key} = $subset;
        return $this;
    }

    public function setSubsets(\stdClass $subsets): self
    {
        unset($this->Subsets);
        foreach ($subsets as $k => $v) {
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

    public function getFailover(): null|\stdClass
    {
        return $this->Failover;
    }

    public function setFailoverKey(string $key, ServiceResolverFailover $failover): self
    {
        if (!isset($this->Failover)) {
            $this->Failover = new \stdClass();
        }
        $this->Failover->{$key} = $failover;
        return $this;
    }

    public function setFailover(\stdClass $failover): self
    {
        unset($this->Failover);
        foreach ($failover as $k => $v) {
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

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
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
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Kind = $this->Kind;
        $out->Name = $this->Name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->DefaultSubset) {
            $out->default_subset = $this->DefaultSubset;
        }
        _enc_obj_if_valued($out, 'Subsets', $this->Subsets);
        if (null !== $this->Redirect) {
            $out->Redirect = $this->Redirect;
        }
        _enc_obj_if_valued($out, 'Failover', $this->Failover);
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
