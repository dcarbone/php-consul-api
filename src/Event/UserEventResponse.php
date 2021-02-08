<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Event;

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

use DCarbone\PHPConsulAPI\AbstractResponse;
use DCarbone\PHPConsulAPI\ErrorContainer;
use DCarbone\PHPConsulAPI\HydratedResponseInterface;
use DCarbone\PHPConsulAPI\WriteMetaContainer;

/**
 * Class UserEventResponse
 */
class UserEventResponse extends AbstractResponse implements HydratedResponseInterface
{
    use WriteMetaContainer;
    use ErrorContainer;

    /** @var \DCarbone\PHPConsulAPI\Event\UserEvent|null */
    public ?UserEvent $UserEvent = null;

    /**
     * @return \DCarbone\PHPConsulAPI\Event\UserEvent|null
     */
    public function getValue()
    {
        return $this->UserEvent;
    }

    /**
     * @param mixed $decodedData
     */
    public function hydrateValue($decodedData): void
    {
        $this->UserEvent = new UserEvent((array)$decodedData);
    }
}
