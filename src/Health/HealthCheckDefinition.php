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
use DCarbone\Go\Time\Duration;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Operator\ReadableDuration;
use DCarbone\PHPConsulAPI\Transcoding;

class HealthCheckDefinition extends AbstractModel implements \JsonSerializable
{
    public const FIELDS = [
        self::FIELD_INTERVAL_DURATION                          => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_SKIP               => true,
        ],
        self::FIELD_TIMEOUT_DURATION                           => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_SKIP               => true,
        ],
        self::FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER_DURATION => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_DURATION,
            Transcoding::FIELD_SKIP               => true,
        ],
        self::FIELD_TIMEOUT                                    => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
        ],
        self::FIELD_INTERVAL                                   => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
        ],
        self::FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER          => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => [ReadableDuration::class, 'unmarshalJSON'],
        ],
    ];

    private const FIELD_HTTP                                       = 'HTTP';
    private const FIELD_HEADER                                     = 'Header';
    private const FIELD_METHOD                                     = 'Method';
    private const FIELD_BODY                                       = 'Body';
    private const FIELD_TLS_SKIP_VERIFY                            = 'TLSSkipVerify';
    private const FIELD_TCP                                        = 'TCP';
    private const FIELD_INTERVAL_DURATION                          = 'IntervalDuration';
    private const FIELD_TIMEOUT_DURATION                           = 'TimeoutDuration';
    private const FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER_DURATION = 'DeregisterCriticalServiceAfterDuration';
    private const FIELD_INTERVAL                                   = 'Interval';
    private const FIELD_TIMEOUT                                    = 'Timeout';
    private const FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER          = 'DeregisterCriticalServiceAfter';

    public string $HTTP = '';
    public array $Header = [];
    public string $Method = '';
    public string $Body = '';
    public bool $TLSSkipVerify = false;
    public string $TCP = '';
    public Duration $IntervalDuration;
    public Duration $TimeoutDuration;
    public Duration $DeregisterCriticalServiceAfterDuration;

    public ReadableDuration $Interval;
    public ReadableDuration $Timeout;
    public ReadableDuration $DeregisterCriticalServiceAfter;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->Interval)) {
            $this->Interval = new ReadableDuration();
        }
        if (!isset($this->Timeout)) {
            $this->Timeout = new ReadableDuration();
        }
        if (!isset($this->DeregisterCriticalServiceAfter)) {
            $this->DeregisterCriticalServiceAfter = new ReadableDuration();
        }

        if (!isset($this->IntervalDuration)) {
            $this->IntervalDuration = Time::ParseDuration((string)$this->Interval);
        } else {
            $this->Interval = ReadableDuration::fromDuration((string)$this->IntervalDuration);
        }
        if (!isset($this->TimeoutDuration)) {
            $this->TimeoutDuration = Time::ParseDuration((string)$this->Timeout);
        } else {
            $this->Timeout = ReadableDuration::fromDuration((string)$this->TimeoutDuration);
        }
        if (!isset($this->DeregisterCriticalServiceAfterDuration)) {
            $this->DeregisterCriticalServiceAfterDuration = Time::ParseDuration(
                (string)$this->DeregisterCriticalServiceAfter
            );
        } else {
            $this->DeregisterCriticalServiceAfter = ReadableDuration::fromDuration(
                (string)$this->DeregisterCriticalServiceAfterDuration
            );
        }
    }

    public function getHTTP(): string
    {
        return $this->HTTP;
    }

    public function getHeader(): array
    {
        return $this->Header;
    }

    public function getMethod(): string
    {
        return $this->Method;
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

    public function getTCP(): string
    {
        return $this->TCP;
    }

    public function getIntervalDuration(): ?Duration
    {
        return $this->IntervalDuration;
    }

    public function getTimeoutDuration(): ?Duration
    {
        return $this->TimeoutDuration;
    }

    public function getDeregisterCriticalServiceAfterDuration(): ?Duration
    {
        return $this->DeregisterCriticalServiceAfterDuration;
    }

    public function getInterval(): ?ReadableDuration
    {
        return $this->Interval;
    }

    public function getTimeout(): ?ReadableDuration
    {
        return $this->Timeout;
    }

    public function getDeregisterCriticalServiceAfter(): ?ReadableDuration
    {
        return $this->DeregisterCriticalServiceAfter;
    }

    public function jsonSerialize(): array
    {
        // prepare base definition
        $prep = [
            self::FIELD_HTTP            => $this->HTTP,
            self::FIELD_HEADER          => $this->Header,
            self::FIELD_METHOD          => $this->Method,
            self::FIELD_BODY            => $this->Body,
            self::FIELD_TLS_SKIP_VERIFY => $this->TLSSkipVerify,
            self::FIELD_TCP             => $this->TCP,

        ];
        if (0 !== $this->IntervalDuration->Nanoseconds()) {
            $prep[self::FIELD_INTERVAL] = (string)$this->IntervalDuration;
        } elseif (0 !== $this->Interval->Nanoseconds()) {
            $prep[self::FIELD_INTERVAL] = (string)$this->Interval;
        }
        if (0 !== $this->TimeoutDuration->Nanoseconds()) {
            $prep[self::FIELD_TIMEOUT] = (string)$this->TimeoutDuration;
        } elseif (0 !== $this->Timeout->Nanoseconds()) {
            $prep[self::FIELD_TIMEOUT] = (string)$this->Timeout;
        }
        if (0 !== $this->DeregisterCriticalServiceAfterDuration->Nanoseconds()) {
            $prep[self::FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER] = (string)$this->DeregisterCriticalServiceAfterDuration;
        } elseif (0 !== $this->DeregisterCriticalServiceAfter->Nanoseconds()) {
            $prep[self::FIELD_DEREGISTER_CRITICAL_SERVICE_AFTER] = (string)$this->DeregisterCriticalServiceAfter;
        }

        // handle per-field marshalling
        $out = [];
        foreach ($prep as $field => $value) {
            $this->marshalField($out, $field, $value);
        }

        return $out;
    }
}
