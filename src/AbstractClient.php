<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\HTTP;
use DCarbone\Go\Time;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class AbstractClient
 */
abstract class AbstractClient
{
    /** @var \DCarbone\PHPConsulAPI\Config */
    protected Config $config;

    /**
     * AbstractConsulClient constructor.
     * @param \DCarbone\PHPConsulAPI\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $duration
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface $uri
     * @return \DCarbone\PHPConsulAPI\QueryMeta
     */
    protected function _buildQueryMeta(
        Time\Duration $duration,
        ResponseInterface $response,
        UriInterface $uri
    ): QueryMeta {
        $qm = new QueryMeta();

        $qm->RequestTime = $duration;
        $qm->RequestUrl  = (string)$uri;

        if ('' !== ($h = $response->getHeaderLine(Consul::headerConsulIndex))) {
            $qm->LastIndex = (int)$h;
        }

        $qm->LastContentHash = $response->getHeaderLine(Consul::headerConsulContentHash);

        // note: do not need to check both as guzzle response compares headers insensitively
        if ('' !== ($h = $response->getHeaderLine(Consul::headerConsulKnownLeader))) {
            $qm->KnownLeader = (bool)$h;
        }
        // note: do not need to check both as guzzle response compares headers insensitively
        if ('' !== ($h = $response->getHeaderLine(Consul::headerConsulLastContact))) {
            $qm->LastContact = (int)$h;
        }

        if ('' !== ($h = $response->getHeaderLine(Consul::headerConsulTranslateAddresses))) {
            $qm->AddressTranslationEnabled = (bool)$h;
        }

        if ('' !== ($h = $response->getHeaderLine(Consul::headerCache))) {
            $qm->CacheAge = Time::Duration(\intval($h, 10) * Time::Second);
        }

        return $qm;
    }

    /**
     * @param \DCarbone\Go\Time\Duration $duration
     * @return \DCarbone\PHPConsulAPI\WriteMeta
     */
    protected function _buildWriteMeta(Time\Duration $duration): WriteMeta
    {
        $wm              = new WriteMeta();
        $wm->RequestTime = $duration;

        return $wm;
    }

