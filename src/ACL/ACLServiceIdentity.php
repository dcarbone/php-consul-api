<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
 * Class ACLServiceIdentity
 */
class ACLServiceIdentity extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_DATACENTERS => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_ARRAY_TYPE => Hydration::STRING,
            Hydration::FIELD_OMITEMPTY  => true,
        ],
    ];

    private const FIELD_DATACENTERS = 'Datacenters';

    /** @var string */
    public string $ServiceName = '';
    /** @var array */
    public array $Datacenters = [];

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    /**
     * @return array
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }
}
