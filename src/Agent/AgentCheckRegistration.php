<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AgentCheckRegistration
 */
class AgentCheckRegistration extends AgentServiceCheck
{
    protected const FIELDS = [
        self::FIELD_ID         => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_ID => Hydration::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE  => Hydration::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_ID         = 'ID';
    private const FIELD_SERVICE_ID = 'ServiceID';
    private const FIELD_NAMESPACE  = 'Namespace';

    /** @var string */
    public string $ID = '';
    /** @var string */
    public string $ServiceID = '';
    /** @var string */
    public string $Namespace = '';

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    /**
     * @param string $ServiceID
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setServiceID(string $ServiceID): self
    {
        $this->ServiceID = $ServiceID;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
