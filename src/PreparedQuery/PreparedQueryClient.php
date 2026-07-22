<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractClient;
use DCarbone\PHPConsulAPI\PHPLib\RequestResponse;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class PreparedQueryClient extends AbstractClient
{
    public function Create(PreparedQueryDefinition $query, null|WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_writeIDResponse($this->_requireOK($this->_doPost('v1/query', $query, $opts)));
    }

    public function Update(PreparedQueryDefinition $query, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut(sprintf('v1/query/%s', urlencode($query->ID)), $query, $opts);
    }

    public function List(null|QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/query', $opts));
        $ret  = new PreparedQueryDefinitionsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Get(string $queryID, null|QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/query/%s', urlencode($queryID)), $opts));
        $ret  = new PreparedQueryDefinitionsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Delete(string $queryID, null|WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('v1/query/%s', $queryID), $opts);
    }

    public function Execute(string $queryIDOrName, null|QueryOptions $opts = null): PreparedQueryExecuteResponseResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/query/%s/execute', urlencode($queryIDOrName)), $opts));
        $ret  = new PreparedQueryExecuteResponseResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    private function _writeIDResponse(RequestResponse $resp): ValuedWriteStringResponse
    {
        $ret = new ValuedWriteStringResponse();
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }
        $ret->WriteMeta = $resp->buildWriteMeta();
        $dec = $this->_decodeBody($resp->Response->getBody());
        if (null !== $dec->Err) {
            $ret->Err = $dec->Err;
            return $ret;
        }
        if ($dec->Decoded instanceof \stdClass && isset($dec->Decoded->ID)) {
            $ret->Value = (string)$dec->Decoded->ID;
        } else {
            $ret->Value = '';
        }
        return $ret;
    }
}
