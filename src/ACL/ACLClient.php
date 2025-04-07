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

use DCarbone\PHPConsulAPI\AbstractClient;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\ValuedWriteStringResponse;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPI\WriteResponse;

class ACLClient extends AbstractClient
{
    public function Bootstrap(): ValuedWriteStringResponse
    {
        return $this->_executePutValuedStr('v1/acl/bootstrap', null, null);
    }

    public function Create(ACLEntry $acl, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_executePutValuedStr('v1/acl/create', $acl, $opts);
    }

    public function Update(ACLEntry $acl, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut('v1/acl/update', $acl, $opts);
    }

    public function Destroy(string $id, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePut(sprintf('v1/acl/destroy/%s', $id), null, $opts);
    }

    public function Clone(string $id, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        return $this->_executePutValuedStr(sprintf('v1/acl/clone/%s', $id), null, $opts);
    }

    public function Info(string $id, ?QueryOptions $opts = null): ACLEntriesResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('v1/acl/info/%s', $id), $opts));
        $ret  = new ACLEntriesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function List(?QueryOptions $opts = null): ACLEntriesResponse
    {
        $resp = $this->_requireOK($this->_doGet('v1/acl/list', $opts));
        $ret  = new ACLEntriesResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Replication(?QueryOptions $opts = null): ACLReplicationStatusResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/replication', $opts));
        $ret  = new ACLReplicationStatusResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenCreate(ACLToken $token, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $resp = $this->_requireOK($this->_doPut('/v1/acl/token', $token, $opts));
        $ret  = new ACLTokenWriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenUpdate(ACLToken $token, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $ret = new ACLTokenWriteResponse();
        if ('' === $token->AccessorID) {
            $ret->Err = new Error('must specify AccessorID for Token Updating');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(sprintf('/v1/acl/token/%s', $token->AccessorID), $token, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenClone(string $tokenID, string $description, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $ret = new ACLTokenWriteResponse();
        if ('' === $tokenID) {
            $ret->Err = new Error('must specify tokenID for Token Cloning');
            return $ret;
        }
        $resp = $this->_requireOK(
            $this->_doPut(sprintf('/v1/acl/token/%s/clone', $tokenID), ['description' => $description], $opts)
        );
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenDelete(string $tokenID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('/v1/acl/token/%s', $tokenID), $opts);
    }

    public function TokenRead(string $tokenID, ?QueryOptions $opts = null): ACLTokenQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('/v1/acl/token/%s', $tokenID), $opts));
        $ret  = new ACLTokenQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenReadSelf(?QueryOptions $opts = null): ACLTokenQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/token/self', $opts));
        $ret  = new ACLTokenQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function TokenList(?QueryOptions $opts = null): ACLTokenListEntryQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/tokens', $opts));
        $ret  = new ACLTokenListEntryQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function PolicyCreate(ACLPolicy $policy, ?WriteOptions $opts = null): ACLPolicyWriteResponse
    {
        $ret = new ACLPolicyWriteResponse();
        if ('' !== $policy->ID) {
            $ret->Err = new Error('cannot specify an id in Policy Create');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut('/v1/acl/policy', $policy, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function PolicyUpdate(ACLPolicy $policy, ?WriteOptions $opts = null): ACLPolicyWriteResponse
    {
        $ret = new ACLPolicyWriteResponse();
        if ('' === $policy->ID) {
            $ret->Err = new Error('must specify an ID in Policy Update');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(sprintf('/v1/acl/policy/%s', $policy->ID), $policy, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function PolicyDelete(string $policyID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('/v1/acl/policy/%s', $policyID), $opts);
    }

    public function PolicyRead(string $policyID, ?QueryOptions $opts = null): ACLPolicyQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('/v1/acl/policy/%s', $policyID), $opts));
        $ret  = new ACLPolicyQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function PolicyReadByName(string $policyName, ?QueryOptions $opts = null): ACLPolicyQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('/v1/acl/policy/name/%s', $policyName), $opts));
        $ret  = new ACLPolicyQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function PolicyList(?QueryOptions $opts = null): ACLPolicyListEntryQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/policies', $opts));
        $ret  = new ACLPolicyListEntryQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RoleCreate(ACLRole $role, ?WriteOptions $opts = null): ACLRoleWriteResponse
    {
        $ret = new ACLRoleWriteResponse();
        if ('' !== $role->ID) {
            $ret->Err = new Error('cannot specify an id in Role Create');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut('/v1/acl/role', $role, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RoleUpdate(ACLRole $role, ?WriteOptions $opts = null): ACLRoleWriteResponse
    {
        $ret = new ACLRoleWriteResponse();
        if ('' === $role->ID) {
            $ret->Err = new Error('must specify an ID in Role Update');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(sprintf('/v1/acl/role/%s', $role->ID), $role, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RoleDelete(string $roleID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('/v1/acl/role/%s', $roleID), $opts);
    }

    public function RoleRead(string $roleID, ?QueryOptions $opts = null): ACLRoleQueryResponse
    {
        $resp = $this->_requireNotFoundOrOK($this->_doGet(sprintf('/v1/acl/role/%s', $roleID), $opts));
        $ret  = new ACLRoleQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RoleReadByName(string $roleName, ?QueryOptions $opts = null): ACLRoleQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet(sprintf('/v1/acl/role/name/%s', $roleName), $opts));
        $ret  = new ACLRoleQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function RoleList(?QueryOptions $opts = null): ACLRolesQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/roles', $opts));
        $ret  = new ACLRolesQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AuthMethodCreate(ACLAuthMethod $authMethod, ?WriteOptions $opts = null): ACLAuthMethodWriteResponse
    {
        $ret = new ACLAuthMethodWriteResponse();
        if ('' !== $authMethod->Name) {
            $ret->Err = new Error('cannot specify an Name in AuthMethod Create');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut('/v1/acl/auth-method', $authMethod, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AuthMethodUpdate(ACLAuthMethod $authMethod, ?WriteOptions $opts = null): ACLAuthMethodWriteResponse
    {
        $ret = new ACLAuthMethodWriteResponse();
        if ('' === $authMethod->ID) {
            $ret->Err = new Error('must specify an ID in AuthMethod Update');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(sprintf('/v1/acl/auth-method/%s', $authMethod->ID), $authMethod, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AuthMethodDelete(string $authMethodID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('/v1/acl/authMethod/%s', $authMethodID), $opts);
    }

    public function AuthMethodRead(string $authMethodID, ?QueryOptions $opts = null): ACLAuthMethodQueryResponse
    {
        $resp = $this->_requireNotFoundOrOK($this->_doGet(sprintf('/v1/acl/authMethod/%s', $authMethodID), $opts));
        $ret  = new ACLAuthMethodQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function AuthMethodList(?QueryOptions $opts = null): ACLAuthMethodListEntryQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/auth-methods', $opts));
        $ret  = new ACLAuthMethodListEntryQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function BindingRuleCreate(
        ACLBindingRule $bindingRule,
        ?WriteOptions $opts = null
    ): ACLBindingRuleWriteResponse {
        $ret = new ACLBindingRuleWriteResponse();
        if ('' !== $bindingRule->ID) {
            $ret->Err = new Error('cannot specify an id in BindingRule Create');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut('/v1/acl/binding-rule', $bindingRule, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function BindingRuleUpdate(
        ACLBindingRule $bindingRule,
        ?WriteOptions $opts = null
    ): ACLBindingRuleWriteResponse {
        $ret = new ACLBindingRuleWriteResponse();
        if ('' === $bindingRule->ID) {
            $ret->Err = new Error('must specify an ID in BindingRule Update');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPut(sprintf('/v1/acl/binding-rule/%s', $bindingRule->ID), $bindingRule, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function BindingRuleDelete(string $bindingRuleID, ?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executeDelete(sprintf('/v1/acl/binding-rule/%s', $bindingRuleID), $opts);
    }

    public function BindingRuleRead(string $bindingRuleID, ?QueryOptions $opts = null): ACLBindingRuleQueryResponse
    {
        $resp = $this->_requireNotFoundOrOK($this->_doGet(sprintf('/v1/acl/binding-rule/%s', $bindingRuleID), $opts));
        $ret  = new ACLBindingRuleQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function BindingRuleList(?QueryOptions $opts = null): ACLBindingRulesQueryResponse
    {
        $resp = $this->_requireOK($this->_doGet('/v1/acl/binding-rules', $opts));
        $ret  = new ACLBindingRulesQueryResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Login(ACLLoginParams $login, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $resp = $this->_requireOK($this->_doPost('/v1/acl/login', $login, $opts));
        $ret  = new ACLTokenWriteResponse();
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function Logout(?WriteOptions $opts = null): WriteResponse
    {
        return $this->_executePost('/v1/acl/logout', null, $opts);
    }

    public function OIDCAuthURL(ACLOIDCAuthURLParams $auth, ?WriteOptions $opts = null): ValuedWriteStringResponse
    {
        $ret = new ValuedWriteStringResponse();
        if ('' === $auth->AuthMethod) {
            $ret->Err = new Error('must specify an auth method name');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPost('/v1/acl/oidc/auth-url', $auth, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }

    public function OIDCCallback(ACLOIDCCallbackParams $auth, ?WriteOptions $opts = null): ACLTokenWriteResponse
    {
        $ret = new ACLTokenWriteResponse();
        if ('' === $auth->AuthMethod) {
            $ret->Err = new Error('must specify an auth method name');
            return $ret;
        }
        $resp = $this->_requireOK($this->_doPost('/v1/acl/oidc/callback', $auth, $opts));
        $this->_unmarshalResponse($resp, $ret);
        return $ret;
    }
}
