<?php namespace DCarbone\PHPConsulAPI;

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

use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentMember;
use DCarbone\PHPConsulAPI\Agent\AgentSelf;
use DCarbone\PHPConsulAPI\Agent\AgentSelfConfig;
use DCarbone\PHPConsulAPI\Agent\AgentSelfCoord;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Catalog\CatalogNode;
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Coordinate\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateDatacenterMap;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateEntry;
use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Operator\RaftConfiguration;
use DCarbone\PHPConsulAPI\Operator\RaftServer;
use DCarbone\PHPConsulAPI\Session\SessionEntry;

/**
 * TODO: Not a big fan of this...
 *
 * Class Hydrator
 * @package DCarbone\PHPConsulAPI
 */
class Hydrator
{
    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\KV\KVPair
     */
    public static function KVPair(array $data)
    {
        if (isset($data['Value']))
            $data['Value'] = base64_decode($data['Value']);

        return new KVPair($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck
     */
    public static function AgentCheck(array $data)
    {
        return new AgentCheck($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     */
    public static function AgentServiceCheck(array $data)
    {
        return new AgentServiceCheck($data);
    }

    /**
     * @param array $data
     * @return AgentMember
     */
    public static function AgentMember(array $data)
    {
        return new AgentMember($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelf
     */
    public static function AgentSelf(array $data)
    {
        $agent = new AgentSelf();
        if (isset($data['Config']))
            $agent->Config = self::AgentSelfConfig($data['Config']);
        if (isset($data['Coord']))
            $agent->Coord = self::AgentSelfCoord($data['Coord']);
        if (isset($data['Member']))
            $agent->Member = self::AgentMember($data['Member']);
        return $agent;
    }

    /**
     * @param array $data
     * @return AgentSelfConfig
     */
    public static function AgentSelfConfig(array $data)
    {
        return new AgentSelfConfig($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Agent\AgentSelfCoord
     */
    public static function AgentSelfCoord(array $data)
    {
        return new AgentSelfCoord($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public static function AgentService(array $data)
    {
        return new AgentService($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNode
     */
    public static function CatalogNode(array $data)
    {
        return new CatalogNode($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService
     */
    public static function CatalogService(array $data)
    {
        return new CatalogService($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Coordinate\Coordinate
     */
    public static function Coordinate(array $data)
    {
        return new Coordinate($data);
    }

    /**
     * @param array $data
     * @return CoordinateDatacenterMap
     */
    public static function CoordinateDatacenterMap(array $data)
    {
        if (isset($data['Coordinates']))
        {
            for ($i = 0, $cnt = count($data['Coordinates']); $i < $cnt; $i++)
            {
                $data['Coordinates'][$i] = self::Coordinate($data['Coordinates'][$i]);
            }
        }

        return new CoordinateDatacenterMap($data);
    }

    /**
     * @param array $data
     * @return CoordinateEntry
     */
    public static function CoordinateEntry(array $data)
    {
        if (isset($data['Coord']))
            $data['Coord'] = self::Coordinate($data['Coord']);

        return new CoordinateEntry($data);
    }

    /**
     * @param array $data
     * @return UserEvent
     */
    public static function UserEvent(array $data)
    {
        if (isset($data['Payload']))
            $data['Payload'] = base64_decode($data['Payload']);

        return new UserEvent($data);
    }

    /**
     * @param array $data
     * @return HealthCheck
     */
    public static function HealthCheck(array $data)
    {
        return new HealthCheck($data);
    }

    /**
     * @param array $data
     * @return ServiceEntry
     */
    public static function ServiceEntry(array $data)
    {
        if (isset($data['Service']))
            $data['Service'] = self::AgentService($data['Service']);

        if (isset($data['Checks']))
        {
            for ($i = 0, $cnt = count($data['Checks']); $i < $cnt; $i++)
            {
                $data['Checks'][$i] = self::HealthCheck($data['Checks'][$i]);
            }
        }

        return new ServiceEntry($data);
    }

    /**
     * @param array $data
     * @return \DCarbone\PHPConsulAPI\Session\SessionEntry
     */
    public static function SessionEntry(array $data)
    {
        return new SessionEntry($data);
    }

    /**
     * @param array $data
     * @return RaftConfiguration
     */
    public static function RaftConfiguration(array $data)
    {
        if (isset($data['Servers']))
        {
            for ($i = 0, $cnt = count($data['Servers']); $i < $cnt; $i++)
            {
                $data['Servers'][$i] = self::RaftServer($data['Servers'][$i]);
            }
        }
        return new RaftConfiguration($data);
    }

    /**
     * @param array $data
     * @return RaftServer
     */
    public static function RaftServer(array $data)
    {
        return new RaftServer($data);
    }
}