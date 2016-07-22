<?php namespace DCarbone\PHPConsulAPI\Health;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractApiClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\HttpRequest;
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\QueryOptions;

/**
 * Class HealthClient
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthClient extends AbstractApiClient
{
    /**
     * @param string $node
     * @param QueryOptions|null $queryOptions
     * @return array(
     *  @type HealthCheck[]|null list of health checks or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query meta
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function node($node, QueryOptions $queryOptions = null)
    {
        if (!is_string($node))
        {
            return [null, null, new Error(sprintf(
                '%s::node - $node must be string, %s seen.',
                get_class($this),
                gettype($node)
            ))];
        }

        $r = new HttpRequest('get', sprintf('v1/health/node/%s', $node), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);
        
        if (null !== $err)
            return [null, $qm, $err];
        
        $checks = array();
        foreach($data as $check)
        {
            $checks[] = Hydrator::HealthCheck($check);
        }

        return [$checks, $qm, null];
    }

    /**
     * @param string $service
     * @param QueryOptions|null $queryOptions
     * @return array(
     * @type HealthCheck[]|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function checks($service, QueryOptions $queryOptions = null)
    {
        if (!is_string($service))
        {
            return [null, null, new Error(sprintf(
                '%s::checks - $service must be string, %s seen.',
                get_class($this),
                gettype($service)
            ))];
        }

        $r = new HttpRequest('get', sprintf('v1/health/checks/%s', $service), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $checks = array();
        foreach($data as $check)
        {
            $checks[] = Hydrator::HealthCheck($check);
        }

        return [$checks, $qm, null];
    }

    /**
     * @param string $service
     * @param QueryOptions|null $queryOptions
     * @return array(
     * @type ServiceEntry[]|null list of service entries or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function service($service, QueryOptions $queryOptions = null)
    {
        if (!is_string($service))
        {
            return [null, null, new Error(sprintf(
                '%s::service - $service must be string, %s seen.',
                get_class($this),
                gettype($service)
            ))];
        }

        $r = new HttpRequest('get', sprintf('v1/health/checks/%s', $service), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $services = array();
        foreach($data as $service)
        {
            $services[] = Hydrator::ServiceEntry($service);
        }

        return [$services, $qm, null];
    }

    /**
     * @param string $state
     * @param QueryOptions|null $queryOptions
     * @return array(
     *  @type HealthCheck[]|null array of heath checks or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta|null query metadata or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function state($state, QueryOptions $queryOptions = null)
    {
        static $validStates = array('any', 'warning', 'critical', 'passing', 'unknown');

        if (!is_string($state) || !in_array($state, $validStates, true))
        {
            return [null, null, new Error(sprintf(
                '%s::state - "$state" must be string with value of ["%s"].  %s seen.',
                get_class($this),
                implode('", "', $validStates),
                is_string($state) ? $state : gettype($state)
            ))];
        }

        $r = new HttpRequest('get', sprintf('v1/health/state/%s', $state), $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $checks = array();
        foreach($data as $check)
        {
            $checks[] = Hydrator::HealthCheck($check);
        }

        return [$checks, $qm, null];
    }
}