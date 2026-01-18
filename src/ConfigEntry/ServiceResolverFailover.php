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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class ServiceResolverFailover extends AbstractType
{
    public string $Service;
    public string $ServiceSubset;
    public string $Namespace;
    /** @var array<string> */
    public array $Datacenters;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget> */
    public array $Targets;
    public null|ServiceResolverFailoverPolicy $Policy;
    public string $SamenessGroup;

    /**
     * @param array<string> $Datacenters
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget> $Targets
     */
    public function __construct(
        string $Service = '',
        string $ServiceSubset = '',
        string $Namespace = '',
        array $Datacenters = [],
        array $Targets = [],
        null|ServiceResolverFailoverPolicy $Policy = null,
        string $SamenessGroup = ''
    ) {
        $this->Service = $Service;
        $this->ServiceSubset = $ServiceSubset;
        $this->Namespace = $Namespace;
        $this->setDatacenters(...$Datacenters);
        $this->setTargets(...$Targets);
        $this->Policy = $Policy;
        $this->SamenessGroup = $SamenessGroup;
    }

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(string ...$Datacenters): self
    {
        $this->Datacenters = $Datacenters;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget>
     */
    public function getTargets(): array
    {
        return $this->Targets;
    }

    public function setTargets(ServiceResolverFailoverTarget ...$Targets): self
    {
        $this->Targets = $Targets;
        return $this;
    }

    public function getPolicy(): null|ServiceResolverFailoverPolicy
    {
        return $this->Policy;
    }

    public function setPolicy(null|ServiceResolverFailoverPolicy $Policy): self
    {
        $this->Policy = $Policy;
        return $this;
    }

    public function getSamenessGroup(): string
    {
        return $this->SamenessGroup;
    }

    public function setSamenessGroup(string $SamenessGroup): self
    {
        $this->SamenessGroup = $SamenessGroup;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Targtes' === $k) {
                $n->Targets = [];
                foreach ($v as $vv) {
                    $n->Targets[] = ServiceResolverFailoverTarget::jsonUnserialize($vv);
                }
            } elseif ('service_subset' === $k) {
                $n->ServiceSubset = $v;
            } elseif ('sameness_group' === $k) {
                $n->SamenessGroup = $v;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ('' !== $this->Service) {
            $out->Service = $this->Service;
        }
        if ('' !== $this->ServiceSubset) {
            $out->ServiceSubset = $this->ServiceSubset;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ([] !== $this->Datacenters) {
            $out->Datacenters = $this->Datacenters;
        }
        if ([] !== $this->Targets) {
            $out->Targets = $this->Targets;
        }
        if (null !== $this->Policy) {
            $out->Policy = $this->Policy;
        }
        if ('' !== $this->SamenessGroup) {
            $out->SamenessGroup = $this->SamenessGroup;
        }
        return $out;
    }
}
