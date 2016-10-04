<?php namespace DCarbone\PHPConsulAPI\Client;

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

use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\Http\Request;
use DCarbone\PHPConsulAPI\Hydration\Hydrator;
use DCarbone\PHPConsulAPI\Model\HealthCheck;
use DCarbone\PHPConsulAPI\Model\QueryOptions;
use DCarbone\PHPConsulAPI\Model\ServiceEntry;

/**
 * Class Health
 * @package DCarbone\PHPConsulAPI\Client
 */
class Health extends AbstractClient
{
    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\Model\QueryOptions|null $queryOptions
     * @return array(
     *  @type HealthCheck[]|null list of health checks or null on error
     *  @type \DCarbone\PHPConsulAPI\Model\QueryMeta query meta
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

        $r = new Request('get', sprintf('v1/health/node/%s', $node), $this->Config);
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
     * @param \DCarbone\PHPConsulAPI\Model\QueryOptions|null $queryOptions
     * @return array(
     * @type HealthCheck[]|null list of health checks or null on error
     * @type \DCarbone\PHPConsulAPI\Model\QueryMeta query metadata
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

        $r = new Request('get', sprintf('v1/health/checks/%s', $service), $this->Config);
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
     * @param string $tag
     * @param bool $passingOnly
     * @param \DCarbone\PHPConsulAPI\Model\QueryOptions|null $queryOptions
     * @return array (
     * @type ServiceEntry[]|null list of service entries or null on error
     * @type \DCarbone\PHPConsulAPI\Model\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function service($service, $tag = '', $passingOnly = false, QueryOptions $queryOptions = null)
    {
        if (!is_string($service))
        {
            return [null, null, new Error(sprintf(
                '%s::service - $service must be string, %s seen.',
                get_class($this),
                gettype($service)
            ))];
        }

        $r = new Request('get', sprintf('v1/health/service/%s', $service), $this->Config);
        $r->setQueryOptions($queryOptions);
        if ('' !== $tag)
            $r->params->set('tag', $tag);
        if ($passingOnly)
            $r->params->set('passing', '1');

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
     *  @type \DCarbone\PHPConsulAPI\Model\QueryMeta|null query metadata or null on error
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

        $r = new Request('get', sprintf('v1/health/state/%s', $state), $this->Config);
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