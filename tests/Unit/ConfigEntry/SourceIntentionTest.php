<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionAction;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionSourceType;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class SourceIntentionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new SourceIntention();
        self::assertSame('', $s->getName());
        self::assertSame('', $s->getPeer());
        self::assertSame('', $s->getPartition());
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getSamenessGroup());
        self::assertSame(IntentionAction::UNDEFINED, $s->getAction());
        self::assertSame([], $s->getPermissions());
        self::assertSame(0, $s->getPrecedence());
        self::assertSame(IntentionSourceType::UNDEFINED, $s->getType());
        self::assertSame('', $s->getDescription());
        self::assertSame('', $s->getLegacyID());
        self::assertNull($s->getLegacyMeta());
        self::assertNull($s->getLegacyCreateTime());
        self::assertNull($s->getLegacyUpdateTime());
    }

    public function testConstructorWithParams(): void
    {
        $s = new SourceIntention(
            Name: 'web',
            Peer: 'peer1',
            Partition: 'pt',
            Namespace: 'ns',
            SamenessGroup: 'sg',
            Action: IntentionAction::Allow,
            Precedence: 9,
            Type: IntentionSourceType::Consul,
            Description: 'Allow web',
        );
        self::assertSame('web', $s->getName());
        self::assertSame('peer1', $s->getPeer());
        self::assertSame('pt', $s->getPartition());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('sg', $s->getSamenessGroup());
        self::assertSame(IntentionAction::Allow, $s->getAction());
        self::assertSame(9, $s->getPrecedence());
        self::assertSame(IntentionSourceType::Consul, $s->getType());
        self::assertSame('Allow web', $s->getDescription());
    }

    public function testFluentSetters(): void
    {
        $s = new SourceIntention();
        $result = $s->setName('api')
            ->setPeer('peer2')
            ->setPartition('pt')
            ->setNamespace('ns')
            ->setSamenessGroup('sg')
            ->setAction(IntentionAction::Deny)
            ->setPrecedence(5)
            ->setType(IntentionSourceType::Consul)
            ->setDescription('Deny api');
        self::assertSame($s, $result);
        self::assertSame('api', $s->getName());
        self::assertSame('peer2', $s->getPeer());
        self::assertSame('pt', $s->getPartition());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame(IntentionAction::Deny, $s->getAction());
        self::assertSame(5, $s->getPrecedence());
        self::assertSame('Deny api', $s->getDescription());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $s = new SourceIntention(Action: 'allow', Type: 'consul');
        self::assertSame(IntentionAction::Allow, $s->getAction());
        self::assertSame(IntentionSourceType::Consul, $s->getType());
    }
}
