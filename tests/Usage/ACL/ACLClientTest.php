<?php

namespace DCarbone\PHPConsulAPITests\Usage\ACL;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;


final class ACLClientTest extends AbstractUsageTests
{
    /** @var string */
    protected string $bootstrappedACL;

    /**
     * @return string
     */
    public function testCanBootstrapACL()
    {
        ConsulManager::startSingleDev();

        $client = new ACLClient(ConsulManager::testConfig());

        [$aclID, $wm, $err] = $client->Bootstrap();
        self::assertNull($err, 'ACL::bootstrap() returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertIsString($aclID);

        return $aclID;
    }

    /**
     * @depends testCanBootstrapACL
     * @param string $aclID
     */
    public function testCanGetBootstrappedACL(string $aclID): void
    {
        $client = new ACLClient(ConsulManager::testConfig());

        [$acls, $qm, $err] = $client->Info($aclID);
        self::assertNull($err, 'ACL::Info() return error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($acls);
        var_dump($acls);
    }
}
