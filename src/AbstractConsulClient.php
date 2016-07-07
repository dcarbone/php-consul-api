<?php namespace DCarbone\PHPConsulAPI;

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

use DCarbone\CURLHeaderExtractor;

/**
 * Class AbstractConsulClient
 * @package DCarbone\PHPConsulAPI\Base
 */
abstract class AbstractConsulClient
{
    /** @var Config */
    protected $_Config;

    /**
     * AbstractConsulClient constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->_Config = $config;
    }

    /**
     * @param array $requestResult
     * @return array(
     *  @type int query duration in microseconds
     *  @type HttpResponse|null response object
     *  @type Error|null error, if any
     * )
     */
    protected function requireOK(array $requestResult)
    {
        /** @var int $duration */
        /** @var HttpResponse|null $response */
        /** @var Error|null $err */

        list($duration, $response, $err) = $requestResult;

        if (null !== $err)
            return $requestResult;

        if (200 !== $response->httpCode)
            return [$duration, $response, new Error('warn', sprintf(
                '%s - Error seen while executing "%s".  Response code: %d.  Message: %s',
                get_class($this),
                $response->url,
                $response->httpCode,
                $response->curlError
            ))];

        return [$duration, $response, null];
    }

    /**
     * @param Request $r
     * @return array(
     *  @type int duration in microseconds
     *  @type HttpResponse|null http response
     *  @type Error|null any seen errors
     * )
     */
    protected function doRequest(Request $r)
    {
        $rt = microtime(true);
        /** @var HttpResponse $response */
        /** @var Error|null $err */
        list($response, $err) = $r->execute();
        $duration = (int)((microtime(true) - $rt) * 1000000);

        if (null !== $err)
            return [$duration, null, $err];

        return [$duration, $response, null];
    }

    /**
     * @param int $duration
     * @param HttpResponse $response
     * @return QueryMeta
     */
    protected function buildQueryMeta($duration, HttpResponse $response)
    {
        $qm = new QueryMeta();

        $qm->requestTime = $duration;
        $qm->requestUrl = $response->url;

        foreach($response->responseHeaders as $header)
        {
            if (isset($header['X-Consul-Index']))
                $qm->lastIndex = (int)$header['X-Consul-Index'];

            if (isset($header['X-Consul-Knownleader']))
                $qm->knownLeader = (bool)$header['X-Consul-Knownleader'];

            if (isset($header['X-Consul-Lastcontact']))
                $qm->lastContact = (int)$header['X-Consul-Lastcontact'] * 1000;
        }

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
        return $wm;
    }

    /**
     * @param HttpResponse $response
     * @return array
     */
    protected function decodeBody(HttpResponse $response)
    {
        $data = @json_decode($response->body, true);
        $err = json_last_error();

        if (JSON_ERROR_NONE === $err)
            return [$data, null];

        return [null, new Error('error', sprintf(
            '%s - Unable to parse response as JSON.  Message: %s',
            get_class($this),
            PHP_VERSION_ID >= 50500 ? json_last_error_msg() : (string)$err
        ))];
    }
}