<?php namespace DCarbone\PHPConsulAPITests\Usage\ACL;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class ACLClientTest
 * @package DCarbone\PHPConsulAPITests\Usage\ACL
 */
class ACLClientTest extends AbstractUsageTests {

    /** @var string */
    protected $bootstrappedACL;

    /**
     * @return string
     */
    public function testCanBootstrapACL() {
        ConsulManager::startSingleDev('-bind="127.0.0.1"');

        $client = new ACLClient(new Config());

        list($aclID, $wm, $err) = $client->Bootstrap();
        $this->assertNull($err, 'ACL::bootstrap() returned error: '.$err);
        $this->assertInstanceOf(WriteMeta::class, $wm);
        $this->assertIsString($aclID);

        return $aclID;
    }

    /**
     * @depends testCanBootstrapACL
     * @param string $aclID
     */
    public function testCanGetBootstrappedACL(string $aclID) {
        $client = new ACLClient(new Config());

        list($acls, $qm, $err) = $client->Info($aclID);
        $this->assertNull($err, 'ACL::info() return error: '.$err);
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertIsArray($acls);
        var_dump($acls);
    }
}