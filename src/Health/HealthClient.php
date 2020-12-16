<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Request;

/**
 * Class HealthClient
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthClient extends AbstractClient
{
    private const serviceHealth = 'service';
    private const connectHealth = 'connect';
    private const ingressHealth = 'ingress';

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecksResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Node(string $node, ?QueryOptions $opts = null): HealthChecksResponse
    {
        $r = new Request('GET', sprintf('v1/health/node/%s', $node), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new HealthChecksResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new HealthChecksResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return new HealthChecksResponse($data, $qm, null);
    }

    /**
     * @param string $service
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecksResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Checks(string $service, ?QueryOptions $opts = null): HealthChecksResponse
    {
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $r = new Request('GET', sprintf('v1/health/checks/%s', $service), $this->config);
        $r->setQueryOptions($opts);

        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new HealthChecksResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new HealthChecksResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return new HealthChecksResponse($data, $qm, null);
    }

    /**
     * @param string $service
     * @param array $tags
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServiceMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_service($service, $tags, $passingOnly, $opts, self::serviceHealth);
    }

    /**
     * @param string $service
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Service(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
       ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->ServiceMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    /**
     * @param string $service
     * @param array $tags
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function IngressMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_service($service, $tags, $passingOnly, $opts, self::ingressHealth);
    }

    /**
     * @param string $service
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Ingress(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
        ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->IngressMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    /**
     * @param string $service
     * @param array $tags
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ConnectMultipleTags(
        string $service,
        array $tags = [],
        bool $passingOnly = false,
        ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->_service($service, $tags, $passingOnly, $opts, self::connectHealth);
    }

    /**
     * @param string $service
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Connect(
        string $service,
        string $tag = '',
        bool $passingOnly = false,
        ?QueryOptions $opts = null
    ): ServiceEntriesResponse {
        return $this->ConnectMultipleTags($service, '' !== $tag ? [$tag] : [], $passingOnly, $opts);
    }

    /**
     * @param string $service
     * @param array $tags
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @param string $healthType
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntriesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _service(
        string $service,
        array $tags,
        bool $passingOnly,
        ?QueryOptions $opts,
        string $healthType
    ): ServiceEntriesResponse {
        switch ($healthType) {
            case self::connectHealth:
                $uri = 'v1/health/connect/%s';
                break;
            case self::ingressHealth:
                $uri = 'v1/health/ingress/%s';
                break;
            default:
                $uri = 'v1/health/service/%s';
        }

        $r = new Request('GET', sprintf($uri, $service), $this->config);
        $r->setQueryOptions($opts);
        if ([] !== $tags) {
            $r->params->set('tag', ...$tags);
        }
        if ($passingOnly) {
            $r->params->set('passing', '1');
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ServiceEntriesResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());

        return new ServiceEntriesResponse($data, $qm, $err);
    }

    /**
     * @param string $state
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecksResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function State(string $state, ?QueryOptions $opts = null): HealthChecksResponse
    {
        static $validStates = ['any', 'warning', 'critical', 'passing', 'unknown'];

        if (!in_array($state, $validStates, true)) {
            return new HealthChecksResponse(
                null,
                null,
                new Error(
                    sprintf(
                        '%s::state - "$state" must be string with value of ["%s"].  %s seen.',
                        get_class($this),
                        implode('", "', $validStates),
                        $state
                    )
                )
            );
        }

        $r = new Request('GET', sprintf('v1/health/state/%s', $state), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new HealthChecksResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return new HealthChecksResponse($data, $qm, $err);
    }
}