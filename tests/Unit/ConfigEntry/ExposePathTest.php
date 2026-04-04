<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ExposePath;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ExposePathTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ExposePath();
        self::assertSame(0, $p->getListenerPort());
        self::assertSame('', $p->getPath());
        self::assertSame(0, $p->getLocalPathPort());
        self::assertSame('', $p->getProtocol());
        self::assertFalse($p->isParsedFromCheck());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ExposePath(
            ListenerPort: 21500,
            Path: '/health',
            LocalPathPort: 8080,
            Protocol: 'http',
            ParsedFromCheck: true,
        );
        self::assertSame(21500, $p->getListenerPort());
        self::assertSame('/health', $p->getPath());
        self::assertSame(8080, $p->getLocalPathPort());
        self::assertSame('http', $p->getProtocol());
        self::assertTrue($p->isParsedFromCheck());
    }

    public function testFluentSetters(): void
    {
        $p = new ExposePath();
        $result = $p->setListenerPort(21500)
            ->setPath('/ready')
            ->setLocalPathPort(8080)
            ->setProtocol('http2')
            ->setParsedFromCheck(true);
        self::assertSame($p, $result);
        self::assertSame(21500, $p->getListenerPort());
        self::assertSame('/ready', $p->getPath());
        self::assertSame(8080, $p->getLocalPathPort());
        self::assertSame('http2', $p->getProtocol());
        self::assertTrue($p->isParsedFromCheck());
    }

}
