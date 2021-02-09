<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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

use DCarbone\PHPConsulAPI\ErrorContainer;
use DCarbone\PHPConsulAPI\QueryMetaContainer;

/**
 * Class KVTxnAPIResponse
 */
class KVTxnAPIResponse
{
    use QueryMetaContainer;
    use ErrorContainer;

    /** @var bool */
    public bool $OK = false;
    /** @var \DCarbone\PHPConsulAPI\KV\KVTxnResponse|null */
    public ?KVTxnResponse $KVTxnResponse = null;

    /**
     * @return bool
     */
    public function isOK(): bool
    {
        return $this->OK;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVTxnResponse|null
     */
    public function getKVTxnResponse(): ?KVTxnResponse
    {
        return $this->KVTxnResponse;
    }
}
