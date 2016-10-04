<?php namespace DCarbone\PHPConsulAPI\Client;

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\Http\Request;
use DCarbone\PHPConsulAPI\Http\Response;
use DCarbone\PHPConsulAPI\Logger;
use DCarbone\PHPConsulAPI\Model\QueryMeta;
use DCarbone\PHPConsulAPI\Model\WriteMeta;

/**
 * Class AbstractClient
 * @package DCarbone\PHPConsulAPI\Base
 */
abstract class AbstractClient
{
    /** @var Config */
    protected $Config;

    /** @var Response|null */
    protected $lastResponse = null;

    /**
     * AbstractConsulClient constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->Config = $config;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Http\Response|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param array $requestResult
     * @return array(
     *  @type int query duration in microseconds
     *  @type \DCarbone\PHPConsulAPI\Http\Response|null response object
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    protected function requireOK(array $requestResult)
    {
        if (null !== $requestResult[2])
            return $requestResult;

        if (200 !== $requestResult[1]->httpCode)
        {
            $err = new Error(sprintf(
                '%s - Error seen while executing "%s".  Response code: %d.  Message: %s',
                get_class($this),
                $requestResult[1]->url,
                $requestResult[1]->httpCode,
                $requestResult[1]->body
            ));

            Logger::error($err);

            return [$requestResult[0], $requestResult[1], $err];
        }

        return $requestResult;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Http\Request $r
     * @return array(
     *  @type int duration in microseconds
     *  @type \DCarbone\PHPConsulAPI\Http\Response|null http response
     *  @type \DCarbone\PHPConsulAPI\Error|null any seen errors
     * )
     */
    protected function doRequest(Request $r)
    {
        /** Error $err */
        list($this->lastResponse, $err) = $r->execute();

        if ('' === $this->lastResponse->curlError)
            return [$this->lastResponse->totalTime, $this->lastResponse, $err];

        $err = new Error(sprintf(
            '%s - Error seen while executing "%s".  Message: "%s"',
            get_class($this),
            $this->lastResponse->url,
            $this->lastResponse->curlError
        ));

        Logger::error($err);

        return [$this->lastResponse->totalTime, $this->lastResponse, $err];
    }

    /**
     * @param int $duration
     * @param \DCarbone\PHPConsulAPI\Http\Response $response
     * @return QueryMeta
     */
    protected function buildQueryMeta($duration, Response $response)
    {
        $qm = new QueryMeta();

        $qm->requestTime = $duration;
        $qm->requestUrl = $response->url;

        foreach($response->responseHeaders as $header)
        {
            if (isset($header['X-Consul-Index']))
                $qm->lastIndex = (int)$header['X-Consul-Index'];

            if (isset($header['X-Consul-KnownLeader']))
                $qm->knownLeader = (bool)$header['X-Consul-KnownLeader'];
            else if (isset($header['X-Consul-Knownleader']))
                $qm->knownLeader = (bool)$header['X-Consul-Knownleader'];

            if (isset($header['X-Consul-LastContact']))
                $qm->lastContact = (int)$header['X-Consul-LastContact'] * 1000;
            else if (isset($header['X-Consul-Lastcontact']))
                $qm->lastContact = (int)$header['X-Consul-Lastcontact'] * 1000;
        }

        Logger::debug(sprintf('QueryMeta built: %s', json_encode($qm)));

        return $qm;
    }

    /**
     * @param int $duration
     * @return WriteMeta
     */
    protected function buildWriteMeta($duration)
    {
        $wm = new WriteMeta();
        $wm->requestTime = $duration;

        Logger::debug(sprintf('WriteMeta built: %s', json_encode($wm)));

        return $wm;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Http\Response $response
     * @return array(
     *  @type array|string|bool|int|float decoded response
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    protected function decodeBody(Response $response)
    {
        $data = @json_decode($response->body, true);
        $err = json_last_error();

        if (JSON_ERROR_NONE === $err)
            return [$data, null];

        return [null, new Error(sprintf(
            '%s - Unable to parse response as JSON.  Message: %s',
            get_class($this),
            PHP_VERSION_ID >= 50500 ? json_last_error_msg() : (string)$err
        ))];
    }
}