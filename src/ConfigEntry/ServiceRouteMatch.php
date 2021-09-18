<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceRouteMatch
 */
class ServiceRouteMatch extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_HTTP => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => ServiceRouteHTTPMatch::class,
            Transcoding::FIELD_NULLABLE  => true,
            Transcoding::FIELD_OMITEMPTY => true,
        ],
    ];

    private const FIELD_HTTP = 'HTTP';

    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch|null */
    public ?ServiceRouteHTTPMatch $HTTP = null;

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch|null
     */
    public function getHTTP(): ?ServiceRouteHTTPMatch
    {
        return $this->HTTP;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch|null $HTTP
     * @return ServiceRouteMatch
     */
    public function setHTTP(?ServiceRouteHTTPMatch $HTTP): self
    {
        $this->HTTP = $HTTP;
        return $this;
    }
}
