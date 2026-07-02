<?php

namespace DCarbone\PHPConsulAPITests\Integration\ACL;

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

use DCarbone\PHPConsulAPI\ACL\ACLClient;
use DCarbone\PHPConsulAPI\ACL\ACLPolicy;
use DCarbone\PHPConsulAPI\ACL\ACLToken;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\Depends;

/**
 * @internal
 */
final class ACLClientTest extends AbstractIntegrationTestCase
{
    protected static bool $singlePerClass = false;

    public static function setUpBeforeClass(): void
    {
        ConsulManager::startSingleDevACL();
    }

    private static function managementClient(): ACLClient
    {
        return new ACLClient(ConsulManager::testConfig(ConsulManager::MANAGEMENT_TOKEN));
    }

    public function testTokenReadSelf(): void
    {
        $client = self::managementClient();

        [$token, $qm, $err] = $client->TokenReadSelf();
        self::assertNull($err, 'TokenReadSelf() returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(ACLToken::class, $token);
        self::assertNotEmpty($token->AccessorID);
        self::assertNotEmpty($token->SecretID);
    }

    /**
     * @return string The policy ID
     */
    public function testPolicyCreate(): string
    {
        $client = self::managementClient();

        $policy = new ACLPolicy(
            Name: 'php-consul-api-test-policy',
            Description: 'Test policy created by php-consul-api',
            Rules: 'key_prefix "test/" { policy = "read" }',
        );

        [$created, $wm, $err] = $client->PolicyCreate($policy);
        self::assertNull($err, 'PolicyCreate() returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertInstanceOf(ACLPolicy::class, $created);
        self::assertNotEmpty($created->ID);
        self::assertSame('php-consul-api-test-policy', $created->Name);

        return $created->ID;
    }

    /**
     * @return array{0: string, 1: string} [accessorID, policyID]
     */
    #[Depends('testPolicyCreate')]
    public function testTokenCreate(string $policyID): array
    {
        $client = self::managementClient();

        $token = new ACLToken(
            Description: 'php-consul-api-test-token',
            Policies: [new ACLTokenPolicyLink(ID: $policyID)],
        );

        [$created, $wm, $err] = $client->TokenCreate($token);
        self::assertNull($err, 'TokenCreate() returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertInstanceOf(ACLToken::class, $created);
        self::assertNotEmpty($created->AccessorID);
        self::assertNotEmpty($created->SecretID);
        self::assertSame('php-consul-api-test-token', $created->Description);

        return [$created->AccessorID, $policyID];
    }

    /**
     * @param array{0: string, 1: string} $ids
     * @return array{0: string, 1: string} [accessorID, policyID]
     */
    #[Depends('testTokenCreate')]
    public function testTokenRead(array $ids): array
    {
        [$accessorID] = $ids;

        $client = self::managementClient();

        [$token, $qm, $err] = $client->TokenRead($accessorID);
        self::assertNull($err, 'TokenRead() returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(ACLToken::class, $token);
        self::assertSame($accessorID, $token->AccessorID);
        self::assertSame('php-consul-api-test-token', $token->Description);

        return $ids;
    }

    /**
     * @param array{0: string, 1: string} $ids
     * @return array{0: string, 1: string} [accessorID, policyID]
     */
    #[Depends('testTokenRead')]
    public function testTokenList(array $ids): array
    {
        $client = self::managementClient();

        [$entries, $qm, $err] = $client->TokenList();
        self::assertNull($err, 'TokenList() returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($entries);
        // At minimum: the management token + the anonymous token + our created token
        self::assertGreaterThanOrEqual(2, count($entries));

        return $ids;
    }

    /**
     * @param array{0: string, 1: string} $ids
     */
    #[Depends('testTokenList')]
    public function testTokenDelete(array $ids): string
    {
        [$accessorID, $policyID] = $ids;

        $client = self::managementClient();

        [$wm, $err] = $client->TokenDelete($accessorID);
        self::assertNull($err, 'TokenDelete() returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        return $policyID;
    }

    #[Depends('testTokenDelete')]
    public function testPolicyDelete(string $policyID): void
    {
        $client = self::managementClient();

        [$wm, $err] = $client->PolicyDelete($policyID);
        self::assertNull($err, 'PolicyDelete() returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }
}
