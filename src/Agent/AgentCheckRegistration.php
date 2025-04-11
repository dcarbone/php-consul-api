<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\Transcoding;

class AgentCheckRegistration extends AgentServiceCheck
{
    protected const FIELDS = [
        self::FIELD_ID         => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_ID => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE  => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_ID         = 'ID';
    private const FIELD_SERVICE_ID = 'ServiceID';
    private const FIELD_NAMESPACE  = 'Namespace';

    public string $ID = '';
    public string $ServiceID = '';
    public string $Namespace = '';

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
