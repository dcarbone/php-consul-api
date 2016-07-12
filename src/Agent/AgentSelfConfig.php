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

use DCarbone\PHPConsulAPI\AbstractObjectModel;

/**
 * Class AgentSelfConfig
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentSelfConfig extends AbstractObjectModel
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
}