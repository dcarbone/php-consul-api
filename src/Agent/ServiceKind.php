<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

enum ServiceKind: string
{
    // Typical is a typical, classic Consul service. This is
    // represented by the absence of a value. This was chosen for ease of
    // backwards compatibility: existing services in the catalog would
    // default to the typical service.
    case Typical = '';

    // ConnectProxy is a proxy for the Connect feature. This
    // service proxies another service within Consul and speaks the connect
    // protocol.
    case ConnectProxy = 'connect-proxy';

    // MeshGateway is a Mesh Gateway for the Connect feature. This
    // service will proxy connections based off the SNI header set by other
    // connect proxies
    case MeshGateway = 'mesh-gateway';

    // TerminatingGateway is a Terminating Gateway for the Connect
    // feature. This service will proxy connections to services outside the mesh.
    case TerminatingGateway = 'terminating-gateway';

    // IngressGateway is an Ingress Gateway for the Connect feature.
    // This service will ingress connections based of configuration defined in
    // the ingress-gateway config entry.
    case IngressGateway = 'ingress-gateway';

    // APIGateway is an API Gateway for the Connect feature.
    // This service will ingress connections based of configuration defined in
    // the api-gateway config entry.
    case APIGateway = 'api-gateway';
}
