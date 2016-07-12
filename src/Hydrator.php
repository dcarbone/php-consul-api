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
     * @return KVPair
     */
    public static function KVPair(array $data)
    {
        $kvp = new KVPair();
        foreach($data as $k => $v)
        {
            if ('Value' === $k)
                $kvp->Value = base64_decode($v);
            else
                $kvp->{$k} = $v;
        }

        return $kvp;
    }

    /**
     * @param array $data
     * @return AgentCheck
     */
    public static function AgentCheck(array $data)
    {
        return new AgentCheck($data);
    }

    /**
     * @param array $data
     * @return AgentServiceCheck
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
     * @return AgentSelf
     */
    public static function AgentSelf(array $data)
    {
        $agent = new AgentSelf();
        $agent->Config = $data['Config'];
        $agent->Coord = $data['Coord'];
        $agent->Member = self::AgentMember($data['Member']);
        return $agent;
    }

    /**
     * @param array $data
     * @return AgentService
     */
    public static function AgentService(array $data)
    {
        return new AgentService($data);
    }

    /**
     * @param array $data
     * @return CatalogNode
     */
    public static function CatalogNode(array $data)
    {
        return new CatalogNode($data);
    }

    /**
     * @param array $data
     * @return CatalogService
     */
    public static function CatalogService(array $data)
    {
        return new CatalogService($data);
    }

    /**
     * @param array $data
     * @return Coordinate
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
        $dcm = new CoordinateDatacenterMap();
        $dcm->Datacenter = $data['Datacenter'];
        foreach($data['Coordinates'] as $coordinate)
        {
            $dcm->Coordinates[] = self::Coordinate($coordinate);
        }
        return $dcm;
    }

    /**
     * @param array $data
     * @return CoordinateEntry
     */
    public static function CoordinateEntry(array $data)
    {
        $ce = new CoordinateEntry();
        $ce->Node = $data['Node'];
        $ce->Coord = self::Coordinate($data['Coord']);
        return $ce;
    }

    /**
     * @param array $data
     * @return UserEvent
     */
    public static function UserEvent(array $data)
    {
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
        $se = new ServiceEntry();
        $se->Node = $data['Node'];
        if (isset($data['Service']))
            $se->Service = self::AgentService($data['Service']);
        
        if (isset($data['Checks']))
        {
            foreach($data['Checks'] as $check)
            {
                $se->Checks[] = self::HealthCheck($check);
            }
        }
        
        return $se;
    }
}