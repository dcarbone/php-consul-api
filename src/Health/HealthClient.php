<?php namespace DCarbone\PHPConsulAPI\Health;

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
class HealthClient extends AbstractClient {
    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Health\HealthChecks|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query meta
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Node(string $node, QueryOptions $options = null): array {
        $r = new Request('GET', sprintf('v1/health/node/%s', $node), $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return [new HealthChecks($data), $qm, null];
    }

    /**
     * @param string $service
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Health\HealthChecks|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Checks(string $service, QueryOptions $options = null): array {
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $r = new Request('GET', sprintf('v1/health/checks/%s', $service), $this->config);
        $r->setQueryOptions($options);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return [new HealthChecks($data), $qm, null];
    }

    /**
     * @param string $service
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array (
     * @type \DCarbone\PHPConsulAPI\Health\ServiceEntry[]|null list of service entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Service(string $service,
                            string $tag = '',
                            bool $passingOnly = false,
                            QueryOptions $options = null): array {
        $r = new Request('GET', sprintf('v1/health/service/%s', $service), $this->config);
        $r->setQueryOptions($options);
        if ('' !== $tag) {
            $r->Params->set('tag', $tag);
        }
        if ($passingOnly) {
            $r->Params->set('passing', '1');
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $services = [];
        foreach ($data as $service) {
            $services[] = new ServiceEntry($service);
        }

        return [$services, $qm, null];
    }

    /**
     * @param string $state
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Health\HealthChecks|null array of heath checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function State(string $state, QueryOptions $options = null): array {
        static $validStates = ['any', 'warning', 'critical', 'passing', 'unknown'];

        if (!in_array($state, $validStates, true)) {
            return [null,
                null,
                new Error(sprintf(
                    '%s::state - "$state" must be string with value of ["%s"].  %s seen.',
                    get_class($this),
                    implode('", "', $validStates),
                    $state
                ))];
        }

        $r = new Request('GET', sprintf('v1/health/state/%s', $state), $this->config);
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        return [new HealthChecks($data), $qm, null];
    }
}