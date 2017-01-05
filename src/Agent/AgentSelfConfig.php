<?php namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class AgentSelfConfig
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelfConfig extends AbstractModel
{
    /** @var bool */
    public $Bootstrap = false;
    /** @var bool */
    public $DevMode = false;
    /** @var int */
    public $BootstrapExpect = 0;
    /** @var bool */
    public $Server = false;
    /** @var string */
    public $Datacenter = '';
    /** @var string */
    public $DataDir = '';
    /** @var string */
    public $DNSRecursor = '';
    /** @var string[] */
    public $DNSRecursors = array();
    /** @var string[] */
    public $DNSConfig = array();
    /** @var string */
    public $Domain = '';
    /** @var string */
    public $LogLevel = '';
    /** @var string */
    public $NodeName = '';
    /** @var string */
    public $ClientAddr = '';
    /** @var string */
    public $BindAddr = '';
    /** @var string */
    public $AdvertiseAddr = '';
    /** @var string[] */
    public $Ports = array();
    /** @var bool */
    public $LeaveOnTerm = false;
    /** @var bool */
    public $SkipLeaveOnInt = false;
    /** @var string[] */
    public $Telemetry = array();
    /** @var int */
    public $Protocol = 0;
    /** @var bool */
    public $EnableDebug = false;
    /** @var bool */
    public $VerifyIncoming = false;
    /** @var bool */
    public $VerifyOutgoing = false;
    /** @var string */
    public $CAFile = '';
    /** @var string */
    public $CertFile = '';
    /** @var string */
    public $KeyFile = '';
    /** @var string[] */
    public $StartJoin = array();
    /** @var string */
    public $UiDir = '';
    /** @var string */
    public $PidFile = '';
    /** @var bool */
    public $EnableSyslog = false;
    /** @var bool */
    public $RejoinAfterLeave = false;

    /**
     * @return boolean
     */
    public function isBootstrap()
    {
        return $this->Bootstrap;
    }

    /**
     * @return boolean
     */
    public function isDevMode()
    {
        return $this->DevMode;
    }

    /**
     * @return int
     */
    public function getBootstrapExpect()
    {
        return $this->BootstrapExpect;
    }

    /**
     * @return boolean
     */
    public function isServer()
    {
        return $this->Server;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this->Datacenter;
    }

    /**
     * @return string
     */
    public function getDataDir()
    {
        return $this->DataDir;
    }

    /**
     * @return string
     */
    public function getDNSRecursor()
    {
        return $this->DNSRecursor;
    }

    /**
     * @return string[]
     */
    public function getDNSRecursors()
    {
        return $this->DNSRecursors;
    }

    /**
     * @return string[]
     */
    public function getDNSConfig()
    {
        return $this->DNSConfig;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->Domain;
    }

    /**
     * @return string
     */
    public function getLogLevel()
    {
        return $this->LogLevel;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->NodeName;
    }

    /**
     * @return string
     */
    public function getClientAddr()
    {
        return $this->ClientAddr;
    }

    /**
     * @return string
     */
    public function getBindAddr()
    {
        return $this->BindAddr;
    }

    /**
     * @return string
     */
    public function getAdvertiseAddr()
    {
        return $this->AdvertiseAddr;
    }

    /**
     * @return string[]
     */
    public function getPorts()
    {
        return $this->Ports;
    }

    /**
     * @return boolean
     */
    public function isLeaveOnTerm()
    {
        return $this->LeaveOnTerm;
    }

    /**
     * @return boolean
     */
    public function isSkipLeaveOnInt()
    {
        return $this->SkipLeaveOnInt;
    }

    /**
     * @return string[]
     */
    public function getTelemetry()
    {
        return $this->Telemetry;
    }

    /**
     * @return int
     */
    public function getProtocol()
    {
        return $this->Protocol;
    }

    /**
     * @return boolean
     */
    public function isEnableDebug()
    {
        return $this->EnableDebug;
    }

    /**
     * @return boolean
     */
    public function isVerifyIncoming()
    {
        return $this->VerifyIncoming;
    }

    /**
     * @return boolean
     */
    public function isVerifyOutgoing()
    {
        return $this->VerifyOutgoing;
    }

    /**
     * @return string
     */
    public function getCAFile()
    {
        return $this->CAFile;
    }

    /**
     * @return string
     */
    public function getCertFile()
    {
        return $this->CertFile;
    }

    /**
     * @return string
     */
    public function getKeyFile()
    {
        return $this->KeyFile;
    }

    /**
     * @return string[]
     */
    public function getStartJoin()
    {
        return $this->StartJoin;
    }

    /**
     * @return string
     */
    public function getUiDir()
    {
        return $this->UiDir;
    }

    /**
     * @return string
     */
    public function getPidFile()
    {
        return $this->PidFile;
    }

    /**
     * @return boolean
     */
    public function isEnableSyslog()
    {
        return $this->EnableSyslog;
    }

    /**
     * @return boolean
     */
    public function isRejoinAfterLeave()
    {
        return $this->RejoinAfterLeave;
    }
}