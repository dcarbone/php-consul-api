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
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class AbstractClient
 */
abstract class AbstractClient
{
    /** @var \DCarbone\PHPConsulAPI\Config */
    protected Config $_config;

    /**
     * AbstractConsulClient constructor.
     * @param \DCarbone\PHPConsulAPI\Config $config
     */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function getConfig(): Config
    {
        return $this->_config;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Request $r
     * @return array
     */
    protected function _buildGuzzleRequestOptions(Request $r): array
    {
        // todo: figure out better guzzle integration

        $opts = Config::DEFAULT_REQUEST_OPTIONS;

        if (!$this->_config->InsecureSkipVerify) {
            $opts[GuzzleRequestOptions::VERIFY] = false;
        } elseif ('' !== ($b = $this->_config->CAFile)) {
            $opts[GuzzleRequestOptions::VERIFY] = $b;
        }

        if ('' !== ($c = $this->_config->CertFile)) {
            $opts[GuzzleRequestOptions::CERT]    = $c;
            $opts[GuzzleRequestOptions::SSL_KEY] = $this->_config->KeyFile;
        }

        if (null !== $r->timeout && 0 < ($ttl = \intval($r->timeout->Seconds(), 10))) {
            $opts[GuzzleRequestOptions::TIMEOUT] = $ttl;
        }

        // todo: per-request content and accept value setting.
        $body = $r->getBody();
        if (null !== $body) {
            if (is_scalar($body)) {
                $opts[GuzzleRequestOptions::BODY] = $body;
            } else {
                $opts[GuzzleRequestOptions::JSON] = $body;
            }
        }

        return $opts;
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
        $r = new Request($method, $path, $this->_config, $body);
        $r->applyOptions($opts);
        return $r;
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\RequestOptions|null $opts
     * @return \DCarbone\PHPConsulAPI\Request
     */
    protected function _newPostRequest(string $path, $body, ?RequestOptions $opts): Request
    {
        return $this->_newRequest(HTTP\MethodPost, $path, $body, $opts);
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _do(Request $r): RequestResponse
    {
        $start    = microtime(true);
        $response = null;
        $err      = null;

        try {
            // If we actually have a client defined...
            if (isset($this->_config->HttpClient) && $this->_config->HttpClient instanceof ClientInterface) {
                $response = $this->_config->HttpClient->send(
                    $r->toPsrRequest(),
                    $this->_buildGuzzleRequestOptions($r)
                );
            } // Otherwise, throw error to be caught below
            else {
                throw new \RuntimeException('Unable to execute query as no HttpClient has been defined.');
            }
        } catch (\Exception $e) {
            // If there has been an exception of any kind, catch it and create Error object
            $err = new Error(
                sprintf(
                    '%s - Error seen while executing "%s".  Message: "%s"',
                    static::class,
                    $r->getUri(),
                    $e->getMessage()
                )
            );
        }

        // calculate execution time
        $dur = new Time\Duration(\intval((microtime(true) - $start) * Time::Second, 10));

        return new RequestResponse($r->meta(), $dur, $response, $err);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $r
     * @param int[] $allowed
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _requireStatus(RequestResponse $r, int ...$allowed): RequestResponse
    {
        // If a previous error occurred, just return as-is.
        if (null !== $r->Err) {
            return $r;
        }

        // if no response, return immediately
        if (null === $r->Response) {
            return $r;
        }

        // if, for whatever reason, we see an unexpected response structure...
        if (!($r->Response instanceof ResponseInterface)) {
            $r->Err = new Error(
                sprintf(
                    '%s - Expected response to be instance of \\Psr\\Message\\ResponseInterface, %s seen.',
                    static::class,
                    \is_object($r->Response) ? \get_class($r->Response) : \gettype($r->Response)
                )
            );
            return $r;
        }

        // once here, assume operable response instance

        // Get the response code...
        $actualCode = $r->Response->getStatusCode();

        // If response code is in allowed list, move right along
        if (\in_array($actualCode, $allowed, true)) {
            return $r;
        }

        // Otherwise, return error
        $r->Err = new Error(
            sprintf(
                '%s - Non-%d response seen.  Response code: %d.  Response: %s',
                static::class,
                $allowed,
                $actualCode,
                $r->Response->getBody()->getContents()
            )
        );

        return $r;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $r
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _requireOK(RequestResponse $r): RequestResponse
    {
        return $this->_requireStatus($r, HTTP\StatusOK);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestResponse $r
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\RequestResponse
     */
    protected function _requireNotFoundOrOK(RequestResponse $r): RequestResponse
    {
        return $this->_requireStatus($r, HTTP\StatusOK, HTTP\StatusNotFound);
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
    protected function _doPost(string $path, $body, ?RequestOptions $opts): RequestResponse
    {
        return $this->_do($this->_newPostRequest($path, $body, $opts));
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
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\DecodedBody
     */
    protected function _decodeBody(StreamInterface $body): DecodedBody
    {
        $data = @json_decode((string)$body, true);

        if (\JSON_ERROR_NONE === json_last_error()) {
            return new DecodedBody($data, null);
        }

        return new DecodedBody(
            null,
            new Error(
                sprintf(
                    '%s - Unable to parse response as JSON.  Message: %s',
                    static::class,
                    json_last_error_msg()
                )
            )
        );
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    protected function _executePut(string $path, $body, ?WriteOptions $opts): WriteResponse
    {
        $resp = $this->_requireOK($this->_doPut($path, $body, $opts));
        $ret  = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    protected function _executePost(string $path, $body, ?WriteOptions $opts): WriteResponse
    {
        $resp = $this->_requireOK($this->_doPost($path, $body, $opts));
        $ret  = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    protected function _executeDelete(string $path, ?WriteOptions $opts): WriteResponse
    {
        $resp = $this->_requireOK($this->_doDelete($path, $opts));
        $ret  = new WriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    protected function _executePutValuedStr(string $path, $body, ?WriteOptions $opts): ValuedWriteStringResponse
    {
        $r    = $this->_newPutRequest($path, $body, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedWriteStringResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringResponse
     */
    protected function _executeGetValuedStr(string $path, ?QueryOptions $opts): ValuedQueryStringResponse
    {
        $r    = $this->_newGetRequest($path, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedQueryStringResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedQueryStringsResponse
     */
    protected function _executeGetValuedStrs(string $path, ?QueryOptions $opts): ValuedQueryStringsResponse
    {
        $r    = $this->_newGetRequest($path, $opts);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedQueryStringsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * todo: move into Unmarshaller?
     *
     * @param \DCarbone\PHPConsulAPI\RequestResponse $resp
     * @param \DCarbone\PHPConsulAPI\AbstractResponse $ret
     * @throws \Exception
     */
    protected function _unmarshalResponse(RequestResponse $resp, AbstractResponse $ret): void
    {
        // determine if this response contains a *Meta field
        // TODO: change to use interfaces + instanceof?
        if (property_exists($ret, Transcoding::FIELD_QUERY_META)) {
            $ret->QueryMeta = $resp->buildQueryMeta();
        } elseif (property_exists($ret, Transcoding::FIELD_WRITE_META)) {
            $ret->WriteMeta = $resp->buildWriteMeta();
        }

        // todo: can probably assume that all responses have an Err field...
        $hasErrField = property_exists($ret, Transcoding::FIELD_ERR);

        // if there was an error in the response, set and return
        if (null !== $resp->Err) {
            if ($hasErrField) {
                $ret->Err = $resp->Err;
            }
            return;
        }

        // if this response type is non-valued, return
        if (!($ret instanceof UnmarshalledResponseInterface)) {
            return;
        }

        // attempt response decode
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            if ($hasErrField) {
                $ret->Err = $dec->Err;
            }
            return;
        }

        // finally, have response create its value
        $ret->unmarshalValue($dec->Decoded);
    }
}
