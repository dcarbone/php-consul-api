<?php /** @noinspection PhpMissingFieldTypeInspection */
namespace DCarbone\PHPConsulAPITests\Usage\KV;

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairs;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class KVCRUDTest
 *
 * @internal
 */
final class KVCRUDTest extends AbstractUsageTests
{
    public const KVKey1   = 'testkey1';
    public const KVValue1 = 'testvalue1';

    public const KVKey2   = 'testkey2';
    public const KVValue2 = 'testvalue2';

    public const KVKey3   = 'testkey3';
    public const KVValue3 = 'testvalue3';

    public const KVPrefix = 'tests';
    /** @var bool */
    protected $singlePerTest = true;

    public function testCanPutKey(): void
    {
        $client = new KVClient(new Config());

        [$wm, $err] = $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        static::assertNull($err, \sprintf('Unable to set kvp: %s', (string)$err));
        static::assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetKey(): void
    {
        $client = new KVClient(new Config());
        $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));

        [$kv, $qm, $err] = $client->Get(self::KVKey1);
        static::assertNull($err, \sprintf('KV::get returned error: %s', (string)$err));
        static::assertInstanceOf(QueryMeta::class, $qm);
        static::assertInstanceOf(KVPair::class, $kv);
        static::assertEquals(self::KVKey1, $kv->Key);
        static::assertEquals(self::KVValue1, $kv->Value);
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanDeleteKey(): void
    {
        $client = new KVClient(new Config());
        $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));

        [$wm, $err] = $client->Delete(self::KVKey1);
        static::assertNull($err, \sprintf('KV::delete returned error: %s', $err));
        static::assertInstanceOf(
            WriteMeta::class,
            $wm,
            \sprintf(
                'expected "%s", saw "%s"',
                WriteMeta::class,
                \is_object($wm) ? \get_class($wm) : \gettype($wm)
            )
        );
    }

    public function testListReturnsErrorWithInvalidPrefix(): void
    {
        $client        = new KVClient(new Config());
        [$_, $_, $err] = $client->List(12345);
        static::assertInstanceOf(
            Error::class,
            $err,
            \sprintf(
                'Expected $err to be instanceof "%s", saw "%s"',
                Error::class,
                \is_object($err) ? \get_class($err) : \gettype($err)
            )
        );
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetNoPrefixList(): void
    {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new KVClient(new Config());
        $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $client->Put(new KVPair(['Key' => self::KVKey2, 'Value' => self::KVValue2]));
        $client->Put(new KVPair(['Key' => self::KVKey3, 'Value' => self::KVValue3]));

        /** @noinspection PhpUnhandledExceptionInspection */
        [$list, $qm, $err] = $client->List();
        static::assertNull($err, \sprintf('KV::valueList returned error: %s', $err));

        try {
            static::assertInstanceOf(KVPairs::class, $list);
            static::assertInstanceOf(QueryMeta::class, $qm);
            static::assertCount(3, $list);

            $key1found = false;
            $key2found = false;
            $key3found = false;

            foreach ($list as $kv) {
                if (self::KVValue1 === $kv->Value) {
                    $key1found = true;
                } elseif (self::KVValue2 === $kv->Value) {
                    $key2found = true;
                } elseif (self::KVValue3 === $kv->Value) {
                    $key3found = true;
                }
            }

            static::assertTrue($key1found, 'Key1 not found in list!');
            static::assertTrue($key2found, 'Key2 not found in list!');
            static::assertTrue($key3found, 'Key3 not found in list!');
        } catch (AssertionFailedError $e) {
            echo "\nno prefix \$list value:\n";
            \var_dump($list);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetPrefixList(): void
    {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new KVClient(new Config());
        $client->Put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey1, 'Value' => self::KVValue1]));
        $client->Put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey2, 'Value' => self::KVValue2]));
        $client->Put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey3, 'Value' => self::KVValue3]));

        [$list, $qm, $err] = $client->List(self::KVPrefix);
        static::assertNull($err, \sprintf('KV::valueList returned error: %s', $err));
        static::assertInstanceOf(QueryMeta::class, $qm);

        try {
            static::assertInstanceOf(KVPairs::class, $list);
            static::assertCount(3, $list);
            static::assertContainsOnlyInstancesOf(KVPair::class, $list);

            $key1found = false;
            $key2found = false;
            $key3found = false;

            foreach ($list as $kv) {
                if (self::KVValue1 === $kv->Value) {
                    $key1found = true;
                } elseif (self::KVValue2 === $kv->Value) {
                    $key2found = true;
                } elseif (self::KVValue3 === $kv->Value) {
                    $key3found = true;
                }
            }

            static::assertTrue($key1found, 'Key1 not found in list!');
            static::assertTrue($key2found, 'Key2 not found in list!');
            static::assertTrue($key3found, 'Key3 not found in list!');
        } catch (AssertionFailedError $e) {
            echo "\nprefix \$list value:\n";
            \var_dump($list);
            echo "\n";

            throw $e;
        }
    }

    public function testKeysReturnsErrorWithInvalidPrefix(): void
    {
        $client        = new KVClient(new Config());
        [$_, $_, $err] = $client->Keys(12345);
        static::assertInstanceOf(
            Error::class,
            $err,
            \sprintf(
                'Expected $err to be "%s", saw "%s"',
                Error::class,
                \is_object($err) ? \get_class($err) : \gettype($err)
            )
        );
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetNoPrefixKeys(): void
    {
        /** @var string[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new KVClient(new Config());
        $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $client->Put(new KVPair(['Key' => self::KVKey2, 'Value' => self::KVValue2]));
        $client->Put(new KVPair(['Key' => self::KVKey3, 'Value' => self::KVValue3]));

        [$list, $qm, $err] = $client->Keys();
        static::assertNull($err, \sprintf('KV::keys returned error: %s', $err));
        static::assertInstanceOf(QueryMeta::class, $qm);

        try {
            static::assertIsArray($list);
            static::assertCount(3, $list);
            static::assertContainsOnly('string', $list, true);

            $key1found = false;
            $key2found = false;
            $key3found = false;

            foreach ($list as $key) {
                if (self::KVKey1 === $key) {
                    $key1found = true;
                } elseif (self::KVKey2 === $key) {
                    $key2found = true;
                } elseif (self::KVKey3 === $key) {
                    $key3found = true;
                }
            }

            static::assertTrue($key1found, 'Key1 not found in list!');
            static::assertTrue($key2found, 'Key2 not found in list!');
            static::assertTrue($key3found, 'Key3 not found in list!');
        } catch (AssertionFailedError $e) {
            echo "\nprefix \$list value:\n";
            \var_dump($list);
            echo "\n";

            throw $e;
        }
    }
}
