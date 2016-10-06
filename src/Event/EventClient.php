<?php namespace DCarbone\PHPConsulAPI\Event;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Hydrator;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;

/**
 * Class EventClient
 * @package DCarbone\PHPConsulAPI\Event
 */
class EventClient extends AbstractClient
{
    /**
     * @param UserEvent $event
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     * @return array(
     *  @type UserEvent|null user event that was fired or null on error
     *  @type \DCarbone\PHPConsulAPI\WriteMeta write metadata
     *  @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function fire(UserEvent $event, WriteOptions $writeOptions = null)
    {
        $r = new Request('put', sprintf('v1/event/fire/%s', $event->Name), $this->Config);
        $r->setWriteOptions($writeOptions);

        if ('' !== ($nf = $event->NodeFilter))
            $r->params->set('node', $nf);
        if ('' !== ($sf = $event->ServiceFilter))
            $r->params->set('service', $sf);
        if ('' !== ($tf = $event->TagFilter))
            $r->params->set('tag', $tf);
        if ('' !== ($payload = $event->Payload))
            $r->body = $payload;

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $wm = $this->buildWriteMeta($duration);

        if (null !== $err)
            return [null, $wm, $err];

        list($data, $err) = $this->decodeBody($response);
        if ($err !== null)
            return [null, $wm, $err];

        return [Hydrator::UserEvent($data), $wm, null];
    }
    
    /**
     * @param string $name
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     * @return array(
     *  @type UserEvent[] list of user events or null on error
     *  @type \DCarbone\PHPConsulAPI\QueryMeta query metadata
     *  @type \DCarbone\PHPConsulAPI\Error error, if any
     * )
     */
    public function eventList($name = '', QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/event/list', $this->Config);
        if ('' !== (string)$name)
            $r->params->set('name', $name);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        if (null !== $err)
            return [null, $qm, $err];

        $events = array();
        foreach($data as $event)
        {
            $events[] = Hydrator::UserEvent($event);
        }

        return [$events, $qm, null];
    }
}