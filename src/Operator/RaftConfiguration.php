<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class RaftConfiguration
 */
class RaftConfiguration extends AbstractModel
{
    private const FIELD_SERVERS = 'Servers';

    /** @var \DCarbone\PHPConsulAPI\Operator\RaftServer[] */
    public array $Servers = [];
    /** @var int */
    public int $Index = 0;

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_SERVERS => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => RaftServer::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    /**
     * RaftConfiguration constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (0 < \count($this->Servers)) {
            $this->Servers = \array_filter($this->Servers);
            foreach ($this->Servers as &$v) {
                if (!($v instanceof RaftServer)) {
                    $v = new RaftServer($v);
                }
            }
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer[]
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\RaftServer[] $servers
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function setServers(array $servers): self
    {
        $this->Servers = [];
        foreach ($servers as $Server) {
            $this->addServer($Server);
        }
        return $this;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\RaftServer $server
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function addServer(RaftServer $server): self
    {
        $this->Servers[] = $server;
        return $this;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->Index;
    }

    /**
     * @param int $index
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function setIndex(int $index): self
    {
        $this->Index = $index;
        return $this;
    }
}
