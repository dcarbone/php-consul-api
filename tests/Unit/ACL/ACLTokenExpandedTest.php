<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLRole;
use DCarbone\PHPConsulAPI\ACL\ACLTokenExpanded;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLTokenExpandedTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new ACLTokenExpanded();
        self::assertSame([], $t->getExpandedPolicies());
        self::assertSame([], $t->ExpandedPolicies);
        self::assertSame([], $t->getExpandedRoles());
        self::assertSame([], $t->ExpandedRoles);
        self::assertSame([], $t->getNamespaceDefaultPolicyIDs());
        self::assertSame([], $t->NamespaceDefaultPolicyIDs);
        self::assertSame([], $t->getNamespaceDefaultRoleIDs());
        self::assertSame([], $t->NamespaceDefaultRoleIDs);
        self::assertSame('', $t->getAgentACLDefaultPolicy());
        self::assertSame('', $t->AgentACLDefaultPolicy);
        self::assertSame('', $t->getAgentACLDownPolicy());
        self::assertSame('', $t->AgentACLDownPolicy);
        self::assertSame('', $t->getResolvedByAgent());
        self::assertSame('', $t->ResolvedByAgent);
        // inherited from ACLToken
        self::assertSame(0, $t->getCreateIndex());
        self::assertSame('', $t->getAccessorID());
        self::assertSame([], $t->getPolicies());
    }

    public function testConstructorWithParams(): void
    {
        $pol = new ACLPolicy(ID: 'ep1', Name: 'expanded-pol');
        $role = new ACLRole(ID: 'er1', Name: 'expanded-role');
        $tokenPol = new ACLTokenPolicyLink(ID: 'tp1', Name: 'token-pol');
        $tokenRole = new ACLTokenRoleLink(ID: 'tr1', Name: 'token-role');

        $t = new ACLTokenExpanded(
            ExpandedPolicies: [$pol],
            ExpandedRoles: [$role],
            NamespaceDefaultPolicyIDs: ['nsp1'],
            NamespaceDefaultRoleIDs: ['nsr1'],
            AgentACLDefaultPolicy: 'allow',
            AgentACLDownPolicy: 'deny',
            ResolvedByAgent: 'agent-1',
            CreateIndex: 1,
            ModifyIndex: 2,
            AccessorID: 'acc-1',
            SecretID: 'sec-1',
            Description: 'desc',
            Policies: [$tokenPol],
            Roles: [$tokenRole],
            Local: true,
        );

        self::assertCount(1, $t->getExpandedPolicies());
        self::assertSame($pol, $t->getExpandedPolicies()[0]);
        self::assertCount(1, $t->getExpandedRoles());
        self::assertSame($role, $t->getExpandedRoles()[0]);
        self::assertSame(['nsp1'], $t->getNamespaceDefaultPolicyIDs());
        self::assertSame(['nsr1'], $t->getNamespaceDefaultRoleIDs());
        self::assertSame('allow', $t->getAgentACLDefaultPolicy());
        self::assertSame('allow', $t->AgentACLDefaultPolicy);
        self::assertSame('deny', $t->getAgentACLDownPolicy());
        self::assertSame('agent-1', $t->getResolvedByAgent());
        self::assertSame(1, $t->getCreateIndex());
        self::assertSame('acc-1', $t->getAccessorID());
        self::assertCount(1, $t->getPolicies());
        self::assertTrue($t->isLocal());
    }

    public function testFluentSetters(): void
    {
        $t = new ACLTokenExpanded();
        $pol = new ACLPolicy(ID: 'ep1');
        $role = new ACLRole(ID: 'er1');

        $result = $t->setExpandedPolicies($pol)
            ->setExpandedRoles($role)
            ->setNamespaceDefaultPolicyIDs('nsp1', 'nsp2')
            ->setNamespaceDefaultRoleIDs('nsr1')
            ->setAgentACLDefaultPolicy('allow')
            ->setAgentACLDownPolicy('deny')
            ->setResolvedByAgent('agent-set');

        self::assertSame($t, $result);
        self::assertCount(1, $t->getExpandedPolicies());
        self::assertSame($pol, $t->ExpandedPolicies[0]);
        self::assertCount(1, $t->getExpandedRoles());
        self::assertSame($role, $t->ExpandedRoles[0]);
        self::assertSame(['nsp1', 'nsp2'], $t->getNamespaceDefaultPolicyIDs());
        self::assertSame(['nsp1', 'nsp2'], $t->NamespaceDefaultPolicyIDs);
        self::assertSame(['nsr1'], $t->getNamespaceDefaultRoleIDs());
        self::assertSame('allow', $t->getAgentACLDefaultPolicy());
        self::assertSame('deny', $t->getAgentACLDownPolicy());
        self::assertSame('agent-set', $t->getResolvedByAgent());
    }

    public function testJsonSerialize(): void
    {
        $pol = new ACLPolicy(ID: 'ep1', Name: 'pol');
        $t = new ACLTokenExpanded(
            ExpandedPolicies: [$pol],
            AgentACLDefaultPolicy: 'allow',
            AccessorID: 'acc',
        );
        $out = $t->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('acc', $out->AccessorID);
        self::assertSame('allow', $out->AgentACLDefaultPolicy);
        self::assertCount(1, $out->ExpandedPolicies);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->CreateIndex = 5;
        $d->ModifyIndex = 10;
        $d->AccessorID = 'acc-1';
        $d->SecretID = 'sec-1';
        $d->Description = 'desc';
        $d->Local = false;
        $d->Hash = 'h';
        $d->Policies = [];
        $d->Roles = [];
        $d->ServiceIdentities = [];
        $d->NodeIdentities = [];
        $d->TemplatePolicies = [];
        $d->AgentACLDefaultPolicy = 'allow';
        $d->AgentACLDownPolicy = 'deny';
        $d->ResolvedByAgent = 'agent-1';
        $d->NamespaceDefaultPolicyIDs = ['nsp1'];
        $d->NamespaceDefaultRoleIDs = ['nsr1'];

        $epObj = new \stdClass();
        $epObj->ID = 'ep1';
        $epObj->Name = 'expanded-pol';
        $epObj->Description = '';
        $epObj->Rules = '';
        $epObj->Datacenters = [];
        $epObj->Hash = '';
        $epObj->CreateIndex = 0;
        $epObj->ModifyIndex = 0;
        $d->ExpandedPolicies = [$epObj];

        $erObj = new \stdClass();
        $erObj->ID = 'er1';
        $erObj->Name = 'expanded-role';
        $erObj->Description = '';
        $erObj->Policies = [];
        $erObj->ServiceIdentities = [];
        $erObj->NodeIdentities = [];
        $erObj->TemplatedPolicies = [];
        $erObj->Hash = '';
        $erObj->CreateIndex = 0;
        $erObj->ModifyIndex = 0;
        $d->ExpandedRoles = [$erObj];

        $t = ACLTokenExpanded::jsonUnserialize($d);
        self::assertInstanceOf(ACLTokenExpanded::class, $t);
        self::assertSame('acc-1', $t->getAccessorID());
        self::assertSame('allow', $t->getAgentACLDefaultPolicy());
        self::assertSame('deny', $t->getAgentACLDownPolicy());
        self::assertSame('agent-1', $t->getResolvedByAgent());
        self::assertSame(['nsp1'], $t->getNamespaceDefaultPolicyIDs());
        self::assertSame(['nsr1'], $t->getNamespaceDefaultRoleIDs());
        self::assertCount(1, $t->getExpandedPolicies());
        self::assertInstanceOf(ACLPolicy::class, $t->getExpandedPolicies()[0]);
        self::assertSame('ep1', $t->getExpandedPolicies()[0]->getID());
        self::assertCount(1, $t->getExpandedRoles());
        self::assertInstanceOf(ACLRole::class, $t->getExpandedRoles()[0]);
        self::assertSame('er1', $t->getExpandedRoles()[0]->getID());
    }
}

