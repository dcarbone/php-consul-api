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
use DCarbone\PHPConsulAPI\Catalog\Node;

class NodeTxnOp extends AbstractType
{
    public NodeOp $Verb;
    public Node $Node;

    public function __construct(
        NodeOp|string $Verb = NodeOp::UNDEFINED,
        null|Node $Node = null,
    ) {
        $this->setVerb($Verb);
        if (null === $Node) {
            $Node = new Node();
        }
        $this->Node = $Node;
    }

    public function getVerb(): NodeOp
    {
        return $this->Verb;
    }

    public function setVerb(NodeOp|string $Verb): self
    {
        if (is_string($Verb)) {
            $Verb = NodeOp::from($Verb);
        }
        $this->Verb = $Verb;
        return $this;
    }

    public function getNode(): Node
    {
        return $this->Node;
    }

    public function setNode(Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Verb' === $k) {
                $n->setVerb($v);
            } elseif ('Node' === $k) {
                $n->Node = Node::jsonUnserialize($v);
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
        $out->Node = $this->Node;
        return $out;
    }
}
