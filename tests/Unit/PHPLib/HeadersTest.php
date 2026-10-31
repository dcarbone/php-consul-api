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
}
