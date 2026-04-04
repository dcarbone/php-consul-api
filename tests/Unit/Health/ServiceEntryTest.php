<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $se = new ServiceEntry();
        self::assertNull($se->getNode());
        self::assertNull($se->getService());
        self::assertInstanceOf(HealthChecks::class, $se->getChecks());
    }

    public function testConstructorWithValues(): void
    {
        $node = new Node(Node: 'n1', Address: '10.0.0.1');
        $svc = new AgentService(ID: 's1', Service: 'web');
        $se = new ServiceEntry(Node: $node, Service: $svc);
        self::assertNotNull($se->getNode());
        self::assertSame('n1', $se->getNode()->getNode());
        self::assertNotNull($se->getService());
        self::assertSame('s1', $se->getService()->getID());
    }

    public function testFluentSetters(): void
    {
        $se = new ServiceEntry();
        $result = $se
            ->setNode(new Node(Node: 'x'))
            ->setService(new AgentService(ID: 'y'))
            ->setChecks(new HealthChecks());
        self::assertSame($se, $result);
    }

    public function testJsonSerialize(): void
    {
        $se = new ServiceEntry(
            Node: new Node(Node: 'n'),
            Service: new AgentService(ID: 's'),
        );
        $out = $se->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
    }
}

