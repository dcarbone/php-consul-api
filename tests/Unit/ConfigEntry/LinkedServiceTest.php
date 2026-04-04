<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\LinkedService;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class LinkedServiceTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $l = new LinkedService();
        self::assertSame('', $l->getNamespace());
        self::assertSame('', $l->getName());
        self::assertSame('', $l->getCAFile());
        self::assertSame('', $l->getCertFile());
        self::assertSame('', $l->getKeyFile());
        self::assertSame('', $l->getSNI());
    }

    public function testConstructorWithParams(): void
    {
        $l = new LinkedService(
            Namespace: 'ns',
            Name: 'web',
            CAFile: 'ca.pem',
            CertFile: 'cert.pem',
            KeyFile: 'key.pem',
            SNI: 'web.consul',
        );
        self::assertSame('ns', $l->getNamespace());
        self::assertSame('web', $l->getName());
        self::assertSame('ca.pem', $l->getCAFile());
        self::assertSame('cert.pem', $l->getCertFile());
        self::assertSame('key.pem', $l->getKeyFile());
        self::assertSame('web.consul', $l->getSNI());
    }

    public function testFluentSetters(): void
    {
        $l = new LinkedService();
        $result = $l->setNamespace('ns')
            ->setName('svc')
            ->setCAFile('ca')
            ->setCertFile('cert')
            ->setKeyFile('key')
            ->setSNI('sni');
        self::assertSame($l, $result);
        self::assertSame('ns', $l->getNamespace());
        self::assertSame('svc', $l->getName());
        self::assertSame('ca', $l->getCAFile());
        self::assertSame('cert', $l->getCertFile());
        self::assertSame('key', $l->getKeyFile());
        self::assertSame('sni', $l->getSNI());
    }

}
