<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AreaJoinResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AreaJoinResponseTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $resp = new AreaJoinResponse();
        self::assertSame('', $resp->getAddress());
        self::assertSame('', $resp->Address);
        self::assertFalse($resp->isJoined());
        self::assertFalse($resp->Joined);
        self::assertSame('', $resp->getError());
        self::assertSame('', $resp->Error);
    }

    public function testConstructorWithValues(): void
    {
        $resp = new AreaJoinResponse(
            Address: '10.0.0.1',
            Joined: true,
            Error: '',
        );
        self::assertSame('10.0.0.1', $resp->getAddress());
        self::assertSame('10.0.0.1', $resp->Address);
        self::assertTrue($resp->isJoined());
        self::assertTrue($resp->Joined);
    }

    public function testFluentSetters(): void
    {
        $resp = new AreaJoinResponse();
        $result = $resp
            ->setAddress('192.168.1.1')
            ->setJoined(true)
            ->setError('some error');

        self::assertSame($resp, $result);
        self::assertSame('192.168.1.1', $resp->getAddress());
        self::assertSame('192.168.1.1', $resp->Address);
        self::assertTrue($resp->isJoined());
        self::assertTrue($resp->Joined);
        self::assertSame('some error', $resp->getError());
        self::assertSame('some error', $resp->Error);
    }

    public function testJsonSerialize(): void
    {
        $resp = new AreaJoinResponse(
            Address: '10.0.0.5',
            Joined: true,
            Error: 'err',
        );
        $out = $resp->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('10.0.0.5', $out->Address);
        self::assertTrue($out->Joined);
        self::assertSame('err', $out->Error);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Address = '172.16.0.1';
        $decoded->Joined = false;
        $decoded->Error = 'connection refused';

        $resp = AreaJoinResponse::jsonUnserialize($decoded);

        self::assertSame('172.16.0.1', $resp->getAddress());
        self::assertFalse($resp->isJoined());
        self::assertSame('connection refused', $resp->getError());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new AreaJoinResponse(
            Address: '10.1.2.3',
            Joined: true,
            Error: '',
        );

        $restored = AreaJoinResponse::jsonUnserialize($original->jsonSerialize());

        self::assertSame($original->getAddress(), $restored->getAddress());
        self::assertSame($original->isJoined(), $restored->isJoined());
        self::assertSame($original->getError(), $restored->getError());
    }
}

