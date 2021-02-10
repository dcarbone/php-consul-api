<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class PreparedQueryDefinition
 */
class PreparedQueryDefinition extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE  => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => ServiceQuery::class,
        ],
        self::FIELD_DNS      => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => QueryDNSOptions::class,
        ],
        self::FIELD_Template => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => QueryTemplate::class,
        ],
    ];

    private const FIELD_SERVICE  = 'Service';
    private const FIELD_DNS      = 'DNS';
    private const FIELD_Template = 'Template';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $Name = '';
    /** @var string */
    public string $Session = '';
    /** @var string */
    public string $Token = '';
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery */
    public ServiceQuery $Service;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions */
    public QueryDNSOptions $DNS;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate */
    public QueryTemplate $Template;

    /**
     * PreparedQueryDefinition constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Service)) {
            $this->Service = new ServiceQuery(null);
        }
        if (!isset($this->DNS)) {
            $this->DNS = new QueryDNSOptions(null);
        }
        if (!isset($this->Template)) {
            $this->Template = new QueryTemplate(null);
        }
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->Session;
    }

    /**
     * @param string $Session
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setSession(string $Session): self
    {
        $this->Session = $Session;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->Token;
    }

    /**
     * @param string $Token
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setToken(string $Token): self
    {
        $this->Token = $Token;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery
     */
    public function getService(): ServiceQuery
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery $Service
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setService(ServiceQuery $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions
     */
    public function getDNS(): QueryDNSOptions
    {
        return $this->DNS;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions $DNS
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setDNS(QueryDNSOptions $DNS): self
    {
        $this->DNS = $DNS;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate
     */
    public function getTemplate(): QueryTemplate
    {
        return $this->Template;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate $Template
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setTemplate(QueryTemplate $Template): self
    {
        $this->Template = $Template;
        return $this;
    }
}
