<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\HttpAuth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HttpAuthTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $a = new HttpAuth();
        self::assertSame('', $a->getUsername());
        self::assertSame('', $a->username);
        self::assertSame('', $a->getPassword());
        self::assertSame('', $a->password);
    }

    public function testConstructorWithParams(): void
    {
        $a = new HttpAuth(username: 'admin', password: 'secret');
        self::assertSame('admin', $a->getUsername());
        self::assertSame('admin', $a->username);
        self::assertSame('secret', $a->getPassword());
        self::assertSame('secret', $a->password);
    }

    public function testFluentSetters(): void
    {
        $a = new HttpAuth();
        $result = $a->setUsername('user1')->setPassword('pass1');
        self::assertSame($a, $result);
        self::assertSame('user1', $a->getUsername());
        self::assertSame('user1', $a->username);
        self::assertSame('pass1', $a->getPassword());
        self::assertSame('pass1', $a->password);
    }

    public function testCompileAuthStringWithPassword(): void
    {
        $a = new HttpAuth(username: 'admin', password: 'secret');
        self::assertSame('admin:secret', $a->compileAuthString());
    }

    public function testCompileAuthStringWithoutPassword(): void
    {
        $a = new HttpAuth(username: 'admin');
        self::assertSame('admin', $a->compileAuthString());
    }

    public function testToStringWithPassword(): void
    {
        $a = new HttpAuth(username: 'u', password: 'p');
        self::assertSame('u:p', (string)$a);
    }

    public function testToStringWithoutPassword(): void
    {
        $a = new HttpAuth(username: 'u');
        self::assertSame('u', (string)$a);
    }

    public function testJsonSerializeWithPassword(): void
    {
        $a = new HttpAuth(username: 'admin', password: 'secret');
        $out = $a->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('admin', $out->username);
        self::assertSame('secret', $out->password);
    }

    public function testJsonSerializeWithoutPassword(): void
    {
        $a = new HttpAuth(username: 'admin');
        $out = $a->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('admin', $out->username);
        self::assertObjectNotHasProperty('password', $out);
    }

    public function testDebugInfoHidesPassword(): void
    {
        $a = new HttpAuth(username: 'admin', password: 'secret');
        $debug = $a->__debugInfo();
        self::assertArrayHasKey('username', $debug);
        self::assertSame('admin', $debug['username']);
        self::assertArrayNotHasKey('password', $debug);
    }
}

