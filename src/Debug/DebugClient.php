<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Debug;

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

use DCarbone\Go\HTTP;
use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\ValuedStringResponse;

class DebugClient extends AbstractClient
{
    public function Heap(): ValuedStringResponse
    {
        $ret  = new ValuedStringResponse();
        $resp = $this->_doGet('/debug/pprof/heap', null);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        if (HTTP\StatusOK !== $resp->Response->getStatusCode()) {
            $ret->Err = Error::unexpectedResponseCodeError($resp);
        }
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Profile(int $seconds): ValuedStringResponse
    {
        $ret = new ValuedStringResponse();
        $req = $this->_newGetRequest('/debug/pprof/profile', null);
        $req->params->set('seconds', (string)$seconds);
        $resp = $this->_do($req);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        if (HTTP\StatusOK !== $resp->Response->getStatusCode()) {
            $ret->Err = Error::unexpectedResponseCodeError($resp);
            return $ret;
        }
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Trace(int $seconds): ValuedStringResponse
    {
        $ret = new ValuedStringResponse();
        $req = $this->_newGetRequest('/debug/pprof/trace', null);
        $req->params->set('seconds', (string)$seconds);
        $resp = $this->_do($req);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        if (HTTP\StatusOK !== $resp->Response->getStatusCode()) {
            $ret->Err = Error::unexpectedResponseCodeError($resp);
            return $ret;
        }
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Goroutine(): ValuedStringResponse
    {
        $ret  = new ValuedStringResponse();
        $resp = $this->_doGet('/debug/pprof/goroutine', null);
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        if (HTTP\StatusOK !== $resp->Response->getStatusCode()) {
            $ret->Err = Error::unexpectedResponseCodeError($resp);
        }
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
