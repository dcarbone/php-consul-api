<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Debug;

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
use DCarbone\PHPConsulAPI\PHPLib\ValuedStringResponse;

class DebugClient extends AbstractClient
{
    public function Heap(): ValuedStringResponse
    {
        return $this->_pprofRequest('/debug/pprof/heap');
    }

    public function Profile(int $seconds): ValuedStringResponse
    {
        return $this->_pprofRequest('/debug/pprof/profile', $seconds);
    }

    public function Trace(int $seconds): ValuedStringResponse
    {
        return $this->_pprofRequest('/debug/pprof/trace', $seconds);
    }

    public function Goroutine(): ValuedStringResponse
    {
        return $this->_pprofRequest('/debug/pprof/goroutine');
    }

    public function PProf(string $name, int $seconds): ValuedStringResponse
    {
        return $this->_pprofRequest(sprintf('/debug/pprof/%s', trim($name, '/')), $seconds);
    }

    private function _pprofRequest(string $path, null|int $seconds = null): ValuedStringResponse
    {
        $ret = new ValuedStringResponse();
        $r = $this->_newGetRequest($path, null);
        if (null !== $seconds) {
            $r->params->set('seconds', (string)$seconds);
        }

        $resp = $this->_requireOK($this->_do($r));
        if (null !== $resp->Err) {
            $ret->Err = $resp->Err;
            return $ret;
        }

        $ret->Value = (string)$resp->Response->getBody();
        return $ret;
    }
}
