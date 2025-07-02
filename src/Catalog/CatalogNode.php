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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Agent\AgentService;

class CatalogNode extends AbstractModel
{
    public null|Node $Node;
    public null|\stdClass $Services;

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\Node|null $Node
     * @param null|\stdClass $Services
     */
    public function __construct(
        null|Node $Node = null,
        null|\stdClass $Services = null
    ) {
        $this->Node = $Node;
        $this->setServices($Services);
}

    public function getNode(): null|Node
    {
        return $this->Node;
    }

    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getServices(): null|\stdClass
    {
        return $this->Services;
    }

    public function setServices(null|\stdClass $Services): self
    {
        if (null === $Services) {
            $this->Services = null;
            return $this;
        }
        $this->Services = new \stdClass();
        foreach ($Services as $k => $v) {
            $this->Services->{$k} = $v instanceof AgentService ? $v : AgentService::jsonUnserialize($v);
        }
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Node' === $k) {
                $n->Node = null === $v ? null : Node::jsonUnserialize($v);
            } elseif ('Services' === $k) {
                $n->setServices($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Node = $this->Node;
        $out->Services = $this->Services;
        return $out;
    }
}
