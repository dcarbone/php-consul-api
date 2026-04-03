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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class PreparedQueryDefinition extends AbstractType
{
    public string $ID;
    public string $Name;
    public string $Session;
    public string $Token;
    public ServiceQuery $Service;
    public QueryDNSOptions $DNS;
    public QueryTemplate $Template;

    public function __construct(
        string $ID = '',
        string $Name = '',
        string $Session = '',
        string $Token = '',
        null|ServiceQuery $Service = null,
        null|QueryDNSOptions $DNS = null,
        null|QueryTemplate $Template = null,
    ) {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Session = $Session;
        $this->Token = $Token;
        $this->Service = $Service ?? new ServiceQuery();
        $this->DNS = $DNS ?? new QueryDNSOptions();
        $this->Template = $Template ?? new QueryTemplate();
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Service' === $k) {
                $n->Service = ServiceQuery::jsonUnserialize($v);
            } elseif ('DNS' === $k) {
                $n->DNS = QueryDNSOptions::jsonUnserialize($v);
            } elseif ('Template' === $k) {
                $n->Template = QueryTemplate::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ID = $this->ID;
        $out->Name = $this->Name;
        $out->Session = $this->Session;
        $out->Token = $this->Token;
        $out->Service = $this->Service;
        $out->DNS = $this->DNS;
        $out->Template = $this->Template;
        return $out;
    }
}
