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
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery|null */
    public ?ServiceQuery $Service = null;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions|null */
    public ?QueryDNSOptions $DNS = null;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate|null */
    public ?QueryTemplate $Template = null;

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

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $id
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setID(string $id): self
    {
        $this->ID = $id;
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
     * @param string $name
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setName(string $name): self
    {
        $this->Name = $name;
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
     * @param string $session
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setSession(string $session): self
    {
        $this->Session = $session;
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
     * @param string $token
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setToken(string $token): self
    {
        $this->Token = $token;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery|null
     */
    public function getService(): ?ServiceQuery
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery|null $service
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setService(?ServiceQuery $service): self
    {
        $this->Service = $service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions|null
     */
    public function getDNS(): ?QueryDNSOptions
    {
        return $this->DNS;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions|null $dns
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setDNS(?QueryDNSOptions $dns): self
    {
        $this->DNS = $dns;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate|null
     */
    public function getTemplate(): ?QueryTemplate
    {
        return $this->Template;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\QueryTemplate|null $template
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition
     */
    public function setTemplate(?QueryTemplate $template): self
    {
        $this->Template = $template;
        return $this;
    }
}
