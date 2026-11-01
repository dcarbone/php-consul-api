<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\Operator\ServerHealth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServerHealthTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ServerHealth();
        self::assertSame('', $s->getID());
        self::assertSame('', $s->ID);
        self::assertSame('', $s->getName());
        self::assertSame('', $s->Name);
        self::assertSame('', $s->getAddress());
        self::assertSame('', $s->Address);
        self::assertSame('', $s->getSerfStatus());
        self::assertSame('', $s->SerfStatus);
        self::assertSame('', $s->getVersion());
        self::assertSame('', $s->Version);
        self::assertFalse($s->isLeader());
        self::assertFalse($s->Leader);
        self::assertNull($s->getLastContact());
        self::assertNull($s->LastContact);
        self::assertTrue($s->getLastTerm()->isZero());
        self::assertTrue($s->LastTerm->isZero());
        self::assertTrue($s->getLastIndex()->isZero());
        self::assertTrue($s->LastIndex->isZero());
        self::assertFalse($s->isHealthy());
        self::assertFalse($s->Healthy);
        self::assertFalse($s->isVoter());
        self::assertFalse($s->Voter);
    }

    public function testConstructorWithValues(): void
    {
        $s = new ServerHealth(
            ID: 'srv-1',
            Name: 'node-1',
            Address: '10.0.0.1:8300',
            SerfStatus: 'alive',
            Version: '1.22.0',
            Leader: true,
            LastContact: '15ms',
            LastTerm: 3,
            LastIndex: 100,
            Healthy: true,
            Voter: true,
        );
        self::assertSame('srv-1', $s->getID());
        self::assertSame('srv-1', $s->ID);
        self::assertSame('node-1', $s->getName());
        self::assertSame('node-1', $s->Name);
        self::assertTrue($s->isLeader());
        self::assertTrue($s->Leader);
        self::assertNotNull($s->getLastContact());
        self::assertNotNull($s->LastContact);
        self::assertSame('3', (string)$s->getLastTerm());
        self::assertSame('3', (string)$s->LastTerm);
        self::assertSame('100', (string)$s->getLastIndex());
        self::assertSame('100', (string)$s->LastIndex);
        self::assertTrue($s->isHealthy());
        self::assertTrue($s->Healthy);
        self::assertTrue($s->isVoter());
        self::assertTrue($s->Voter);
    }

    public function testFluentSetters(): void
    {
        $s = new ServerHealth();
        $result = $s
            ->setID('i')
            ->setName('n')
            ->setAddress('a')
            ->setSerfStatus('alive')
            ->setVersion('v')
            ->setLeader(true)
            ->setLastContact('10ms')
            ->setLastTerm(1)
            ->setLastIndex(50)
            ->setHealthy(true)
            ->setVoter(true);
        self::assertSame($s, $result);
        self::assertSame('i', $s->ID);
        self::assertSame('n', $s->Name);
        self::assertSame('a', $s->Address);
        self::assertSame('alive', $s->SerfStatus);
        self::assertSame('v', $s->Version);
        self::assertTrue($s->Leader);
        self::assertSame('1', (string)$s->LastTerm);
        self::assertSame('50', (string)$s->LastIndex);
        self::assertTrue($s->Healthy);
        self::assertTrue($s->Voter);
    }

    public function testNullLastContact(): void
    {
        $s = new ServerHealth(LastContact: '5s');
        self::assertNotNull($s->getLastContact());
        $s->setLastContact(null);
        self::assertNull($s->getLastContact());
    }

    public function testJsonSerialize(): void
    {
        $s = new ServerHealth(ID: 'x', Name: 'y', Healthy: true);
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertTrue($out->Healthy);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'sh1';
        $decoded->Name = 'node';
        $decoded->Address = '10.0.0.1:8300';
        $decoded->SerfStatus = 'alive';
        $decoded->Version = '1.22.0';
        $decoded->Leader = true;
        $decoded->LastContact = '15ms';
        $decoded->LastTerm = 3;
        $decoded->LastIndex = 100;
        $decoded->Healthy = true;
        $decoded->Voter = true;
        $decoded->StableSince = '2024-01-01T00:00:00.000000Z';

        $s = ServerHealth::jsonUnserialize($decoded);
        self::assertSame('sh1', $s->getID());
        self::assertTrue($s->isLeader());
        self::assertTrue($s->isHealthy());
        self::assertSame('3', (string)$s->LastTerm);
        self::assertSame('100', (string)$s->LastIndex);
    }

    public function testUint64FieldsAcceptIntStringAndBigInteger(): void
    {
        $s = new ServerHealth(LastTerm: '18446744073709551615', LastIndex: BigInteger::of(7));
        self::assertSame('18446744073709551615', (string)$s->LastTerm);
        self::assertSame('7', (string)$s->getLastIndex());

        $s->setLastTerm(BigInteger::of(2))->setLastIndex('9223372036854775808');
        self::assertTrue($s->getLastTerm()->isEqualTo(2));
        self::assertSame('9223372036854775808', (string)$s->LastIndex);

        $s->setLastIndex(8);
        self::assertSame('8', (string)$s->LastIndex);

        $out = $s->jsonSerialize();
        self::assertSame($s->LastTerm, $out->LastTerm);
        self::assertSame($s->LastIndex, $out->LastIndex);
    }

    public function testJsonUnserializeLargeValues(): void
    {
        $s = ServerHealth::jsonUnserialize(json_decode('{"ID":"a","LastTerm":18446744073709551615,"LastIndex":"18446744073709551614"}', flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR));
        self::assertSame('18446744073709551615', (string)$s->LastTerm);
        self::assertSame('18446744073709551614', (string)$s->LastIndex);
    }
}

