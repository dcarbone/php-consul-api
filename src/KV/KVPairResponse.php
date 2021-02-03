<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryMeta;

/**
 * Class KVPairResponse
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVPairResponse extends AbstractValuedQueryResponse
{
    /** @var \DCarbone\PHPConsulAPI\KV\KVPair|null */
    public ?KVPair $KVPair = null;

    /**
     * KVPairResponse constructor.
     * @param array|null $kvp
     * @param \DCarbone\PHPConsulAPI\QueryMeta|null $qm
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?array $kvp, ?QueryMeta $qm, ?Error $err)
    {
        parent::__construct($qm, $err);
        if (null !== $kvp) {
            $this->KVPair = new KVPair($kvp, true);
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair|null
     */
    public function getValue(): ?KVPair
    {
        return $this->KVPair;
    }
}