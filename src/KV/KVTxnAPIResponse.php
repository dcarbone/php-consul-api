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

use DCarbone\PHPConsulAPI\AbstractValuedResponse;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\ResponseQueryMetaTrait;

/**
 * Class KVTxnAPIResponse
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVTxnAPIResponse extends AbstractValuedResponse implements \ArrayAccess
{
    use ResponseQueryMetaTrait;

    /** @var bool */
    public $OK = false;
    /** @var \DCarbone\PHPConsulAPI\KV\KVTxnResponse|null */
    public $KVTxnResponse = null;

    /**
     * KVTxnAPIResponse constructor.
     * @param bool $ok
     * @param \DCarbone\PHPConsulAPI\KV\KVTxnResponse|null $resp
     * @param \DCarbone\PHPConsulAPI\QueryMeta|null $qm
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(bool $ok, ?KVTxnResponse $resp, ?QueryMeta $qm, ?Error $err)
    {
        parent::__construct($err);
        $this->OK = $ok;
        $this->KVTxnResponse = $resp;
        $this->QueryMeta = $qm;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVTxnResponse|mixed|null
     */
    public function getValue()
    {
        return $this->KVTxnResponse;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return is_int($offset) && 0 <= $offset && $offset < 4;
    }

    /**
     * @param mixed $offset
     * @return bool|\DCarbone\PHPConsulAPI\Error|\DCarbone\PHPConsulAPI\KV\KVTxnResponse|\DCarbone\PHPConsulAPI\QueryMeta|null
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->OK;
        } elseif (1 === $offset) {
            return $this->KVTxnResponse;
        } elseif (2 === $offset) {
            return $this->QueryMeta;
        } elseif (3 === $this->Err) {
            return $this->Err;
        } else {
            throw new \OutOfBoundsException(sprintf('Offset %s does not exist', var_export($offset, true)));
        }
    }
}