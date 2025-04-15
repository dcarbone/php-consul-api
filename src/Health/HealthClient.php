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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;

class HealthClient extends AbstractClient
{
    private const serviceHealth = 'service';
    private const connectHealth = 'connect';
    private const ingressHealth = 'ingress';

    public function Node(string $node, null|QueryOptions $opts = null): HealthChecksResponse
    {
        return $this->_getHealthChecks(sprintf('v1/health/node/%s', $node), $opts);
    }

    public function Checks(string $service, null|QueryOptions $opts = null): HealthChecksResponse
    {
        return $this->_getHealthChecks(sprintf('v1/health/checks/%s', $service), $opts);
    }

    public function ServiceMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_getServiceEntries($service, $tags, $passingOnly, $opts, self::serviceHealth);
    }

    public function Service(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->ServiceMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    public function IngressMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_getServiceEntries($service, $tags, $passingOnly, $opts, self::ingressHealth);
    }

    public function Ingress(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->IngressMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    public function ConnectMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_getServiceEntries($service, $tags, $passingOnly, $opts, self::connectHealth);
    }

    public function Connect(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
        null|QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->ConnectMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    public function State(string $state, null|QueryOptions $opts = null): HealthChecksResponse
    {
        static $validStates = ['any', 'warning', 'critical', 'passing', 'unknown'];

        if (!\in_array($state, $validStates, true)) {
            $ret      = new HealthChecksResponse();
            $ret->Err = new Error(
                sprintf(
                    '%s::state - "$state" must be string with value of ["%s"].  %s seen.',
                    static::class,
                    implode('", "', $validStates),
                    $state
                )
            );
            return $ret;
        }

        return $this->_getHealthChecks(sprintf('v1/health/state/%s', $state), $opts);
    }

    protected function _getHealthChecks(string $path, null|QueryOptions $opts): HealthChecksResponse
    {
        $resp = $this->_requireOK($this->_doGet($path, $opts));
        $ret  = new HealthChecksResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    private function _getServiceEntries(
        string $service,
        array $tags,
        bool $passingOnly,
        null|QueryOptions $opts,
        string $healthType
    ): ServiceEntriesResponse {
        $uri = match ($healthType) {
            self::connectHealth => 'v1/health/connect/%s',
            self::ingressHealth => 'v1/health/ingress/%s',
            default => 'v1/health/service/%s',
        };

        $r = $this->_newGetRequest(sprintf($uri, $service), $opts);
        if ([] !== $tags) {
            $r->params->set('tag', ...$tags);
        }
        if ($passingOnly) {
            $r->params->set('passing', '1');
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ServiceEntriesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
