<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\PHPConsulAPI\AbstractModel;

class ACLNodeIdentity extends AbstractModel
{
    public string $NodeName;
    public string $Datacenter;

    public function __construct(string $NodeName = '', string $Datacenter = '')
    {
        $this->NodeName = $NodeName;
        $this->Datacenter = $Datacenter;
    }

    public function getNodeName(): string
    {
        return $this->NodeName;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new static();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->NodeName = $this->NodeName;
        $out->Datacenter = $this->Datacenter;
        return $out;
    }
}
