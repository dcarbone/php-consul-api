<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;
use DCarbone\PHPConsulAPI\Health\HealthCheck;

class CheckTxnOp extends AbstractType
{
    public CheckOp $Verb;
    public HealthCheck $Check;

    public function __construct(CheckOp|string $Verb = CheckOp::UNDEFINED, null|HealthCheck $Check = null)
    {
        $this->setVerb($Verb);
        if (null === $Check) {
            $Check = new HealthCheck();
        }
        $this->Check = $Check;
    }

    public function getVerb(): CheckOp
    {
        return $this->Verb;
    }

    public function setVerb(string|CheckOp $Verb): self
    {
        if (is_string($Verb)) {
            $Verb = CheckOp::from($Verb);
        }
        $this->Verb = $Verb;
        return $this;
    }

    public function getCheck(): HealthCheck
    {
        return $this->Check;
    }

    public function setCheck(HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Check' === $k) {
                $n->Check = HealthCheck::jsonUnserialize($v);
            } elseif ('Verb' === $k) {
                $n->setVerb($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Verb = $this->Verb;
        $out->Check = $this->Check;
        return $out;
    }
}
