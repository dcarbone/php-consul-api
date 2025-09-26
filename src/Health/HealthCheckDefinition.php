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
use DCarbone\PHPConsulAPI\Values;

class HealthCheckDefinition extends AbstractModel implements \JsonSerializable
{
    public string $HTTP;
    public Values $Header;
    public string $Method;
    public string $Body;
    public bool $TLSSkipVerify;
    public string $TCP;
    public bool $TCPUseTLS;
    public string $UDP;
    public string $GRPC;
    public string $OSService;
    public bool $GRPCUseTLS;
    public Time\Duration $IntervalDuration;
    public Time\Duration $TimeoutDuration;
    public Time\Duration $DeregisterCriticalServiceAfterDuration;

    /**
     * @param array<string,array<string>>|\DCarbone\PHPConsulAPI\Values|null $Header
     */
    public function __construct(
        string $HTTP = '',
        null|array|\stdClass|Values $Header = null,
        string $Method = '',
        string $Body = '',
        bool $TLSSkipVerify = false,
        string $TCP = '',
        string $UDP = '',
        string $GRPC = '',
        string $OSService = '',
        bool $GRPCUseTLS = false,
        null|int|float|string|\DateInterval|Time\Duration $IntervalDuration = null,
        null|int|float|string|\DateInterval|Time\Duration $TimeoutDuration = null,
        null|int|float|string|\DateInterval|Time\Duration $DeregisterCriticalServiceAfterDuration = null,
    ) {
        $this->HTTP = $HTTP;
        $this->setHeader($Header);
        $this->Method = $Method;
        $this->Body = $Body;
        $this->TLSSkipVerify = $TLSSkipVerify;
        $this->TCP = $TCP;
        $this->UDP = $UDP;
        $this->GRPC = $GRPC;
        $this->OSService = $OSService;
        $this->GRPCUseTLS = $GRPCUseTLS;
        $this->IntervalDuration = Time::Duration($IntervalDuration);
        $this->TimeoutDuration = Time::Duration($TimeoutDuration);
        $this->DeregisterCriticalServiceAfterDuration = Time::Duration($DeregisterCriticalServiceAfterDuration);
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

    public function getHeader(): Values
    {
        return $this->Header;
    }

    /**
     * @param array<string,array<string>>|\DCarbone\PHPConsulAPI\Values|null $Header
     * @return $this
     */
    public function setHeader(null|array|\stdClass|Values $Header = []): self
    {
        if (null === $Header) {
            $this->Header = new Values();
            return $this;
        }
        if (!$Header instanceof Values) {
            $Header = Values::fromArray((array)$Header);
        }
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

    public function isTCPUseTLS(): bool
    {
        return $this->TCPUseTLS;
    }

    public function setTCPUseTLS(bool $TCPUseTLS): HealthCheckDefinition
    {
        $this->TCPUseTLS = $TCPUseTLS;
        return $this;
    }

    public function getUDP(): string
    {
        return $this->UDP;
    }

    public function setUDP(string $UDP): HealthCheckDefinition
    {
        $this->UDP = $UDP;
        return $this;
    }

    public function getGRPC(): string
    {
        return $this->GRPC;
    }

    public function setGRPC(string $GRPC): HealthCheckDefinition
    {
        $this->GRPC = $GRPC;
        return $this;
    }

    public function getOSService(): string
    {
        return $this->OSService;
    }

    public function setOSService(string $OSService): HealthCheckDefinition
    {
        $this->OSService = $OSService;
        return $this;
    }

    public function isGRPCUseTLS(): bool
    {
        return $this->GRPCUseTLS;
    }

    public function setGRPCUseTLS(bool $GRPCUseTLS): HealthCheckDefinition
    {
        $this->GRPCUseTLS = $GRPCUseTLS;
        return $this;
    }

    public function getIntervalDuration(): Time\Duration
    {
        return $this->IntervalDuration;
    }

    public function setIntervalDuration(null|int|float|string|\DateInterval|Time\Duration $IntervalDuration): self
    {
        $this->IntervalDuration = Time::Duration($IntervalDuration);
        return $this;
    }

    public function getTimeoutDuration(): Time\Duration
    {
        return $this->TimeoutDuration;
    }

    public function setTimeoutDuration(null|int|float|string|\DateInterval|Time\Duration $TimeoutDuration): self
    {
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Interval' === $k || 'IntervalDuration' === $k) {
                $n->IntervalDuration = Time::Duration($v);
            } elseif ('Timeout' === $k || 'TimeoutDuration' === $k) {
                $n->TimeoutDuration = Time::Duration($v);
            } elseif ('DeregisterCriticalServiceAfter' === $k || 'DeregisterCriticalServiceAfterDuration' === $k) {
                $n->DeregisterCriticalServiceAfterDuration = Time::Duration($v);
            } elseif ('Header' === $k) {
                $n->setHeader($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->HTTP = $this->HTTP;
        $out->Header = $this->Header;
        $out->Method = $this->Method;
        $out->Body = $this->Body;
        $out->TLSSkipVerify = $this->TLSSkipVerify;
        $out->TCP = $this->TCP;
        $out->TCPUseTLS = $this->TCPUseTLS;
        $out->UDP = $this->UDP;
        $out->GRPC = $this->GRPC;
        $out->OSService = $this->OSService;
        $out->GRPCUseTLS = $this->GRPCUseTLS;
        $out->Interval = (string)$this->IntervalDuration;
        $out->Timeout = (string)$this->TimeoutDuration;
        $out->DeregisterCriticalServiceAfter = (string)$this->DeregisterCriticalServiceAfterDuration;
        return $out;
    }
}
