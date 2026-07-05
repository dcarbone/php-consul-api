<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\MeshHTTPConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class MeshHTTPConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MeshHTTPConfig();
        self::assertFalse($m->isSanitizeXForwardClientCert());
    }

    public function testConstructorWithParams(): void
    {
        $m = new MeshHTTPConfig(SanitizeXForwardClientCert: true);
        self::assertTrue($m->isSanitizeXForwardClientCert());
    }

    public function testFluentSetters(): void
    {
        $m = new MeshHTTPConfig();
        $result = $m->setSanitizeXForwardClientCert(true);
        self::assertSame($m, $result);
        self::assertTrue($m->isSanitizeXForwardClientCert());
    }

}
