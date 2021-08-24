<?php declare(strict_types=1);

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
 * Class ACLBindingRuleWriteResponse
 */
class ACLBindingRuleQueryResponse extends AbstractValuedQueryResponse implements UnmarshalledResponseInterface
{
    /** @var \DCarbone\PHPConsulAPI\ACL\ACLBindingRule|null */
    public ?ACLBindingRule $ACLBindingRule = null;

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLBindingRule|null
     */
    public function getValue(): ?ACLBindingRule
    {
        return $this->ACLBindingRule;
    }

    /**
     * @param mixed $decodedData
     */
    public function unmarshalValue($decodedData): void
    {
        $this->ACLBindingRule = new ACLBindingRule((array)$decodedData);
    }
}
