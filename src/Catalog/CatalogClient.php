<?php namespace DCarbone\PHPConsulAPI\Catalog;

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
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration $catalogRegistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Register(CatalogRegistration $catalogRegistration, WriteOptions $options = null): array
    {
        $r = new Request('PUT', 'v1/catalog/register', $this->config, $catalogRegistration);
        $r->setWriteOptions($options);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration $catalogDeregistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\WriteMeta write meta data
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Deregister(CatalogDeregistration $catalogDeregistration, WriteOptions $options = null): array
    {
        $r = new Request('PUT', 'v1/catalog/deregister', $this->config, $catalogDeregistration);
        $r->setWriteOptions($options);

        list($duration, $_, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return [$this->buildWriteMeta($duration), null];
    }

    /**
     * @return array(
     * @type string[]|null list of datacenters or null on error
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Datacenters(): array
    {
        $r = new Request('GET', 'v1/catalog/datacenters', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($_, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, $err];
        }

        return $this->decodeBody($response->getBody());
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Catalog\CatalogNode[]|null array of catalog nodes or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Nodes(QueryOptions $options = null): array
    {
        $r = new Request('GET', 'v1/catalog/nodes', $this->config);
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

        $nodes = [];
        foreach ($data as $v) {
            $nodes[] = new CatalogNode($v);
        }

        return [$nodes, $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type array|null list of services or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Services(QueryOptions $options = null): array
    {
        $r = new Request('GET', 'v1/catalog/services', $this->config);
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

        return [$data, $this->buildQueryMeta($duration, $response, $r->getUri()), $err];
    }

    /**
     * @param string $service
     * @param string $tag
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Catalog\CatalogService[]|null array of services or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Service(string $service, string $tag = '', QueryOptions $options = null): array
    {
        $r = new Request('GET', sprintf('v1/catalog/service/%s', $service), $this->config);
        $r->setQueryOptions($options);
        if ('' !== $tag) {
            $r->Params->set('tag', $tag);
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        list($data, $err) = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return [null, null, $err];
        }

        $services = [];
        foreach ($data as $v) {
            $services[] = new CatalogService($v);
        }

        return [$services, $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type \DCarbone\PHPConsulAPI\Catalog\CatalogNode|null node or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function Node(string $node, QueryOptions $options = null): array
    {
        $r = new Request('GET', sprintf('v1/catalog/node/%s', $node), $this->config);
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

        return [new CatalogNode($data), $this->buildQueryMeta($duration, $response, $r->getUri()), null];
    }
}
