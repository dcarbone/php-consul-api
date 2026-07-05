<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\AccessLogsConfig;
use DCarbone\PHPConsulAPI\ConfigEntry\LogSinkType;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class AccessLogsConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AccessLogsConfig();
        self::assertFalse($c->isEnabled());
        self::assertFalse($c->isDisableListenerLogs());
        self::assertSame(LogSinkType::Default, $c->getType());
        self::assertSame('', $c->getPath());
        self::assertSame('', $c->getJSONFormat());
        self::assertSame('', $c->getTextFormat());
    }

    public function testConstructorWithParams(): void
    {
        $c = new AccessLogsConfig(
            Enabled: true,
            DisableListenerLogs: true,
            Type: LogSinkType::File,
            Path: '/var/log/envoy.log',
            JSONFormat: '{"start_time":"%START_TIME%"}',
            TextFormat: '%START_TIME%',
        );
        self::assertTrue($c->isEnabled());
        self::assertTrue($c->isDisableListenerLogs());
        self::assertSame(LogSinkType::File, $c->getType());
        self::assertSame('/var/log/envoy.log', $c->getPath());
        self::assertSame('{"start_time":"%START_TIME%"}', $c->getJSONFormat());
        self::assertSame('%START_TIME%', $c->getTextFormat());
    }

    public function testFluentSetters(): void
    {
        $c = new AccessLogsConfig();
        $result = $c->setEnabled(true)
            ->setDisableListenerLogs(true)
            ->setType(LogSinkType::StdErr)
            ->setPath('/log')
            ->setJSONFormat('json')
            ->setTextFormat('text');
        self::assertSame($c, $result);
        self::assertTrue($c->isEnabled());
        self::assertTrue($c->isDisableListenerLogs());
        self::assertSame(LogSinkType::StdErr, $c->getType());
        self::assertSame('/log', $c->getPath());
        self::assertSame('json', $c->getJSONFormat());
        self::assertSame('text', $c->getTextFormat());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $c = new AccessLogsConfig(Type: 'file');
        self::assertSame(LogSinkType::File, $c->getType());
    }

    public function testSetTypeWithString(): void
    {
        $c = new AccessLogsConfig();
        $c->setType('stderr');
        self::assertSame(LogSinkType::StdErr, $c->getType());
    }
}
