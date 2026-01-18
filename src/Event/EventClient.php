<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Event;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class EventClient extends AbstractClient
{
    public function Fire(UserEvent $event, null|WriteOptions $opts = null): UserEventResponse
    {
        $r = $this->_newPutRequest(sprintf('v1/event/fire/%s', $event->Name), '' !== $event->Payload ? $event->Payload : null, $opts);
        if ('' !== ($nf = $event->NodeFilter)) {
            $r->params->set('node', $nf);
        }
        if ('' !== ($sf = $event->ServiceFilter)) {
            $r->params->set('service', $sf);
        }
        if ('' !== ($tf = $event->TagFilter)) {
            $r->params->set('tag', $tf);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new UserEventResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function List(string $name = '', null|QueryOptions $opts = null): UserEventsResponse
    {
        $r = $this->_newGetRequest('v1/event/list', $opts);
        if ('' !== $name) {
            $r->params->set('name', $name);
        }
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new UserEventsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function IDToIndex(string $uuid): int
    {
        if (36 !== strlen($uuid)) {
            throw new \InvalidArgumentException("{$uuid} is not a valid UUID");
        }

        $lower  = sprintf('%s%s%s', substr($uuid, 0, 8), substr($uuid, 9, 4), substr($uuid, 14, 4));
        $upper  = sprintf('%s%s', substr($uuid, 19, 4), substr($uuid, 24, 12));
        $lowVal = intval($lower, 10);
        if (0 >= $lowVal) {
            throw new \InvalidArgumentException("{$lower} is not greater than 0");
        }
        $highVal = intval($upper, 10);
        if (0 >= $highVal) {
            throw new \InvalidArgumentException("{$upper} is not greater than 0");
        }

        return $lowVal ^ $highVal;
    }
}
