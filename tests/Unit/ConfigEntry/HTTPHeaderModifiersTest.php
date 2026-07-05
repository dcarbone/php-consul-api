<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\HTTPHeaderModifiers;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class HTTPHeaderModifiersTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $h = new HTTPHeaderModifiers();
        self::assertSame([], $h->getAdd());
        self::assertSame([], $h->getSet());
        self::assertSame([], $h->getRemove());
    }

    public function testConstructorWithParams(): void
    {
        $h = new HTTPHeaderModifiers(
            Add: ['X-Custom' => 'value'],
            Set: ['X-Replace' => 'new-value'],
            Remove: ['X-Remove'],
        );
        self::assertSame(['X-Custom' => 'value'], $h->getAdd());
        self::assertSame(['X-Replace' => 'new-value'], $h->getSet());
        self::assertSame(['X-Remove'], $h->getRemove());
    }

    public function testFluentSetters(): void
    {
        $h = new HTTPHeaderModifiers();
        $result = $h->setAdd(['X-A' => 'a'])
            ->setSet(['X-S' => 's'])
            ->setRemove('X-R');
        self::assertSame($h, $result);
        self::assertSame(['X-A' => 'a'], $h->getAdd());
        self::assertSame(['X-S' => 's'], $h->getSet());
        self::assertSame(['X-R'], $h->getRemove());
    }

}
