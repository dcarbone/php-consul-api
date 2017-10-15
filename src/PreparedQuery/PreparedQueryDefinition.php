<?php namespace DCarbone\PHPConsulAPI\PreparedQuery;

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
 * Class PreparedQueryDefinition
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class PreparedQueryDefinition extends AbstractModel {
    /** @var string */
    public $ID = '';
    /** @var string */
    public $Name = '';
    /** @var string */
    public $Session = '';
    /** @var string */
    public $Token = '';
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery */
    public $Service = null;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions */
    public $DNS = null;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate */
    public $Template = null;

    /**
     * PreparedQueryDefinition constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (null !== $this->Service && !($this->Service instanceof ServiceQuery)) {
            $this->Service = new ServiceQuery((array)$this->Service);
        }
        if (null !== $this->DNS && !($this->DNS instanceof QueryDNSOptions)) {
            $this->DNS = new QueryDNSOptions((array)$this->DNS);
        }
        if (null !== $this->Template && !($this->Template instanceof QueryTemplate)) {
            $this->Template = new QueryTemplate((array)$this->Template);
        }
    }

    /**
     * @return string
     */
    public function getID(): string {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setID(string $ID): PreparedQueryDefinition {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setName(string $Name): PreparedQueryDefinition {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession(): string {
        return $this->Session;
    }

    /**
     * @param string $Session
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setSession(string $Session): PreparedQueryDefinition {
        $this->Session = $Session;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string {
        return $this->Token;
    }

    /**
     * @param string $Token
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setToken(string $Token): PreparedQueryDefinition {
        $this->Token = $Token;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function getService() {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery $Service
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setService(ServiceQuery $Service): PreparedQueryDefinition {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions
     */
    public function getDNS() {
        return $this->DNS;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions $DNS
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setDNS(QueryDNSOptions $DNS): PreparedQueryDefinition {
        $this->DNS = $DNS;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate
     */
    public function getTemplate() {
        return $this->Template;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate $Template
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setTemplate(QueryTemplate $Template): PreparedQueryDefinition {
        $this->Template = $Template;
        return $this;
    }
}