<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class PreparedQueryDefinition extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_SERVICE  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => ServiceQuery::class,
        ],
        self::FIELD_DNS      => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => QueryDNSOptions::class,
        ],
        self::FIELD_Template => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => QueryTemplate::class,
        ],
    ];

    private const FIELD_SERVICE  = 'Service';
    private const FIELD_DNS      = 'DNS';
    private const FIELD_Template = 'Template';

    public string $ID = '';
    public string $Name = '';
    public string $Session = '';
    public string $Token = '';
    public ServiceQuery $Service;
    public QueryDNSOptions $DNS;
    public QueryTemplate $Template;

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

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getSession(): string
    {
        return $this->Session;
    }

    public function setSession(string $Session): self
    {
        $this->Session = $Session;
        return $this;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $Token): self
    {
        $this->Token = $Token;
        return $this;
    }

    public function getService(): ServiceQuery
    {
        return $this->Service;
    }

    public function setService(ServiceQuery $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getDNS(): QueryDNSOptions
    {
        return $this->DNS;
    }

    public function setDNS(QueryDNSOptions $DNS): self
    {
        $this->DNS = $DNS;
        return $this;
    }

    public function getTemplate(): QueryTemplate
    {
        return $this->Template;
    }

    public function setTemplate(QueryTemplate $Template): self
    {
        $this->Template = $Template;
        return $this;
    }
}
