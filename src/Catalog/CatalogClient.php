<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\ValuedQueryStringsResponse;
use DCarbone\PHPConsulAPI\ValuedStringsResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

class CatalogClient extends AbstractClient
{
    public function Register(CatalogRegistration $catalogRegistration, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/catalog/register', $catalogRegistration, $opts);
    }

    public function Deregister(CatalogDeregistration $catalogDeregistration, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/catalog/deregister', $catalogDeregistration, $opts);
    }

    public function Datacenters(): ValuedStringsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/catalog/datacenters', null));
        $ret  = new ValuedStringsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Nodes(null|QueryOptions $opts = null): NodesResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/catalog/nodes', $opts));
        $ret  = new NodesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Services(null|QueryOptions $opts = null): ValuedQueryStringsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/catalog/services', $opts));
        $ret  = new ValuedQueryStringsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function NodeServicesList(string $node, null|QueryOptions $opts = null): CatalogNodeServicesListResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/catalog/node-services/%s', urlencode($node)), $opts));
        $ret  = new CatalogNodeServicesListResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function ServiceMultipleTags(
        string $service,
        array $tags,
        null|QueryOptions $opts = null
    ): CatalogServicesResponse {
        $r = $this->_newGetRequest(sprintf('v1/catalog/service/%s', $service), $opts);
        if ([] !== $tags) {
            $r->params->set('tag', ...$tags);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new CatalogServicesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Service(string $service, string $tag = '', null|QueryOptions $opts = null): CatalogServicesResponse
    {
        return $this->ServiceMultipleTags($service, '' !== $tag ? [$tag] : [], $opts);
    }

    public function Node(string $node, null|QueryOptions $opts = null): CatalogNodeResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/catalog/node/%s', $node), $opts));
        $ret  = new CatalogNodeResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function GatewayServices(string $gateway, null|QueryOptions $opts = null): GatewayServicesResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/catalog/gateway-services/%s', urlencode($gateway)), $opts));
        $ret  = new GatewayServicesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
