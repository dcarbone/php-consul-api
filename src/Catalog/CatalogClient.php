<?php namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class CatalogClient
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogClient extends AbstractClient
{
    /**
     * @param CatalogRegistration $catalogRegistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function register(CatalogRegistration $catalogRegistration, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', 'v1/catalog/register', $this->Config, $catalogRegistration);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        return [$wm, $err];
    }

    /**
     * @param CatalogDeregistration $catalogDeregistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function deregister(CatalogDeregistration $catalogDeregistration, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', 'v1/catalog/deregister', $this->Config, $catalogDeregistration);
        $r->setWriteOptions($writeOptions);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        return [$wm, $err];
    }

    /**
     * @return array(
     *  @type string[]|null list of datacenters or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function datacenters()
    {
        $r = new Request('get', 'v1/catalog/datacenters', $this->Config);

        list($_, $response, $err) = $this->requireOK($this->doRequest($r));

        if (null !== $err)
            return [null, $err];

        return $this->decodeBody($response);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type CatalogNode[]|null array of catalog nodes or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function nodes(QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/catalog/nodes', $this->Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $nodes = array();
        foreach($data as $v)
        {
            $node = Hydrator::CatalogNode($v);
            $nodes[$node->Node] = $node;
        }

        return [$nodes, $qm, null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     * @type string[]|null list of services or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function services(QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/catalog/services', $this->Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        return [$data, $qm, $err];
    }

    /**
     * @param string $service
     * @param string $tag
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type CatalogService[]|null array of services or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function service($service, $tag = '', QueryOptions $queryOptions = null)
    {
        $r = new Request('get', sprintf('v1/catalog/service/%s', $service), $this->Config);
        $r->setQueryOptions($queryOptions);
        if ('' !== $tag)
            $r->params->set('tag', $tag);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];
        
        list($data, $err) = $this->decodeBody($response);
        
        if (null !== $err)
            return [null, $qm, $err];
        
        $services = array();
        foreach($data as $v)
        {
            $service = Hydrator::CatalogService($v);
            $services[$service->ServiceID] = $service;
        }

        return [$services, $qm, null];
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type CatalogNode node or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function node($node, QueryOptions $queryOptions = null)
    {
        $r = new Request('get', sprintf('v1/catalog/node/%s', $node), $this->Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        return [Hydrator::CatalogNode($data), $qm, null];
    }
}