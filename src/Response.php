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
 * Class HttpResponse
 * @package DCarbone\PHPConsulAPI
 */
class Response
{
    /** @var string */
    public $url = '';
    /** @var int */
    public $httpCode = 0;
    /** @var string */
    public $contentType = '';
    /** @var int */
    public $headerSize = 0;
    /** @var int */
    public $requestSize = 0;
    /** @var bool */
    public $sslVerifyResult = false;
    /** @var int */
    public $redirectCount = 0;
    /** @var float */
    public $totalTime = 0.0;
    /** @var float */
    public $nameLookupTime = 0.0;
    /** @var float */
    public $connectTime = 0.0;
    /** @var float */
    public $preTransferTime = 0.0;
    /** @var float */
    public $sizeUpload = 0.0;
    /** @var float */
    public $sizeDownload = 0.0;
    /** @var float */
    public $speedDownload = 0.0;
    /** @var float */
    public $speedUpload = 0.0;
    /** @var float */
    public $downloadContentLength = 0.0;
    /** @var float */
    public $uploadContentLength = 0.0;
    /** @var float */
    public $startTransferTime = 0.0;
    /** @var float */
    public $redirectTime = 0.0;
    /** @var string */
    public $redirectURL = '';
    /** @var string */
    public $primaryIP = '';
    /** @var array */
    public $certInfo = array();
    /** @var int */
    public $primaryPort = 0;
    /** @var string */
    public $localIP = '';
    /** @var int */
    public $localPort = 0;
    /** @var string */
    public $requestHeader = '';

    /** @var array */
    public $responseHeaders = array();
    /** @var string */
    public $body = '';

    /** @var string */
    public $curlError;

    /** @var array */
    private $_requestHeaderArray = null;

    /**
     * HttpResponse constructor.
     * @param string|bool $response
     * @param array $curlInfo
     * @param string $curlError
     */
    public function __construct($response, array $curlInfo, $curlError)
    {
        $this->url = $curlInfo['url'];
        $this->contentType = $curlInfo['content_type'];
        $this->httpCode = $curlInfo['http_code'];
        $this->headerSize = $curlInfo['header_size'];
        $this->requestSize = $curlInfo['request_size'];
        $this->sslVerifyResult = (bool)$curlInfo['ssl_verify_result'];
        $this->redirectCount = $curlInfo['redirect_count'];
        $this->totalTime = $curlInfo['total_time'];
        $this->nameLookupTime = $curlInfo['namelookup_time'];
        $this->connectTime = $curlInfo['connect_time'];
        $this->preTransferTime = $curlInfo['pretransfer_time'];
        $this->sizeDownload = $curlInfo['size_download'];
        $this->sizeUpload = $curlInfo['size_upload'];
        $this->speedDownload = $curlInfo['speed_download'];
        $this->speedUpload = $curlInfo['speed_upload'];
        $this->downloadContentLength = $curlInfo['download_content_length'];
        $this->uploadContentLength = $curlInfo['upload_content_length'];
        $this->startTransferTime = $curlInfo['starttransfer_time'];
        $this->redirectTime = $curlInfo['redirect_time'];
        $this->redirectURL = $curlInfo['redirect_url'];
        $this->primaryIP = $curlInfo['primary_ip'];
        $this->certInfo = $curlInfo['certinfo'];
        $this->primaryPort = $curlInfo['primary_port'];
        $this->localIP = $curlInfo['local_ip'];
        $this->localPort = $curlInfo['local_port'];
        if (isset($curlInfo['request_header']))
            $this->requestHeader = $curlInfo['request_header'];

        $this->curlError = $curlError;

        if (is_string($response))
            list($this->responseHeaders, $this->body) = CURLHeaderExtractor::getHeaderAndBody($response);
    }

    /**
     * @return array
     */
    public function getRequestHeaderArray()
    {
        if (null === $this->_requestHeaderArray)
        {
            $this->_requestHeaderArray = array();

            foreach(explode("\r\n", $this->requestHeader) as $header)
            {
                if ('' === $header)
                    continue;

                $pos = strpos($header, ':');
                if (false === $pos)
                    $this->_requestHeaderArray[] = $header;
                else
                    $this->_requestHeaderArray[substr($header, 0, $pos)] = ltrim(substr($header, $pos + 1));
            }
        }

        return $this->_requestHeaderArray;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->body;
    }
}