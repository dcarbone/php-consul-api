<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

class ServiceIntentionsConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    public string $Kind;
    public string $name;
    public string $Partition;
    public string $Namespace;
    /** @var array<null|\DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention> */
    public array $Sources;
    public null|IntentionJWTRequirement $JWT;

    /**
     * @param array<null|\DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention> $Sources
     * @param null|\stdClass|array<string,string> $Meta
     */
    public function __construct(
        string $Kind = '',
        string $name = '',
        string $Partition = '',
        string $Namespace = '',
        array $Sources = [],
        null|IntentionJWTRequirement $JWT = null,
        null|\stdClass|array $Meta = null,
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
    ) {
        $this->Kind = $Kind;
        $this->name = $name;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->setSources(...$Sources);
        $this->JWT = $JWT;
        $this->setMeta($Meta);
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
    }

    public function getKind(): string
    {
        return $this->Kind;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    /**
     * @return array<null|\DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention>
     */
    public function getSources(): array
    {
        return $this->Sources;
    }

    public function setSources(null|SourceIntention ...$Sources): self
    {
        $this->Sources = $Sources;
        return $this;
    }

    public function getJWT(): null|IntentionJWTRequirement
    {
        return $this->JWT;
    }

    public function setJWT(null|IntentionJWTRequirement $JWT): self
    {
        $this->JWT = $JWT;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Sources' === $k) {
                $n->Sources = [];
                foreach ($v as $vv) {
                    $n->Sources[] = null === $vv ? null : SourceIntention::jsonUnserialize($vv);
                }
            } elseif ('JWT' === $k) {
                $n->JWT = null === $v ? null : IntentionJWTRequirement::jsonUnserialize($v);
            } elseif ('Meta' === $k) {
                $n->setMeta($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Kind = $this->Kind;
        $out->Name = $this->name;
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ([] !== $this->Sources) {
            $out->Sources = $this->Sources;
        }
        if (null !== $this->JWT) {
            $out->JWT = $this->JWT;
        }
        if (isset($this->Meta)) {
            $out->Meta = $this->Meta;
        }
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        return $out;
    }
}
