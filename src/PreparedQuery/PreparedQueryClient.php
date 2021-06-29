<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

/**
 * Class PreparedQueryClient
 */
class PreparedQueryClient extends AbstractClient
{
    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition $query
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\ValuedWriteStringResponse
     */
    public function Create(PreparedQueryDefinition $query, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $resp = $this->_requireOK($this->_doPost('v1/query', $query, $opts));
        $ret  = new ValuedWriteStringResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinition $query
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Update(PreparedQueryDefinition $query, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/query', $query, $opts);
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinitionsResponse
     */
    public function List(?QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $resp = $this->_doGet('v1/query', $opts);
        $ret  = new PreparedQueryDefinitionsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $queryID
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryDefinitionsResponse
     */
    public function Get(string $queryID, ?QueryOptions $opts = null): PreparedQueryDefinitionsResponse
    {
        $resp = $this->_doGet(\sprintf('v1/query/%s', $queryID), $opts);
        $ret  = new PreparedQueryDefinitionsResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    /**
     * @param string $queryID
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return \DCarbone\PHPConsulAPI\WriteResponse
     */
    public function Delete(string $queryID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(\sprintf('v1/query/%s', $queryID), $opts);
    }

    /**
     * @param string $queryIDOrName
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponseResponse
     */
    public function Execute(string $queryIDOrName, ?QueryOptions $opts = null): PreparedQueryExecuteResponseResponse
    {
        $resp = $this->_doGet(\sprintf('v1/query/%s/execute', $queryIDOrName), $opts);
        $ret  = new PreparedQueryExecuteResponseResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
