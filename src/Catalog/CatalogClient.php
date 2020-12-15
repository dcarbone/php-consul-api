<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\ValuedStringsResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class CatalogClient
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration $catalogRegistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Register(CatalogRegistration $catalogRegistration, WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('PUT', 'v1/catalog/register', $this->config, $catalogRegistration);
        $r->setWriteOptions($opts);

        [$duration, $_, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        return new WriteResponse($this->buildWriteMeta($duration), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration $catalogDeregistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Deregister(CatalogDeregistration $catalogDeregistration, WriteOptions $opts = null): WriteResponse
    {
        $r = new Request('PUT', 'v1/catalog/deregister', $this->config, $catalogDeregistration);
        $r->setWriteOptions($opts);

        [$duration, $_, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new WriteResponse(null, $err);
        }

        return new WriteResponse($this->buildWriteMeta($duration), null);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ValuedStringsResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Datacenters(): ValuedStringsResponse
    {
        $r = new Request('GET', 'v1/catalog/datacenters', $this->config);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$_, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ValuedStringsResponse(null, $err);
        }
        [$data, $err] = $this->decodeBody($response->getBody());
        return new ValuedStringsResponse($data, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Nodes(QueryOptions $opts = null): CatalogNodesResponse
    {
        $r = new Request('GET', 'v1/catalog/nodes', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new CatalogNodesResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new CatalogNodesResponse(null, null, $err);
        }

        return new CatalogNodesResponse($data, $this->buildQueryMeta($duration, $response, $r->getUri()), null);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringsResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Services(QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        $r = new Request('GET', 'v1/catalog/services', $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new ValuedQueryStringsResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new ValuedQueryStringsResponse(null, null, $err);
        }

        return new ValuedQueryStringsResponse($data, $this->buildQueryMeta($duration, $response, $r->getUri()), $err);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodeServicesListResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function NodeServicesList(string $node, QueryOptions $opts = null): CatalogNodeServicesListResponse
    {
        $r = new Request(HTTP\MethodGet, sprintf('v1/catalog/node-services/%s', urlencode($node)), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new CatalogNodeServicesListResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new CatalogNodeServicesListResponse(null, null, $err);
        }

        return new CatalogNodeServicesListResponse(
            $data,
            $this->buildQueryMeta($duration, $response, $r->getUri()),
            $err
        );
    }

    /**
     * @param string $service
     * @param array $tags
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ServiceMultipleTags(
        string $service,
        array $tags,
        QueryOptions $opts = null
    ): CatalogServicesResponse {
        $r = new Request('GET', sprintf('v1/catalog/service/%s', $service), $this->config);
        $r->setQueryOptions($opts);
        if ([] !== $tags) {
            $r->params->set('tag', ...$tags);
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new CatalogServicesResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new CatalogServicesResponse(null, null, $err);
        }

        return new CatalogServicesResponse($data, $this->buildQueryMeta($duration, $response, $r->getUri()), null);
    }

    /**
     * @param string $service
     * @param string $tag
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Service(string $service, string $tag = '', QueryOptions $opts = null): CatalogServicesResponse
    {
        return $this->ServiceMultipleTags($service, '' !== $tag ? [$tag] : [], $opts);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodeResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Node(string $node, QueryOptions $opts = null): CatalogNodeResponse
    {
        $r = new Request('GET', sprintf('v1/catalog/node/%s', $node), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new CatalogNodeResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new CatalogNodeResponse(null, null, $err);
        }

        return new CatalogNodeResponse($data, $this->buildQueryMeta($duration, $response, $r->getUri()), null);
    }

    /**
     * @param string $gateway
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayServicesResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function GatewayServices(string $gateway, QueryOptions $opts = null): GatewayServicesResponse
    {
        $r = new Request(HTTP\MethodGet, sprintf('v1/catalog/gateway-services/%s', urlencode($gateway)), $this->config);
        $r->setQueryOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->requireOK($this->do($r));
        if (null !== $err) {
            return new GatewayServicesResponse(null, null, $err);
        }

        [$data, $err] = $this->decodeBody($response->getBody());
        if (null !== $err) {
            return new GatewayServicesResponse(null, null, $err);
        }

        return new GatewayServicesResponse($data, $this->buildQueryMeta($duration, $response, $r->getUri()), null);
    }
}