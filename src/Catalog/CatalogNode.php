<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Agent\AgentService;

class CatalogNode extends AbstractType
{
    public null|Node $Node;
    /** @var array<string,\DCarbone\PHPConsulAPI\Agent\AgentService> */
    public array $Services;

    public function getNode(): null|Node
    {
        return $this->Node ?? null;
    }

    /**
     * @return null|array<string,\DCarbone\PHPConsulAPI\Agent\AgentService>
     */
    public function getServices(): null|array
    {
        return $this->Services ?? null;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Node' === $k) {
                $n->Node = null === $v ? null : Node::jsonUnserialize($v);
            } elseif ('Services' === $k) {
                $n->Services = [];
                foreach ($v as $kk => $vv) {
                    $n->Services[$kk] = AgentService::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Node = $this->getNode();
        $out->Services = $this->getServices();
        return $out;
    }
}
