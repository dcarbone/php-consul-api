<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceIntentionsConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\SourceIntention;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionJWTRequirement;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceIntentionsConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ServiceIntentionsConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame('', $e->getNamespace());
        self::assertSame([], $e->getSources());
        self::assertNull($e->getJWT());
    }

    public function testConstructorWithParams(): void
    {
        $source = new SourceIntention(Name: 'web', Action: 'allow');
        $jwt = new IntentionJWTRequirement(Name: 'jwt');
        $e = new ServiceIntentionsConfigEntry(
            Kind: 'service-intentions',
            name: 'api',
            Partition: 'pt',
            Namespace: 'ns',
            Sources: [$source],
            JWT: $jwt,
        );
        self::assertSame('service-intentions', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertSame('ns', $e->getNamespace());
        self::assertCount(1, $e->getSources());
        self::assertSame($jwt, $e->getJWT());
    }

    public function testFluentSetters(): void
    {
        $source = new SourceIntention(Name: 'web');
        $e = new ServiceIntentionsConfigEntry();
        $result = $e->setKind('service-intentions')
            ->setName('api')
            ->setPartition('pt')
            ->setNamespace('ns')
            ->setSources($source);
        self::assertSame($e, $result);
        self::assertSame('service-intentions', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertSame('ns', $e->getNamespace());
        self::assertCount(1, $e->getSources());
    }

}
