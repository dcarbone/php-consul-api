<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\ACL\ACLPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLPolicyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ACLPolicy();
        self::assertSame('', $p->getID());
        self::assertSame('', $p->getName());
        self::assertSame('', $p->getDescription());
        self::assertSame('', $p->getRules());
        self::assertSame([], $p->getDatacenters());
        self::assertSame('', $p->getHash());
        self::assertSame('0', (string)$p->getCreateIndex());
        self::assertSame('0', (string)$p->getModifyIndex());
        self::assertSame('', $p->getNamespace());
        self::assertSame('', $p->getPartition());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ACLPolicy(
            ID: 'pol-1',
            Name: 'my-policy',
            Description: 'desc',
            Rules: 'key "" { policy = "read" }',
            Datacenters: ['dc1', 'dc2'],
            Hash: 'abc',
            CreateIndex: 1,
            ModifyIndex: 2,
            Namespace: 'ns',
            Partition: 'pt',
        );
        self::assertSame('pol-1', $p->getID());
        self::assertSame('my-policy', $p->getName());
        self::assertSame('desc', $p->getDescription());
        self::assertSame('key "" { policy = "read" }', $p->getRules());
        self::assertSame(['dc1', 'dc2'], $p->getDatacenters());
        self::assertSame('abc', $p->getHash());
        self::assertSame('1', (string)$p->getCreateIndex());
        self::assertSame('2', (string)$p->getModifyIndex());
        self::assertSame('ns', $p->getNamespace());
        self::assertSame('pt', $p->getPartition());
    }

    public function testFluentSetters(): void
    {
        $p = new ACLPolicy();
        $result = $p->setID('a')->setName('b')->setDescription('c')->setRules('r')
            ->setDatacenters('dc1')->setHash('h')->setCreateIndex(1)->setModifyIndex(2)
            ->setNamespace('ns')->setPartition('pt');
        self::assertSame($p, $result);
        self::assertSame('a', $p->getID());
        self::assertSame(['dc1'], $p->getDatacenters());
    }

    public function testJsonSerializeOmitsEmptyNamespaceAndPartition(): void
    {
        $p = new ACLPolicy(ID: 'x', Name: 'y');
        $out = $p->jsonSerialize();
        self::assertObjectNotHasProperty('Namespace', $out);
        self::assertObjectNotHasProperty('Partition', $out);
    }

    public function testJsonSerializeIncludesNamespaceAndPartition(): void
    {
        $p = new ACLPolicy(ID: 'x', Name: 'y', Namespace: 'ns', Partition: 'pt');
        $out = $p->jsonSerialize();
        self::assertSame('ns', $out->Namespace);
        self::assertSame('pt', $out->Partition);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ID = 'id-1';
        $d->Name = 'pol';
        $d->Description = 'desc';
        $d->Rules = 'r';
        $d->Datacenters = ['dc1'];
        $d->Hash = 'h';
        $d->CreateIndex = 3;
        $d->ModifyIndex = 4;
        $p = ACLPolicy::jsonUnserialize($d);
        self::assertSame('id-1', $p->getID());
        self::assertSame(['dc1'], $p->getDatacenters());
        self::assertSame('3', (string)$p->getCreateIndex());
    }

    public function testUint64Fields(): void
    {
        $max = '18446744073709551615';
        $p = new ACLPolicy(CreateIndex: 1, ModifyIndex: '2');
        self::assertSame('1', (string)$p->CreateIndex);
        self::assertSame('2', (string)$p->getModifyIndex());
        $p = new ACLPolicy(CreateIndex: BigInteger::of($max));
        self::assertSame($max, (string)$p->getCreateIndex());

        $p->setCreateIndex('4')->setModifyIndex(BigInteger::of(5));
        self::assertSame('4', (string)$p->getCreateIndex());
        self::assertSame('5', (string)$p->getModifyIndex());
        $p->setModifyIndex(6);
        self::assertSame('6', (string)$p->ModifyIndex);

        $d = new \stdClass();
        $d->CreateIndex = $max;
        $d->ModifyIndex = 9;
        $p = ACLPolicy::jsonUnserialize($d);
        self::assertSame($max, (string)$p->getCreateIndex());
        self::assertSame('9', (string)$p->getModifyIndex());
    }
}

