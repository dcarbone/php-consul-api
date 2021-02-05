<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Event;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class EventClient
 */
class EventClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\Event\UserEvent $event
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Event\UserEventResponse
     */
    public function Fire(UserEvent $event, ?WriteOptions $opts = null): UserEventResponse
    {
        $r = new Request(
            'PUT',
            \sprintf('v1/event/fire/%s', $event->Name),
            $this->config,
            '' !== $event->Payload ? $event->Payload : null
        );

        $r->applyOptions($opts);

        if ('' !== ($nf = $event->NodeFilter)) {
            $r->params->set('node', $nf);
        }
        if ('' !== ($sf = $event->ServiceFilter)) {
            $r->params->set('service', $sf);
        }
        if ('' !== ($tf = $event->TagFilter)) {
            $r->params->set('tag', $tf);
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new UserEventResponse(null, null, $err);
        }

        $wm = $this->buildWriteMeta($duration);

        [$data, $err] = $this->decodeBody($response->getBody());
        return new UserEventResponse($data, $wm, $err);
    }

    /**
     * @param string $name
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\Event\UserEventsResponse
     */
    public function List(string $name = '', ?QueryOptions $opts = null): UserEventsResponse
    {
        $r = new Request('GET', 'v1/event/list', $this->config, null);
        if ('' !== (string) $name) {
            $r->params->set('name', $name);
        }
        $r->applyOptions($opts);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        [$duration, $response, $err] = $this->_requireOK($this->_do($r));
        if (null !== $err) {
            return new UserEventsResponse(null, null, $err);
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        [$data, $err] = $this->decodeBody($response->getBody());

        return new UserEventsResponse($data, $qm, $err);
    }

    /**
     * @param string $uuid
     * @return int
     */
    public function IDToIndex(string $uuid): int
    {
        if (36 !== \strlen($uuid)) {
            throw new \InvalidArgumentException("{$uuid} is not a valid UUID");
        }

        $lower  = \substr($uuid, 0, 8)  + \substr($uuid, 9, 4)  + \substr($uuid, 14, 4);
        $upper  = \substr($uuid, 19, 4) + \substr($uuid, 24, 12);
        $lowVal = \intval($lower, 10);
        if (0 >= $lowVal) {
            throw new \InvalidArgumentException("{$lower} is not greater than 0");
        }
        $highVal = \intval($upper, 10);
        if (0 >= $highVal) {
            throw new \InvalidArgumentException("{$upper} is not greater than 0");
        }

        return $lowVal ^ $highVal;
    }
}
