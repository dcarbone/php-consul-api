<?php

namespace DCarbone\PHPConsulAPITests\Usage;

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

use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\QueryOptions;
use DCarbone\PHPConsulAPI\Request;
use DCarbone\PHPConsulAPI\WriteOptions;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class RequestUsageTest
 *
 * @internal
 */
final class RequestUsageTest extends TestCase
{
    public function testCanConstructWithoutBody(): void
    {
        $r = new Request('', '', ConsulManager::testConfig(), null);
        self::assertInstanceOf(Request::class, $r);
    }

    /**
     * @depends testCanConstructWithoutBody
     */
    public function testCanConstructWithBody(): void
    {
        $r = new Request('', '', ConsulManager::testConfig(), new KVPair());
        self::assertInstanceOf(Request::class, $r);
    }

    /**
     * @depends testCanConstructWithoutBody
     */
    public function testCanCreatePsr7Uri(): void
    {
        $r   = new Request('GET', 'kv', ConsulManager::testConfig(), null);
        $uri = $r->getUri();
        self::assertInstanceOf(UriInterface::class, $uri);
        self::assertSame('/kv', $uri->getPath());
    }

    /**
     * @depends testCanCreatePsr7Uri
     */
    public function testCanCreatePsr7Request(): void
    {
        $r = new Request('GET', '/kv', ConsulManager::testConfig(), null);

        $psr7Request = $r->toPsrRequest();
        self::assertInstanceOf(RequestInterface::class, $psr7Request);

        self::assertSame('GET', $psr7Request->getMethod());
    }

    /**
     * @depends testCanCreatePsr7Request
     */
    public function testCanSetQueryOptions(): void
    {
        $r = new Request('GET', 'kv', ConsulManager::testConfig(), null);
        $r->applyOptions(new QueryOptions(['Pretty' => true]));

        $psr7 = $r->toPsrRequest();
        $uri  = $psr7->getUri();
        self::assertSame('pretty', $uri->getQuery());
    }

    /**
     * @depends testCanCreatePsr7Request
     */
    public function testCanSetWriteOptions(): void
    {
        $r = new Request('GET', 'kv', ConsulManager::testConfig(), null);
        $r->applyOptions(new WriteOptions(['Datacenter' => 'dc1']));

        $psr7 = $r->toPsrRequest();
        $uri  = $psr7->getUri();
        self::assertSame('dc=dc1', $uri->getQuery());
    }
}
