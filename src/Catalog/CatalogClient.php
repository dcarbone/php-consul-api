<?php namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractConsulClient;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;

/**
 * Class CatalogClient
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogClient extends AbstractConsulClient
{
    /**
     * @param QueryOptions|null $queryOptions
     * @return array(
     * @type string[]|null list of services or null on error
     * @type QueryMeta query metadata
     * @type \DCarbone\PHPConsulAPI\Error|null error, if any
     * )
     */
    public function services(QueryOptions $queryOptions = null)
    {
        $r = new Request('get', 'v1/catalog/services', $this->_Config);
        $r->setQueryOptions($queryOptions);

        list($duration, $response, $err) = $this->requireOK($this->doRequest($r));
        $qm = $this->buildQueryMeta($duration, $response);

        if (null !== $err)
            return [null, $qm, $err];

        list($data, $err) = $this->decodeBody($response);

        return [$data, $qm, $err];
    }
}