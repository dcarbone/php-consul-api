<?php

namespace DCarbone\PHPConsulAPITests\Unit\ACL;

use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ACLServiceIdentityTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ACLServiceIdentity();
        self::assertSame('', $s->getServiceName());
        self::assertSame([], $s->getDatacenters());
    }

    public function testConstructorWithParams(): void
    {
        $s = new ACLServiceIdentity(ServiceName: 'web', Datacenters: ['dc1', 'dc2']);
        self::assertSame('web', $s->getServiceName());
        self::assertSame(['dc1', 'dc2'], $s->getDatacenters());
    }

    public function testFluentSetters(): void
    {
        $s = new ACLServiceIdentity();
        $result = $s->setServiceName('api')->setDatacenters('dc1');
        self::assertSame($s, $result);
        self::assertSame('api', $s->getServiceName());
        self::assertSame(['dc1'], $s->getDatacenters());
    }

    public function testJsonSerializeOmitsEmptyDatacenters(): void
    {
        $s = new ACLServiceIdentity(ServiceName: 'web');
        $out = $s->jsonSerialize();
        self::assertSame('web', $out->ServiceName);
        self::assertObjectNotHasProperty('Datacenters', $out);
    }

    public function testJsonSerializeIncludesDatacenters(): void
    {
        $s = new ACLServiceIdentity(ServiceName: 'web', Datacenters: ['dc1']);
        $out = $s->jsonSerialize();
        self::assertSame(['dc1'], $out->Datacenters);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->ServiceName = 'svc';
        $d->Datacenters = ['dc1', 'dc2'];
        $s = ACLServiceIdentity::jsonUnserialize($d);
        self::assertSame('svc', $s->getServiceName());
        self::assertSame(['dc1', 'dc2'], $s->getDatacenters());
    }
}

