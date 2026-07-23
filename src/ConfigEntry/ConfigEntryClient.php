<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPI\PHPLib\ValuedWriteBoolResponse;
use DCarbone\PHPConsulAPI\PHPLib\WriteResponse;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\WriteOptions;

class ConfigEntryClient extends AbstractClient
{
    public function Get(string $kind, string $name, null|QueryOptions $opts = null): ConfigEntryQueryResponse
    {
        $ret = new ConfigEntryQueryResponse();
        if ('' === $kind || '' === $name) {
            $ret->Err = new Error('Both kind and name parameters must not be empty');
            return $ret;
        }

        $resp = $this->_requireOK($this->_doGet(sprintf('v1/config/%s/%s', urlencode($kind), urlencode($name)), $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function List(string $kind, null|QueryOptions $opts = null): ConfigEntriesQueryResponse
    {
        $ret = new ConfigEntriesQueryResponse();
        if ('' === $kind) {
            $ret->Err = new Error('The kind parameter must not be empty');
            return $ret;
        }

        $resp = $this->_requireOK($this->_doGet(sprintf('v1/config/%s', urlencode($kind)), $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Set(ConfigEntry $entry, null|WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $resp = $this->_requireOK($this->_doPut('v1/config', $entry, $opts));
        $ret  = new ValuedWriteBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function CAS(ConfigEntry $entry, int $index, null|WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $r = $this->_newPutRequest('v1/config', $entry, $opts);
        $r->params->set('cas', (string)$index);
        $resp = $this->_requireOK($this->_do($r));
        $ret  = new ValuedWriteBoolResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Delete(string $kind, string $name, null|WriteOptions $opts = null): WriteResponse
    {
        $ret = new WriteResponse();
        if ('' === $kind || '' === $name) {
            $ret->Err = new Error('Both kind and name parameters must not be empty');
            return $ret;
        }
        return $this->_executeDelete(sprintf('v1/config/%s/%s', urlencode($kind), urlencode($name)), $opts);
    }

    public function DeleteCAS(string $kind, string $name, int $index, null|WriteOptions $opts = null): ValuedWriteBoolResponse
    {
        $ret = new ValuedWriteBoolResponse();
        if ('' === $kind || '' === $name) {
            $ret->Err = new Error('Both kind and name parameters must not be empty');
            return $ret;
        }

        $r = $this->_newDeleteRequest(sprintf('v1/config/%s/%s', urlencode($kind), urlencode($name)), $opts);
        $r->params->set('cas', (string)$index);
        $resp = $this->_requireOK($this->_do($r));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
