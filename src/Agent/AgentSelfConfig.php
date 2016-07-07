<?php namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractDefinedCollection;

/**
 * Class AgentSelfConfig
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelfConfig extends AbstractDefinedCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Bootstrap' => null,
            'Server' => null,
            'Datacenter' => null,
            'DataDir' => null,
            'DNSRecursor' => null,
            'DNSRecursors' => array(),
            'DNSConfig' => array(),
            'Domain' => null,
            'LogLevel' => null,
            'NodeName' => null,
            'ClientAddr' => null,
            'BindAddr' => null,
            'AdvertiseAddr' => null,
            'Ports' => array(),
            'LeaveOnTerm' => null,
            'SkipLeaveOnInt' => null,
            'StatsiteAddr' => null,
            'Protocol' => null,
            'EnableDebug' => null,
            'VerifyIncoming' => null,
            'VerifyOutgoing' => null,
            'CAFile' => null,
            'CertFile' => null,
            'KeyFile' => null,
            'StartJoin' => array(),
            'UiDir' => null,
            'PidFile' => null,
            'EnableSyslog' => null,
            'RejoinAfterLeave' => null,
        );
    }

    /**
     * @return bool
     */
    public function getBootstrap()
    {
        return (bool)$this['Bootstrap'];
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this['Server'];
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this['Datacenter'];
    }

    /**
     * @return string
     */
    public function getDataDir()
    {
        return $this['DataDir'];
    }

    /**
     * @return string
     */
    public function getDNSRecursor()
    {
        return $this['DNSRecursor'];
    }

    /**
     * @return array
     */
    public function getDNSRecursors()
    {
        return $this['DNSRecursors'];
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this['Domain'];
    }

    /**
     * @return string
     */
    public function getLogLevel()
    {
        return $this['LogLevel'];
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this['NodeName'];
    }

    /**
     * @return string
     */
    public function getClientAddr()
    {
        return $this['ClientAddr'];
    }

    /**
     * @return string
     */
    public function getBindAddr()
    {
        return $this['BindAddr'];
    }

    /**
     * @return string
     */
    public function getAdvertiseAddr()
    {
        return $this['AdvertiseAddr'];
    }

    /**
     * @return array
     */
    public function getPorts()
    {
        return $this['Ports'];
    }

    /**
     * @return bool
     */
    public function getLeaveOnTerm()
    {
        return (bool)$this['LeaveOnTerm'];
    }

    /**
     * @return bool
     */
    public function getSkipLeaveOnInt()
    {
        return (bool)$this['SkipLeaveOnInt'];
    }

    /**
     * @return string
     */
    public function getStatsiteAddr()
    {
        return $this['StatsiteAddr'];
    }

    /**
     * @return int
     */
    public function getProtocol()
    {
        return (int)$this['Protocol'];
    }

    /**
     * @return bool
     */
    public function getEnableDebug()
    {
        return (bool)$this['EnableDebug'];
    }

    /**
     * @return bool
     */
    public function getVerifyIncoming()
    {
        return (bool)$this['VerifyIncoming'];
    }

    /**
     * @return bool
     */
    public function getVerifyOutgoing()
    {
        return (bool)$this['VerifyOutgoing'];
    }

    /**
     * @return string
     */
    public function getCAFile()
    {
        return $this['CAFile'];
    }

    /**
     * @return string
     */
    public function getCertFile()
    {
        return $this['CertFile'];
    }

    /**
     * @return string
     */
    public function getKeyFile()
    {
        return $this['KeyFile'];
    }

    /**
     * @return array
     */
    public function getStartJoin()
    {
        return $this['StartJoin'];
    }

    /**
     * @return string
     */
    public function getUiDir()
    {
        return $this['UiDir'];
    }

    /**
     * @return string
     */
    public function getPidFile()
    {
        return $this['PidFile'];
    }

    /**
     * @return bool
     */
    public function getEnableSyslog()
    {
        return (bool)$this['EnableSyslog'];
    }

    /**
     * @return bool
     */
    public function getRejoinAfterLeave()
    {
        return (bool)$this['RejoinAfterLeave'];
    }
}