<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\PHPConsulAPI\ACL\ACLClient;
use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\Event\EventClient;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\Operator\OperatorClient;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Status\StatusClient;

class Consul
{
    // "public" constants

    public const HTTPAddrEnvName       = 'CONSUL_HTTP_ADDR';
    public const HTTPTokenEnvName      = 'CONSUL_HTTP_TOKEN';
    public const HTTPTokenFileEnvName  = 'CONSUL_HTTP_TOKEN_FILE';
    public const HTTPAuthEnvName       = 'CONSUL_HTTP_AUTH';
    public const HTTPCAFileEnvName     = 'CONSUL_CACERT';
    public const HTTPClientCertEnvName = 'CONSUL_CLIENT_CERT';
    public const HTTPClientKeyEnvName  = 'CONSUL_CLIENT_KEY';
    public const HTTPSSLEnvName        = 'CONSUL_HTTP_SSL';
    public const HTTPSSLVerifyEnvName  = 'CONSUL_HTTP_SSL_VERIFY';

    public const HealthAny      = 'any';
    public const HealthPassing  = 'passing';
    public const HealthWarning  = 'warning';
    public const HealthCritical = 'critical';
    public const HealthMaint    = 'maintenance';

    public const NodeMaint          = '_node_maintenance';
    public const ServiceMaintPrefix = '_service_maintenance';

    public const AllSegments = '_all';

    public const KVSet            = 'set';
    public const KVDelete         = 'delete';
    public const KVDeleteCAS      = 'delete-cas';
    public const KVDeleteTree     = 'delete-tree';
    public const KVCAS            = 'cas';
    public const KVLock           = 'lock';
    public const KVUnlock         = 'unlock';
    public const KVGet            = 'get';
    public const KVGetTree        = 'get-tree';
    public const KVCheckSession   = 'check-session';
    public const KVCheckIndex     = 'check-index';
    public const KVCheckNotExists = 'check-not-exists';

    public const SessionBehaviorRelease = 'release';
    public const SessionBehaviorDelete  = 'delete';

    public const ServiceKindTypical            = '';
    public const ServiceKindConnectProxy       = 'connect-proxy';
    public const ServiceKindMeshGateway        = 'mesh-gateway';
    public const ServiceKindTerminatingGateway = 'terminating-gateway';
    public const ServiceKindIngressGateway     = 'ingress-gateway';

    public const UpstreamDestTypeService       = 'service';
    public const UpstreamDestTypePreparedQuery = 'prepared_query';

    public const AutopilotServerNone     = 'none';
    public const AutopilotServerLeader   = 'leader';
    public const AutopilotServerVoter    = 'voter';
    public const AutopilotServerNonVoter = 'non-voter';
    public const AutopilotServerStaging  = 'staging';

    public const AutopilotTypeVoter          = 'voter';
    public const AutopilotTypeReadReplica    = 'read-replica';
    public const AutopilotTypeZoneVoter      = 'zone-voter';
    public const AutopilotTypeZoneExtraVoter = 'zone-extra-voter';
    public const AutopilotTypeZoneStandby    = 'zone-standby';

    public const AutopilotUpgradeIdle               = 'idle';
    public const AutopilotUpgradeAwaitNewVoters     = 'await-new-voters';
    public const AutopilotUpgradePromoting          = 'promoting';
    public const AutopilotUpgradeDemoting           = 'demoting';
    public const AutopilotUpgradeLeaderTransfer     = 'leader-transfer';
    public const AutopilotUpgradeAwaitNewServers    = 'await-new-servers';
    public const AutopilotUpgradeAwaitServerRemoval = 'await-server-removal';
    public const AutopilotUpgradeDisabled           = 'disabled';

    public const BindingRuleBindTypeService = 'service';
    public const BindingRuleBindTypeRole    = 'role';

    public const MeshGatewayModeDefault = '';
    public const MeshGatewayModeNone    = 'none';
    public const MeshGatewayModeLocal   = 'local';
    public const MeshGatewayModeRemote  = 'remote';

