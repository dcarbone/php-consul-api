<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\EnvoyExtension;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class EnvoyExtensionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new EnvoyExtension();
        self::assertSame('', $e->getName());
        self::assertFalse($e->isRequired());
        self::assertSame([], $e->getArguments());
        self::assertSame('', $e->getConsulVersion());
        self::assertSame('', $e->getEnvoyVersion());
    }

    public function testConstructorWithParams(): void
    {
        $e = new EnvoyExtension(
            Name: 'lua',
            Required: true,
            Arguments: ['key' => 'val'],
            ConsulVersion: '1.15',
            EnvoyVersion: '1.25',
        );
        self::assertSame('lua', $e->getName());
        self::assertTrue($e->isRequired());
        self::assertSame(['key' => 'val'], $e->getArguments());
        self::assertSame('1.15', $e->getConsulVersion());
        self::assertSame('1.25', $e->getEnvoyVersion());
    }

    public function testFluentSetters(): void
    {
        $e = new EnvoyExtension();
        $result = $e->setName('ext')
            ->setRequired(true)
            ->setArguments(['a' => 'b'])
            ->setConsulVersion('1.16')
            ->setEnvoyVersion('1.26');
        self::assertSame($e, $result);
        self::assertSame('ext', $e->getName());
        self::assertTrue($e->isRequired());
        self::assertSame(['a' => 'b'], $e->getArguments());
        self::assertSame('1.16', $e->getConsulVersion());
        self::assertSame('1.26', $e->getEnvoyVersion());
    }

}
