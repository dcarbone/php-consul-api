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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

class ACLToken extends AbstractModel
{
    use ACLTokenFields;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink> $Policies
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink> $Roles
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity> $ServiceIdentities
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity> $NodeIdentities
     * @param array<\DCarbone\PHPConsulAPI\ACL\ACLTemplatedPolicy> $TemplatePolicies
     */
    public function __construct(
        int $CreateIndex = 0,
        int $ModifyIndex = 0,
        string $AccessorID = '',
        string $SecretID = '',
        string $Description = '',
        iterable $Policies = [],
        iterable $Roles = [],
        iterable $ServiceIdentities = [],
        iterable $NodeIdentities = [],
        iterable $TemplatePolicies = [],
        bool $Local = false,
        string $AuthMethod = '',
        null|int|float|string|\DateInterval|Time\Duration $ExpirationTTL = null,
        null|Time\Time $ExpirationTime = null,
        null|Time\Time $CreateTime = null,
        string $Hash = '',
        string $Namespace = '',
        string $Rules = '',
        string $Partition = '',
        string $AuthMethodNamespace = '',
    ) {
        $this->CreateIndex = $CreateIndex;
        $this->ModifyIndex = $ModifyIndex;
        $this->AccessorID = $AccessorID;
        $this->SecretID = $SecretID;
        $this->Description = $Description;
        $this->setPolicies(...$Policies);
        $this->setRoles(...$Roles);
        $this->setServiceIdentities(...$ServiceIdentities);
        $this->setNodeIdentities(...$NodeIdentities);
        $this->setTemplatePolicies(...$TemplatePolicies);
        $this->Local = $Local;
        $this->AuthMethod = $AuthMethod;
        $this->setExpirationTTL($ExpirationTTL);
        $this->setExpirationTime($ExpirationTime);
        $this->CreateTime = $CreateTime ?? Time::New();
        $this->Hash = $Hash;
        $this->Namespace = $Namespace;
        $this->Rules = $Rules;
        $this->Partition = $Partition;
        $this->AuthMethodNamespace = $AuthMethodNamespace;
}

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if (!$n->_jsonUnserializeField($k, $v, $n)) {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $this->_jsonSerialize($out);
        return $out;
    }
}