    public const MemberTagKeyACLMode         = 'acls';
    public const MemberTagKeyRole            = 'role';
    public const MemberTagValueRoleServer    = 'consul';
    public const MemberTagKeySegment         = 'segment';
    public const MemberTagKeyBootstrap       = 'bootstrap';
    public const MemberTagValueBootstrap     = '1';
    public const MemberTagKeyBootstrapExpect = 'expect';
    public const MemberTagKeyUseTLS          = 'use_tls';
    public const MemberTagValueUseTLS        = '1';
    public const MemberTagKeyReadReplica     = 'read_replica';
    public const MemberTagValueReadReplica   = '1';

    public const ACLModeDisabled = '0';
    public const ACLModeEnabled  = '1';
    public const ACLModeLegacy   = '2';
    public const ACLModeUnknown  = '3';

    public const ProxyModeDefault = '';
    public const ProxyModeTransparent = 'transparent';
    public const ProxyModeDirect = 'direct';

    // "private" constants

    public const _headerConsulPrefix             = 'X-Consul-';
    public const _headerConsulIndex              = self::_headerConsulPrefix . 'Index';
    public const _headerConsulContentHash        = self::_headerConsulPrefix . 'ContentHash';
    public const _headerConsulKnownLeader        = self::_headerConsulPrefix . 'KnownLeader';
    public const _headerConsulLastContact        = self::_headerConsulPrefix . 'LastContact';
    public const _headerConsulTranslateAddresses = self::_headerConsulPrefix . 'Translate-Addresses';
    public const _headerCache                    = 'X-Cache';

    public ACLClient $ACL;
    public AgentClient $Agent;
    public CatalogClient $Catalog;
    public CoordinateClient $Coordinate;
    public EventClient $Event;
    public HealthClient $Health;
    public KVClient $KV;
    public OperatorClient $Operator;
    public PreparedQueryClient $PreparedQuery;
    public SessionClient $Session;
    public StatusClient $Status;

    public function __construct(Config $config = null)
    {
        $config = Config::merge($config);

        if ('' !== $config->TokenFile) {
            if (!file_exists($config->TokenFile) || !is_readable($config->TokenFile)) {
                throw new \RuntimeException(
                    sprintf(
                        'Provided $TokenFile "%s" either does not exist or is not readable',
                        $config->TokenFile
                    )
                );
            }
            $data = trim(file_get_contents($config->TokenFile));
            if ('' === $config->Token && '' !== $data) {
                $config->Token = $data;
            }
        }

        $this->ACL           = new ACLClient($config);
        $this->Agent         = new AgentClient($config);
        $this->Catalog       = new CatalogClient($config);
        $this->Coordinate    = new CoordinateClient($config);
        $this->Event         = new EventClient($config);
        $this->Health        = new HealthClient($config);
        $this->KV            = new KVClient($config);
        $this->Operator      = new OperatorClient($config);
        $this->PreparedQuery = new PreparedQueryClient($config);
        $this->Session       = new SessionClient($config);
        $this->Status        = new StatusClient($config);
    }

    public function ACL(): ACLClient
    {
        return $this->ACL;
    }

    public function Agent(): AgentClient
    {
        return $this->Agent;
    }

    public function Catalog(): CatalogClient
    {
        return $this->Catalog;
    }

    public function Coordinate(): CoordinateClient
    {
        return $this->Coordinate;
    }

    public function Event(): EventClient
    {
        return $this->Event;
    }

    public function Health(): HealthClient
    {
        return $this->Health;
    }

    public function KV(): KVClient
    {
        return $this->KV;
    }

    public function Operator(): OperatorClient
    {
        return $this->Operator;
    }

    public function PreparedQuery(): PreparedQueryClient
    {
        return $this->PreparedQuery;
    }

    public function Session(): SessionClient
    {
        return $this->Session;
    }

    public function Status(): StatusClient
    {
        return $this->Status;
    }
}
