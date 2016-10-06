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

use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class AbstractClient
 * @package DCarbone\PHPConsulAPI
 */
abstract class AbstractClient
{
    /** @var Config */
    protected $Config;

    /**
     * AbstractConsulClient constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->Config = $config;
    }

    /**
     * @param array $r
     * @return array(
     *  @type int query duration in microseconds
     *  @type ResponseInterface|null response object
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    protected function requireOK(array $r)
    {
        // If a previous error occurred, just return as-is.
        if (null !== $r[2])
            return $r;

        // If we have any kind of response...
        if (null !== $r[1])
        {
            // If this is a response...
            if ($r[1] instanceof ResponseInterface)
            {
                // Get the response code...
                $code = $r[1]->getStatusCode();

                // If 200, move right along
                if (200 === $code)
                    return $r;

                // Otherwise, return error
                return [$r[0], $r[1], new Error(sprintf(
                    '%s - Non-200 response seen.  Response code: %d.  Message: %s',
                    get_class($this),
                    $code,
                    $r[1]->getReasonPhrase()
                ))];

            }
            else
            {
                return [$r[0], $r[1], new Error(sprintf(
                    '%s - Expected response to be instance of \\Psr\\Message\\ResponseInterface, %s seen.',
                    is_object($r[1]) ? get_class($r[1]) : gettype($r[1])
                ))];
            }
        }

        return $r;
    }

    /**
     * @param RequestInterface $r
     * @return array(
     *  @type int duration in microseconds
     *  @type \Psr\Http\Message\ResponseInterface|\Http\Promise\Promise|null http response
     *  @type \DCarbone\PHPConsulAPI\Error|null any seen errors
     * )
     */
    protected function doRequest(RequestInterface $r)
    {
        $rt = microtime(true);
        $response = null;
        $err = null;
        try
        {
            // If we actually have a client defined...
            if (isset($this->Config->HttpClient))
                $response = $this->Config->HttpClient->sendRequest($r);
            // Otherwise, throw error to be caught below
            else
                throw new \RuntimeException('Unable to execute query as no HttpClient has been defined.');
        }
        catch (\Exception $e)
        {
            // If there has been an exception of any kind, catch it and create Error object
            $err = new Error(sprintf(
                '%s - Error seen while executing "%s".  Message: "%s"',
                get_class($this),
                $r->getUri(),
                $e->getMessage()
            ));
        }

        // Calculate duration and move along whatever response and error we see (if any)
        return [(int)((microtime(true) - $rt) * 1000000), $response, $err];
    }

    /**
     * @param int $duration
     * @param ResponseInterface $response
     * @param Uri $uri
     * @return QueryMeta
     */
    protected function buildQueryMeta($duration, ResponseInterface $response, Uri $uri)
    {
        $qm = new QueryMeta();

        $qm->requestTime = $duration;
        $qm->requestUrl = (string)$uri;

        if ($response->hasHeader('X-Consul-Index'))
            $qm->lastIndex = (int)$response->getHeaderLine('X-Consul-Index');

        if ($response->hasHeader('X-Consul-KnownLeader'))
            $qm->knownLeader = (bool)$response->getHeaderLine('X-Consul-KnownLeader');
        else if ($response->hasHeader('X-Consul-Knownleader'))
            $qm->knownLeader = (bool)$response->getHeaderLine('X-Consul-Knownleader');

        if ($response->hasHeader('X-Consul-LastContact'))
            $qm->lastContact = (int)$response->getHeaderLine('X-Consul-LastContact');
        else if ($response->hasHeader('X-Consul-Lastcontact'))
            $qm->lastContact = (int)$response->getHeaderLine('X-Consul-Lastcontact');

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
     * @param StreamInterface $body
     * @return array(
     *  @type array|string|bool|int|float decoded response
     *  @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    protected function decodeBody(StreamInterface $body)
    {
        $data = @json_decode((string)$body, true);
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