    /**
     * @param string $method
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\RequestOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Request
     */
    protected function _newRequest(string $method, string $path, $body, ?RequestOptions $opts): Request
    {
        $r = new Request($method, $path, $this->config, $body);
        $r->applyOptions($opts);
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\RequestOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Request
     */
    protected function _newPutRequest(string $path, $body, ?RequestOptions $opts): Request
    {
        return $this->_newRequest(HTTP\MethodPut, $path, $body, $opts);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Request
     */
    protected function _newGetRequest(string $path, ?QueryOptions $opts): Request
    {
        return $this->_newRequest(HTTP\MethodGet, $path, null, $opts);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Request
     */
    protected function _newDeleteRequest(string $path, ?WriteOptions $opts): Request
    {
        return $this->_newRequest(HTTP\MethodDelete, $path, null, $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Request $r
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _do(Request $r): RequestResponse
    {
        $start    = \microtime(true);
        $response = null;
        $err      = null;

        try {
            // If we actually have a client defined...
            if (isset($this->config->HttpClient) && $this->config->HttpClient instanceof ClientInterface) {
                $response = $this->config->HttpClient->send(
                    $r->toPsrRequest(),
                    $this->config->getGuzzleRequestOptions($r)
                );
            } // Otherwise, throw error to be caught below
            else {
                throw new \RuntimeException('Unable to execute query as no HttpClient has been defined.');
            }
        } catch (\Exception $e) {
            // If there has been an exception of any kind, catch it and create Error object
            $err = new Error(
                \sprintf(
                    '%s - Error seen while executing "%s".  Message: "%s"',
                    \get_class($this),
                    $r->getUri(),
                    $e->getMessage()
                )
            );
        }

        // calculate execution time
        $dur = new Time\Duration(\intval((\microtime(true) - $start) * Time::Second, 10));

        return new RequestResponse($r->meta(), $dur, $response, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $r
     * @param int $statusCode
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _requireStatus(RequestResponse $r, int $statusCode): RequestResponse
    {
        // If a previous error occurred, just return as-is.
        if (null !== $r->Err) {
            return $r;
        }

        // If we have any kind of response...
        if (null !== $r->Response) {
            // If this is a response...
            if ($r->Response instanceof ResponseInterface) {
                // Get the response code...
                $actualCode = $r->Response->getStatusCode();

                // If $desiredCode, move right along
                if ($statusCode === $actualCode) {
                    return $r;
                }

                // Otherwise, return error
                $r->Err = new Error(
                    \sprintf(
                        '%s - Non-%d response seen.  Response code: %d.  Message: %s',
                        \get_class($this),
                        $statusCode,
                        $actualCode,
                        $r->Response->getReasonPhrase()
                    )
                );
            } else {
                $r->Err = new Error(
                    \sprintf(
                        '%s - Expected response to be instance of \\Psr\\Message\\ResponseInterface, %s seen.',
                        \get_class($this),
                        \is_object($r->Response) ? \get_class($r->Response) : \gettype($r->Response)
                    )
                );
            }
        }

        return $r;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $r
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _requireOK(RequestResponse $r): RequestResponse
    {
        return $this->_requireStatus($r, HTTP\StatusOK);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _doGet(string $path, ?QueryOptions $opts): RequestResponse
    {
        return $this->_do($this->_newGetRequest($path, $opts));
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\RequestOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _doPut(string $path, $body, ?RequestOptions $opts): RequestResponse
    {
        return $this->_do($this->_newPutRequest($path, $body, $opts));
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _doDelete(string $path, ?WriteOptions $opts): RequestResponse
    {
        return $this->_do($this->_newDeleteRequest($path, $opts));
    }

    /**
     * @param \Psr\Http\Message\StreamInterface $body
     * @return \DCarbone\PHPConsulAPI\DecodedBody
     */
    protected function _decodeBody(StreamInterface $body): DecodedBody
    {
        $data = @\json_decode((string)$body, true);

        if (\JSON_ERROR_NONE === \json_last_error()) {
            return new DecodedBody($data, null);
        }

        return new DecodedBody(
            null,
            new Error(
                \sprintf(
                    '%s - Unable to parse response as JSON.  Message: %s',
                    \get_class($this),
                    \json_last_error_msg()
                )
            )
        );
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    protected function _executePut(string $path, $body, ?WriteOptions $opts): WriteResponse
    {
        $resp = $this->_requireOK($this->_doPut($path, $body, $opts));
        return new WriteResponse($this->_buildWriteMeta($resp->Duration), $resp->Err);
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    protected function _executeDelete(string $path, ?WriteOptions $opts): WriteResponse
    {
        $resp = $this->_requireOK($this->_doDelete($path, $opts));
        return new WriteResponse($this->_buildWriteMeta($resp->Duration), $resp->Err);
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    protected function _doPutValuedStr(string $path, $body, ?WriteOptions $opts): ValuedWriteStringResponse
    {
        $r    = $this->_newPutRequest($path, $body, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedWriteStringResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringResponse
     */
    protected function _doGetValuedStr(string $path, ?QueryOptions $opts): ValuedQueryStringResponse
    {
        $r    = $this->_newGetRequest($path, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedQueryStringResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringsResponse
     */
    protected function _doGetValuedStrs(string $path, ?QueryOptions $opts): ValuedQueryStringsResponse
    {
        $r    = $this->_newGetRequest($path, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedQueryStringsResponse();
        $this->_hydrateResponse($resp, $ret);
        return $ret;
    }

    /**
     * todo: move into Hydrator?
     *
     * @param \DCarbone\PHPConsulAPI\RequestResponse $resp
     * @param \DCarbone\PHPConsulAPI\AbstractResponse $ret
     */
    protected function _hydrateResponse(RequestResponse $resp, AbstractResponse $ret): void
    {
        // determine if this response contains a *Meta field
        if (\property_exists($ret, Hydration::FIELD_QUERY_META)) {
            $ret->QueryMeta = $this->_buildQueryMeta($resp->Duration, $resp->Response, $resp->RequestMeta->uri);
        } elseif (\property_exists($ret, Hydration::FIELD_WRITE_META)) {
            $ret->WriteMeta = $this->_buildWriteMeta($resp->Duration);
        }

        // todo: this can probably go away...
        $hasErrField = \property_exists($ret, Hydration::FIELD_ERR);

        // if there was an error in the response, set and return
        if (null !== $resp->Err) {
            if ($hasErrField) {
                $ret->Err = $resp->Err;
            }
            return;
        }

        // if this response type is non-valued, return
        if (!($ret instanceof HydratedResponseInterface)) {
            return;
        }

        // attempt response decoded
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            if ($hasErrField) {
                $ret->Err = $dec->Err;
            }
            return;
        }

        // finally, have response create its value
        $ret->hydrateValue($dec->Decoded);
    }
}
