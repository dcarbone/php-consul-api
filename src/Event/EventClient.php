<?php namespace DCarbone\PHPConsulAPI\Event;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\Event
 */
class EventClient extends AbstractClient {
    /**
     * @param \DCarbone\PHPConsulAPI\Event\UserEvent $event
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     * @return array(
     * @type UserEvent|null user event that was fired or null on error
     * @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     * @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function fire(UserEvent $event, WriteOptions $options = null): array {
        $r = new Request(
            'PUT',
            sprintf('v1/event/fire/%s', $event->Name),
            $this->config,
            '' !== $event->Payload ? $event->Payload : null);

        $r->setWriteOptions($options);

        if ('' !== ($nf = $event->NodeFilter)) {
            $r->Params->set('node', $nf);
        }
        if ('' !== ($sf = $event->ServiceFilter)) {
            $r->Params->set('service', $sf);
        }
        if ('' !== ($tf = $event->TagFilter)) {
            $r->Params->set('tag', $tf);
        }

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $wm = $this->buildWriteMeta($duration);

        list($data, $err) = $this->decodeBody($response->getBody());
        if ($err !== null) {
            return [null, $wm, $err];
        }

        return [new UserEvent($data), $wm, null];
    }

    /**
     * @param string $name
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     * @return array(
     * @type UserEvent[] list of user events or null on error
     * @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function eventList(string $name = '', QueryOptions $options = null): array {
        $r = new Request('GET', 'v1/event/list', $this->config);
        if ('' !== (string)$name) {
            $r->Params->set('name', $name);
        }
        $r->setQueryOptions($options);

        /** @var \Psr\Http\Message\ResponseInterface $response */
        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        if (null !== $err) {
            return [null, null, $err];
        }

        $qm = $this->buildQueryMeta($duration, $response, $r->getUri());

        list($data, $err) = $this->decodeBody($response->getBody());

        if (null !== $err) {
            return [null, $qm, $err];
        }

        $events = [];
        foreach ($data as $event) {
            $events[] = new UserEvent($event);
        }

        return [$events, $qm, null];
    }
}