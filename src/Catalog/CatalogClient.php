<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\ValuedStringsResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class CatalogClient
 */
class CatalogClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration $catalogRegistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Register(CatalogRegistration $catalogRegistration, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/catalog/register', $catalogRegistration, $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration $catalogDeregistration
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Deregister(CatalogDeregistration $catalogDeregistration, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/catalog/deregister', $catalogDeregistration, $opts);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedStringsResponse
     */
    public function Datacenters(): ValuedStringsResponse
    {
        $resp = $this->_doGet('v1/catalog/datacenters', null);
        $ret  = new ValuedStringsResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodesResponse
     */
    public function Nodes(?QueryOptions $opts = null): CatalogNodesResponse
    {
        $resp = $this->_doGet('v1/catalog/nodes', $opts);
        $ret  = new CatalogNodesResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringsResponse
     */
    public function Services(?QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        $resp = $this->_doGet('v1/catalog/services', $opts);
        $ret  = new ValuedQueryStringsResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodeServicesListResponse
     */
    public function NodeServicesList(string $node, ?QueryOptions $opts = null): CatalogNodeServicesListResponse
    {
        $resp = $this->_doGet(\sprintf('v1/catalog/node-services/%s', \urlencode($node)), $opts);
        $ret  = new CatalogNodeServicesListResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $service
     * @param array $tags
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse
     */
    public function ServiceMultipleTags(
        string $service,
        array $tags,
        ?QueryOptions $opts = null
    ): CatalogServicesResponse {
        $r = $this->_newGetRequest(\sprintf('v1/catalog/service/%s', $service), $opts);
        if ([] !== $tags) {
            $r->params->set('tag', ...$tags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new CatalogServicesResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $service
     * @param string $tag
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogServicesResponse
     */
    public function Service(string $service, string $tag = '', ?QueryOptions $opts = null): CatalogServicesResponse
    {
        return $this->ServiceMultipleTags($service, '' !== $tag ? [$tag] : [], $opts);
    }

    /**
     * @param string $node
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNodeResponse
     */
    public function Node(string $node, ?QueryOptions $opts = null): CatalogNodeResponse
    {
        $resp = $this->_doGet(\sprintf('v1/catalog/node/%s', $node), $opts);
        $ret  = new CatalogNodeResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $gateway
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\Catalog\GatewayServicesResponse
     */
    public function GatewayServices(string $gateway, ?QueryOptions $opts = null): GatewayServicesResponse
    {
        $resp = $this->_doGet(\sprintf('v1/catalog/gateway-services/%s', \urlencode($gateway)), $opts);
        $ret  = new GatewayServicesResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }
}
