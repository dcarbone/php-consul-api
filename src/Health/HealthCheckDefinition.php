<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

class HealthCheckDefinition extends AbstractModel implements \JsonSerializable
{
    public string $HTTP;
    public array $Header;
    public string $Method;
    public string $Body;
    public bool $TLSSkipVerify;
    public string $TCP;
    public Time\Duration $IntervalDuration;
    public Time\Duration $TimeoutDuration;
    public Time\Duration $DeregisterCriticalServiceAfterDuration;

    public function __construct(
        null|array $data = null,
        string $HTTP = '',
        iterable $Header = [],
        string $Method = '',
        string $Body = '',
        bool $TLSSkipVerify = false,
        string $TCP = '',
        null|int|float|string|\DateInterval|Time\Duration $IntervalDuration = null,
        null|int|float|string|\DateInterval|Time\Duration $TimeoutDuration = null,
        null|int|float|string|\DateInterval|Time\Duration $DeregisterCriticalServiceAfterDuration = null,
    ) {
        $this->HTTP = $HTTP;
        $this->setHeader(...$Header);
        $this->Method = $Method;
        $this->Body = $Body;
        $this->TLSSkipVerify = $TLSSkipVerify;
        $this->TCP = $TCP;
        $this->IntervalDuration = Time::Duration($IntervalDuration);
        $this->TimeoutDuration = Time::Duration($TimeoutDuration);
        $this->DeregisterCriticalServiceAfterDuration = Time::Duration($DeregisterCriticalServiceAfterDuration);
        if (null !== $data && [] !== $data) {
            static::jsonUnserialize((object)$data, $this);
        }
    }

    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    public function setHTTP(string $HTTP): self
    {
        $this->HTTP = $HTTP;
        return $this;
    }

    public function getHeader(): array
    {
        return $this->Header;
    }

    public function setHeader(string ...$Header): self
    {
        $this->Header = $Header;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->Method;
    }

    public function setMethod(string $Method): self
    {
        $this->Method = $Method;
        return $this;
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): self
    {
        $this->Body = $Body;
        return $this;
    }

    public function isTLSSkipVerify(): bool
    {
        return $this->TLSSkipVerify;
    }

    public function setTLSSkipVerify(bool $TLSSkipVerify): self
    {
        $this->TLSSkipVerify = $TLSSkipVerify;
        return $this;
    }

    public function getTCP(): string
    {
        return $this->TCP;
    }

    public function setTCP(string $TCP): self
    {
        $this->TCP = $TCP;
        return $this;
    }

    public function getIntervalDuration(): Time\Duration
    {
        return $this->IntervalDuration;
    }

    public function setIntervalDuration(
        null|int|float|string|\DateInterval|Time\Duration $IntervalDuration
    ): self {
        $this->IntervalDuration = Time::Duration($IntervalDuration);
        return $this;
    }

    public function getTimeoutDuration(): Time\Duration
    {
        return $this->TimeoutDuration;
    }

    public function setTimeoutDuration(
        null|int|float|string|\DateInterval|Time\Duration $TimeoutDuration
    ): self {
        $this->TimeoutDuration = Time::Duration($TimeoutDuration);
        return $this;
    }

    public function getDeregisterCriticalServiceAfterDuration(): Time\Duration
    {
        return $this->DeregisterCriticalServiceAfterDuration;
    }

    public function setDeregisterCriticalServiceAfterDuration(
        null|int|float|string|\DateInterval|Time\Duration $DeregisterCriticalServiceAfterDuration
    ): self {
        $this->DeregisterCriticalServiceAfterDuration = Time::Duration($DeregisterCriticalServiceAfterDuration);
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('Interval' === $k || 'IntervalDuration' === $k) {
                $n->IntervalDuration = Time::Duration($v);
            } elseif ('Timeout' === $k || 'TimeoutDuration' === $k) {
                $n->TimeoutDuration = Time::Duration($v);
            } elseif ('DeregisterCriticalServiceAfter' === $k || 'DeregisterCriticalServiceAfterDuration' === $k) {
                $n->DeregisterCriticalServiceAfterDuration = Time::Duration($v);
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
        $out->HTTP = $this->HTTP;
        $out->Header = $this->Header;
        $out->Method = $this->Method;
        $out->Body = $this->Body;
        $out->TLSSkipVerify = $this->TLSSkipVerify;
        $out->TCP = $this->TCP;
        $out->Interval = (string)$this->IntervalDuration;
        $out->Timeout = (string)$this->TimeoutDuration;
        $out->DeregisterCriticalServiceAfter = (string)$this->DeregisterCriticalServiceAfterDuration;
        return $out;
    }
}
