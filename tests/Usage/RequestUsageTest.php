<?php namespace DCarbone\PHPConsulAPITests\Usage;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class RequestUsageTest
 * @package DCarbone\PHPConsulAPITests\Usage
 */
class RequestUsageTest extends TestCase {
    public function testCanConstructWithoutBody() {
        $r = new Request('', '', new Config());
        $this->assertInstanceOf(Request::class, $r);
    }

    /**
     * @depends testCanConstructWithoutBody
     */
    public function testCanConstructWithBody() {
        $r = new Request('', '', new Config(), new KVPair());
        $this->assertInstanceOf(Request::class, $r);
    }

    /**
     * @depends testCanConstructWithoutBody
     */
    public function testCanCreatePsr7Uri() {
        $r = new Request('GET', 'kv', new Config());
        $uri = $r->getUri();
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertEquals('/kv', $uri->getPath());
    }

    /**
     * @depends testCanCreatePsr7Uri
     */
    public function testCanCreatePsr7Request() {
        $r = new Request('GET', '/kv', new Config());

        $psr7Request = $r->toPsrRequest();
        $this->assertInstanceOf(RequestInterface::class, $psr7Request);

        $this->assertEquals('GET', $psr7Request->getMethod());
    }

    /**
     * @depends testCanCreatePsr7Request
     */
    public function testCanSetQueryOptions() {
        $r = new Request('GET', 'kv', new Config());
        $r->setQueryOptions(new QueryOptions(['Pretty' => true]));

        $psr7 = $r->toPsrRequest();
        $uri = $psr7->getUri();
        $this->assertEquals('pretty', $uri->getQuery());
    }

    /**
     * @depends testCanCreatePsr7Request
     */
    public function testCanSetWriteOptions() {
        $r = new Request('GET', 'kv', new Config());
        $r->setWriteOptions(new WriteOptions(['Datacenter' => 'dc1']));

        $psr7 = $r->toPsrRequest();
        $uri = $psr7->getUri();
        $this->assertEquals('dc=dc1', $uri->getQuery());
    }
}