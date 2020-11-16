<?php namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Agent\AgentService;

/**
 * Class CatalogNode
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogNode extends AbstractModel {
    /** @var \DCarbone\PHPConsulAPI\Catalog\Node */
    public $Node = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService[] */
    public $Services = [];

    /**
     * CatalogNode constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (is_array($this->Node)) {
            $this->Node = new Node($this->Node);
        }
        if (0 < count($this->Services)) {
            $this->Services = array_filter($this->Services);
            foreach (array_keys($this->Services) as &$service) {
                if (!($this->Services[$service] instanceof AgentService)) {
                    $service = new AgentService($this->Services[$service]);
                }
            }
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\Node
     */
    public function getNode(): Node {
        return $this->Node;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService[]
     */
    public function getServices(): array {
        return $this->Services;
    }
}