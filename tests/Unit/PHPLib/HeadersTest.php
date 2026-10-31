<?php

namespace DCarbone\PHPConsulAPITests\Unit\PHPLib;

use DCarbone\PHPConsulAPI\PHPLib\Headers;
use PHPUnit\Framework\TestCase;

final class HeadersTest extends TestCase
{
    public function testHeaderKeysAreComparedCaseInsensitively(): void
    {
        $headers = new Headers();
        $headers->set('Content-Type', 'application/json');

        self::assertSame('application/json', $headers->get('content-type'));
        self::assertSame(['application/json'], $headers->getAll('CONTENT-TYPE'));
        self::assertTrue(isset($headers['content-type']));
        self::assertSame(['application/json'], $headers['CONTENT-TYPE']);
        self::assertSame(['Content-Type' => ['application/json']], $headers->toPsr7Array());
    }

    public function testSetAddAndDeleteFindHeaderKeysRegardlessOfCase(): void
    {
        $headers = new Headers();
        $headers->set('Content-Type', 'application/json');
        $headers->set('content-type', 'application/custom');
        $headers->add('CONTENT-TYPE', 'application/other');

        self::assertSame(['application/custom', 'application/other'], $headers->getAll('Content-Type'));
        self::assertSame(['Content-Type' => ['application/custom', 'application/other']], $headers->toPsr7Array());

        $headers->delete('CONTENT-type');

        self::assertSame('', $headers->get('Content-Type'));
        self::assertSame([], $headers->toPsr7Array());
    }

    public function testNormalizedKeyCacheRemainsValidWhenEntriesAreRemoved(): void
    {
        $headers = new Headers();
        $headers->set('Content-Type', 'application/json');
        $headers->set('X-Request-Id', 'abc');
        $headers->delete('content-type');
        $headers->set('content-type', 'application/custom');

        self::assertSame('abc', $headers->get('x-request-id'));
        self::assertSame('application/custom', $headers->get('CONTENT-TYPE'));
        self::assertSame(
            [
                'X-Request-Id' => ['abc'],
                'content-type' => ['application/custom'],
            ],
            $headers->toPsr7Array()
        );
    }

    public function testArrayAccessRemainsCaseInsensitive(): void
    {
        $headers = new Headers();
        $headers['Content-Type'] = ['application/json'];

        self::assertSame(['application/json'], $headers['content-type']);
        self::assertTrue(isset($headers['CONTENT-TYPE']));

        unset($headers['content-TYPE']);

        self::assertFalse(isset($headers['Content-Type']));
        self::assertSame([], $headers->toPsr7Array());
    }
}
