<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\AbstractValuedQueryResponse;
use DCarbone\PHPConsulAPI\UnmarshalledResponseInterface;

/**
 * Class ACLReplicationStatusResponse
 */
class ACLReplicationStatusResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatus|null */
    public ?ACLReplicationStatus $ACLReplicationStatus = null;

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLReplicationStatus|null
     */
    public function getValue(): ?ACLReplicationStatus
    {
        return $this->ACLReplicationStatus;
    }

    /**
     * @param mixed $decodedData
     */
    public function unmarshalValue($decodedData): void
    {
        $this->ACLReplicationStatus = new ACLReplicationStatus((array)$decodedData);
    }
}
