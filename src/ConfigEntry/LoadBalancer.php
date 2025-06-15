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

class LoadBalancer extends AbstractModel
{
    public string $Policy;
    public null|RingHashConfig $RingHashConfig;
    public null|LeastRequestConfig $LeastRequestConfig;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy> */
    public array $HashPolicies;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy> $HashPolicies
     */
    public function __construct(
        string $Policy = '',
        null|RingHashConfig $RingHashConfig = null,
        null|LeastRequestConfig $LeastRequestConfig = null,
        array $HashPolicies = []
    ) {
        $this->Policy = $Policy;
        $this->RingHashConfig = $RingHashConfig;
        $this->LeastRequestConfig = $LeastRequestConfig;
        $this->setHashPolicies(...$HashPolicies);
    }

    public function getPolicy(): string
    {
        return $this->Policy;
    }

    public function setPolicy(string $Policy): self
    {
        $this->Policy = $Policy;
        return $this;
    }

    public function getRingHashConfig(): null|RingHashConfig
    {
        return $this->RingHashConfig;
    }

    public function setRingHashConfig(null|RingHashConfig $RingHashConfig): self
    {
        $this->RingHashConfig = $RingHashConfig;
        return $this;
    }

    public function getLeastRequestConfig(): null|LeastRequestConfig
    {
        return $this->LeastRequestConfig;
    }

    public function setLeastRequestConfig(null|LeastRequestConfig $LeastRequestConfig): self
    {
        $this->LeastRequestConfig = $LeastRequestConfig;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy>
     */
    public function getHashPolicies(): array
    {
        return $this->HashPolicies;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\HashPolicy ...$HashPolicies
     */
    public function setHashPolicies(HashPolicy ...$HashPolicies): self
    {
        $this->HashPolicies = $HashPolicies;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach($decoded as $k => $v) {
            if ('RingHashConfig' === $k || 'ring_hash_config' === $k) {
                $n->RingHashConfig = RingHashConfig::jsonUnserialize($v);
            } elseif ('LeastRequestConfig' === $k || 'least_request_config' === $k) {
                $n->LeastRequestConfig = LeastRequestConfig::jsonUnserialize($v);
            } elseif ('HashPolicies' === $k || 'hash_policies' === $k) {
                foreach ($v as $hp) {
                    $n->HashPolicies[] = HashPolicy::jsonUnserialize($hp);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if ('' !== $this->Policy) {
            $out->Policy = $this->Policy;
        }
        if (null !== $this->RingHashConfig) {
            $out->RingHashConfig = $this->RingHashConfig;
        }
        if (null !== $this->LeastRequestConfig) {
            $out->LeastRequestConfig = $this->LeastRequestConfig;
        }
        if ([] !== $this->HashPolicies) {
            $out->HashPolicies = $this->HashPolicies;
        }
        return $out;
    }
}