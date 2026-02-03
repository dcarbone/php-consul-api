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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class SourceIntention extends AbstractType
{
    public string $Name;
    public string $Peer;
    public string $Partition;
    public string $Namespace;
    public string $SamenessGroup;
    public IntentionAction $Action;
    /** @var array<null|\DCarbone\PHPConsulAPI\ConfigEntry\IntentionPermission> */
    public array $Permissions;
    public int $Precedence;
    public IntentionSourceType $Type;
    public string $Description;
    public string $LegacyID;
    public null|\stdClass $LegacyMeta;
    public null|Time\Time $LegacyCreateTime;
    public null|Time\Time $LegacyUpdateTime;

    /**
     * @param array<null|\DCarbone\PHPConsulAPI\ConfigEntry\IntentionPermission> $Permissions
     */
    public function __construct(
        string $Name = '',
        string $Peer = '',
        string $Partition = '',
        string $Namespace = '',
        string $SamenessGroup = '',
        string|IntentionAction $Action = IntentionAction::UNDEFINED,
        array $Permissions = [],
        int $Precedence = 0,
        string|IntentionSourceType $Type = IntentionSourceType::UNDEFINED,
        string $Description = '',
        string $LegacyID = '',
        null|\stdClass $LegacyMeta = null,
        null|Time\Time $LegacyCreateTime = null,
        null|Time\Time $LegacyUpdateTime = null,
    ) {
        $this->Name = $Name;
        $this->Peer = $Peer;
        $this->Partition = $Partition;
        $this->Namespace = $Namespace;
        $this->SamenessGroup = $SamenessGroup;
        $this->Action = $Action instanceof IntentionAction ? $Action : IntentionAction::from($Action);
        $this->setPermissions(...$Permissions);
        $this->Precedence = $Precedence;
        $this->Type = $Type instanceof IntentionSourceType ? $Type : IntentionSourceType::from($Type);
        $this->Description = $Description;
        $this->LegacyID = $LegacyID;
        $this->LegacyMeta = $LegacyMeta;
        $this->LegacyCreateTime = $LegacyCreateTime;
        $this->LegacyUpdateTime = $LegacyUpdateTime;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;
        return $this;
    }

    public function getPeer(): string
    {
        return $this->Peer;
    }

    public function setPeer(string $Peer): self
    {
        $this->Peer = $Peer;
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

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getSamenessGroup(): string
    {
        return $this->SamenessGroup;
    }

    public function setSamenessGroup(string $SamenessGroup): self
    {
        $this->SamenessGroup = $SamenessGroup;
        return $this;
    }

    public function getAction(): IntentionAction
    {
        return $this->Action;
    }

    public function setAction(IntentionAction $Action): self
    {
        $this->Action = $Action;
        return $this;
    }

    /**
     * @return array<null|\DCarbone\PHPConsulAPI\ConfigEntry\IntentionPermission>
     */
    public function getPermissions(): array
    {
        return $this->Permissions;
    }

    public function setPermissions(null|IntentionPermission ...$Permissions): self
    {
        $this->Permissions = $Permissions;
        return $this;
    }

    public function getPrecedence(): int
    {
        return $this->Precedence;
    }

    public function setPrecedence(int $Precedence): self
    {
        $this->Precedence = $Precedence;
        return $this;
    }

    public function getType(): IntentionSourceType
    {
        return $this->Type;
    }

    public function setType(IntentionSourceType $Type): self
    {
        $this->Type = $Type;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;
        return $this;
    }

    public function getLegacyID(): string
    {
        return $this->LegacyID;
    }

    public function setLegacyID(string $LegacyID): self
    {
        $this->LegacyID = $LegacyID;
        return $this;
    }

    public function getLegacyMeta(): null|\stdClass
    {
        return $this->LegacyMeta;
    }

    public function setLegacyMeta(null|\stdClass $LegacyMeta): self
    {
        $this->LegacyMeta = $LegacyMeta;
        return $this;
    }

    public function getLegacyCreateTime(): null|Time\Time
    {
        return $this->LegacyCreateTime;
    }

    public function setLegacyCreateTime(null|Time\Time $LegacyCreateTime): self
    {
        $this->LegacyCreateTime = $LegacyCreateTime;
        return $this;
    }

    public function getLegacyUpdateTime(): null|Time\Time
    {
        return $this->LegacyUpdateTime;
    }

    public function setLegacyUpdateTime(null|Time\Time $LegacyUpdateTime): self
    {
        $this->LegacyUpdateTime = $LegacyUpdateTime;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('sameness_group' === $k) {
                $n->SamenessGroup = $v;
            } elseif ('Action' === $k) {
                $n->Action = IntentionAction::from($v);
            } elseif ('Permissions' === $k) {
                $n->Permissions = [];
                foreach ($v as $vv) {
                    $n->Permissions[] = null === $vv ? null : IntentionPermission::jsonUnserialize($vv);
                }
            } elseif ('Type' === $k) {
                $n->Type = IntentionSourceType::from($v);
            } elseif ('legacy_id' === $k) {
                $n->LegacyID = $v;
            } elseif ('legacy_meta' === $k) {
                $n->LegacyMeta = $v;
            } elseif ('legacy_create_time' === $k) {
                $n->LegacyCreateTime = null === $v ? null : Time\Time::createFromFormat(DATE_RFC3339, $v);
            } elseif ('legacy_update_time' === $k) {
                $n->LegacyUpdateTime = null === $v ? null : Time\Time::createFromFormat(DATE_RFC3339, $v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Name = $this->Name;
        if ('' !== $this->Peer) {
            $out->Peer = $this->Peer;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->SamenessGroup) {
            $out->SamenessGroup = $this->SamenessGroup;
        }
        if (IntentionAction::UNDEFINED !== $this->Action) {
            $out->Action = $this->Action->value;
        }
        if ([] !== $this->Permissions) {
            $out->Permissions = $this->Permissions;
        }
        $out->Precedence = $this->Precedence;
        $out->Type = $this->Type;
        if ('' !== $this->Description) {
            $out->Description = $this->Description;
        }
        if ('' !== $this->LegacyID) {
            $out->LegacyID = $this->LegacyID;
        }
        if (null !== $this->LegacyMeta) {
            $out->LegacyMeta = $this->LegacyMeta;
        }
        if (null !== $this->LegacyCreateTime) {
            $out->LegacyCreateTime = $this->LegacyCreateTime->format(DATE_RFC3339);
        }
        if (null !== $this->LegacyUpdateTime) {
            $out->LegacyUpdateTime = $this->LegacyUpdateTime->format(DATE_RFC3339);
        }
        return $out;
    }
}
