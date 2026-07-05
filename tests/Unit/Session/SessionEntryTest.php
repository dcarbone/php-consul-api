<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Session;

use DCarbone\PHPConsulAPI\Session\ServiceCheck;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SessionEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $se = new SessionEntry();
        self::assertSame(0, $se->getCreateIndex());
        self::assertSame('', $se->getID());
        self::assertSame('', $se->getName());
        self::assertSame('', $se->getNode());
        self::assertSame('', $se->getBehavior());
        self::assertSame('', $se->getTTL());
        self::assertSame('', $se->getNamespace());
        self::assertSame([], $se->getChecks());
        self::assertSame([], $se->getNodeChecks());
        self::assertSame([], $se->getServiceChecks());
    }

    public function testConstructorWithValues(): void
    {
        $sc = new ServiceCheck(ID: 'svc-check-1', Namespace: 'ns');
        $se = new SessionEntry(
            CreateIndex: 10,
            ID: 'sess-1',
            Name: 'my-session',
            Node: 'node-1',
            Behavior: 'release',
            TTL: '30s',
            Namespace: 'default',
            Checks: ['serfHealth'],
            NodeChecks: ['node-check-1'],
            ServiceChecks: [$sc],
        );

        self::assertSame(10, $se->getCreateIndex());
        self::assertSame('sess-1', $se->getID());
        self::assertSame('my-session', $se->getName());
        self::assertSame('node-1', $se->getNode());
        self::assertSame('release', $se->getBehavior());
        self::assertSame('30s', $se->getTTL());
        self::assertSame('default', $se->getNamespace());
        self::assertSame(['serfHealth'], $se->getChecks());
        self::assertSame(['node-check-1'], $se->getNodeChecks());
        self::assertCount(1, $se->getServiceChecks());
        self::assertSame('svc-check-1', $se->getServiceChecks()[0]->getID());
    }

    public function testVariadicSetChecks(): void
    {
        $se = new SessionEntry();
        $result = $se->setChecks('check-a', 'check-b');

        self::assertSame($se, $result);
        self::assertSame(['check-a', 'check-b'], $se->getChecks());
    }

    public function testVariadicSetNodeChecks(): void
    {
        $se = new SessionEntry();
        $result = $se->setNodeChecks('nc-1', 'nc-2', 'nc-3');

        self::assertSame($se, $result);
        self::assertSame(['nc-1', 'nc-2', 'nc-3'], $se->getNodeChecks());
    }

    public function testVariadicSetServiceChecks(): void
    {
        $sc1 = new ServiceCheck(ID: 'sc-1');
        $sc2 = new ServiceCheck(ID: 'sc-2');

        $se = new SessionEntry();
        $result = $se->setServiceChecks($sc1, $sc2);

        self::assertSame($se, $result);
        self::assertCount(2, $se->getServiceChecks());
        self::assertSame('sc-1', $se->getServiceChecks()[0]->getID());
        self::assertSame('sc-2', $se->getServiceChecks()[1]->getID());
    }

    public function testVariadicSettersReplaceExisting(): void
    {
        $se = new SessionEntry(
            Checks: ['old'],
            NodeChecks: ['old-nc'],
            ServiceChecks: [new ServiceCheck(ID: 'old-sc')],
        );

        $se->setChecks('new');
        $se->setNodeChecks('new-nc');
        $se->setServiceChecks(new ServiceCheck(ID: 'new-sc'));

        self::assertSame(['new'], $se->getChecks());
        self::assertSame(['new-nc'], $se->getNodeChecks());
        self::assertCount(1, $se->getServiceChecks());
        self::assertSame('new-sc', $se->getServiceChecks()[0]->getID());
    }

    public function testFluentSetters(): void
    {
        $se = new SessionEntry();
        $result = $se
            ->setCreateIndex(1)
            ->setID('id')
            ->setName('name')
            ->setNode('node')
            ->setBehavior('delete')
            ->setTTL('60s')
            ->setNamespace('ns')
            ->setChecks('c1')
            ->setNodeChecks('nc1')
            ->setServiceChecks(new ServiceCheck(ID: 'sc1'));

        self::assertSame($se, $result);
    }

    public function testJsonSerialize(): void
    {
        $se = new SessionEntry(
            CreateIndex: 5,
            ID: 'sess-x',
            Name: 'test',
            Node: 'n1',
            Behavior: 'release',
            TTL: '15s',
            Checks: ['serfHealth'],
            NodeChecks: ['nc1'],
            ServiceChecks: [new ServiceCheck(ID: 'sc1', Namespace: 'ns')],
        );

        $out = $se->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(5, $out->CreateIndex);
        self::assertSame('sess-x', $out->ID);
        self::assertSame('test', $out->Name);
        self::assertSame('release', $out->Behavior);
        self::assertSame(['serfHealth'], $out->Checks);
        self::assertSame(['nc1'], $out->NodeChecks);
        self::assertIsArray($out->ServiceChecks);
        self::assertCount(1, $out->ServiceChecks);
    }

    public function testJsonSerializeOmitsEmptyNamespace(): void
    {
        $se = new SessionEntry();
        $out = $se->jsonSerialize();

        self::assertObjectNotHasProperty('Namespace', $out);
    }

    public function testJsonSerializeIncludesNonEmptyNamespace(): void
    {
        $se = new SessionEntry(Namespace: 'my-ns');
        $out = $se->jsonSerialize();

        self::assertSame('my-ns', $out->Namespace);
    }

    public function testJsonUnserialize(): void
    {
        $scObj = new \stdClass();
        $scObj->ID = 'sc-unserialized';
        $scObj->Namespace = 'ns';

        $decoded = new \stdClass();
        $decoded->CreateIndex = 20;
        $decoded->ID = 'sess-from-json';
        $decoded->Name = 'json-session';
        $decoded->Node = 'json-node';
        $decoded->LockDelay = 15000000000; // 15 seconds in nanoseconds
        $decoded->Behavior = 'release';
        $decoded->TTL = '30s';
        $decoded->Checks = ['serfHealth'];
        $decoded->NodeChecks = ['nc1'];
        $decoded->ServiceChecks = [$scObj];

        $se = SessionEntry::jsonUnserialize($decoded);

        self::assertSame(20, $se->getCreateIndex());
        self::assertSame('sess-from-json', $se->getID());
        self::assertSame('json-session', $se->getName());
        self::assertSame('json-node', $se->getNode());
        self::assertSame('release', $se->getBehavior());
        self::assertSame('30s', $se->getTTL());
        self::assertSame(['serfHealth'], $se->getChecks());
        self::assertSame(['nc1'], $se->getNodeChecks());
        self::assertCount(1, $se->getServiceChecks());
        self::assertInstanceOf(ServiceCheck::class, $se->getServiceChecks()[0]);
        self::assertSame('sc-unserialized', $se->getServiceChecks()[0]->getID());
    }

    public function testLockDelayDurationHandling(): void
    {
        // Integer nanoseconds
        $se = new SessionEntry(LockDelay: 5000000000);
        self::assertSame(5.0, $se->getLockDelay()->Seconds());

        // String duration
        $se2 = new SessionEntry(LockDelay: '10s');
        self::assertSame(10.0, $se2->getLockDelay()->Seconds());

        // Null defaults to zero
        $se3 = new SessionEntry();
        self::assertSame(0.0, $se3->getLockDelay()->Seconds());
    }

    public function testSetLockDelay(): void
    {
        $se = new SessionEntry();
        $result = $se->setLockDelay('5s');

        self::assertSame($se, $result);
        self::assertSame(5.0, $se->getLockDelay()->Seconds());
    }
}

